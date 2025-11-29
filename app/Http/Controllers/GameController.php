<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Services\TarotScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function __construct(
        private TarotScoreService $scoreService
    ) {}

    /**
     * Afficher la liste des parties
     */
    public function index()
    {
        $games = Game::with(['gamePlayers.user'])
            ->orderBy('played_at', 'desc')
            ->paginate(20);

        return view('games.index', compact('games'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $users = User::orderBy('name')->get();
        
        // Récupérer les joueurs pré-sélectionnés si fournis
        $preselectedPlayers = $request->input('players', []);
        
        // S'assurer que c'est un tableau de valeurs (pas associatif)
        if (is_array($preselectedPlayers)) {
            $preselectedPlayers = array_values($preselectedPlayers);
        }
        
        return view('games.create', compact('users', 'preselectedPlayers'));
    }

    /**
     * Enregistrer une nouvelle partie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'played_at' => 'required|date',
            'nombre_joueurs' => 'required|in:4,5',
            'contract_type' => 'required|in:petite,garde,garde_sans,garde_contre',
            'points' => 'required|integer|min:0|max:91',
            'oudlers' => 'required|integer|min:0|max:3',
            'contract_success' => 'required|boolean',
            'preneur_id' => 'required|exists:users,id',
            'attaquant_id' => 'nullable|exists:users,id',
            'player_ids' => 'required|array',
            'player_ids.*' => 'exists:users,id',
            'petit_au_bout' => 'nullable|boolean',
            'petit_au_bout_team' => 'nullable|in:attaque,defense',
            'poignee' => 'nullable|in:aucune,simple,double,triple',
            'poignee_team' => 'nullable|in:attaque,defense',
            'chelem' => 'nullable|in:aucun,annonce_reussi,annonce_chute,non_annonce',
            'miseres' => 'nullable|array',
            'miseres.*' => 'nullable|array',
        ]);

        // Vérifier que le nombre de joueurs correspond
        if (count($validated['player_ids']) != $validated['nombre_joueurs']) {
            return back()->withInput()->withErrors(['player_ids' => 'Le nombre de joueurs sélectionnés ne correspond pas.']);
        }

        DB::beginTransaction();

        try {
            // Créer la partie
            $game = Game::create([
                'played_at' => $validated['played_at'],
                'nombre_joueurs' => $validated['nombre_joueurs'],
                'contract_type' => $validated['contract_type'],
                'contract_success' => $validated['contract_success'],
                'points' => $validated['points'],
                'oudlers' => $validated['oudlers'],
                'bonus_points' => 0, // Conservé pour compatibilité
                'petit_au_bout' => $validated['petit_au_bout'] ?? false,
                'petit_au_bout_team' => $validated['petit_au_bout_team'] ?? null,
                'poignee' => $validated['poignee'] ?? 'aucune',
                'poignee_team' => $validated['poignee_team'] ?? null,
                'chelem' => $validated['chelem'] ?? 'aucun',
            ]);

            // Enregistrer les misères individuelles
            if (!empty($validated['miseres'])) {
                foreach ($validated['miseres'] as $userId => $misereTypes) {
                    if (!empty($misereTypes['tetes'])) {
                        $game->miseres()->create([
                            'user_id' => $userId,
                            'type' => 'tetes',
                        ]);
                    }
                    if (!empty($misereTypes['atouts'])) {
                        $game->miseres()->create([
                            'user_id' => $userId,
                            'type' => 'atouts',
                        ]);
                    }
                }
            }

            // Préparer les joueurs avec leurs rôles
            $players = [];
            $preneurId = $validated['preneur_id'];
            $attaquantId = $validated['attaquant_id'] ?? null;
            
            // Le preneur
            $players[$preneurId] = 'preneur';
            
            // L'attaquant (si partie à 5)
            if ($attaquantId) {
                $players[$attaquantId] = 'attaquant';
            }

            // Les défenseurs (tous les autres)
            foreach ($validated['player_ids'] as $playerId) {
                if ($playerId != $preneurId && $playerId != $attaquantId) {
                    $players[$playerId] = 'defenseur';
                }
            }

            // Mettre à jour les ELO
            $this->scoreService->updateEloRatings($game, $players);

            DB::commit();

            // Récupérer les IDs des joueurs pour permettre de rejouer facilement
            $playerIds = array_keys($players);

            return redirect()
                ->route('games.show', $game)
                ->with('success', 'Partie enregistrée avec succès !')
                ->with('player_ids', $playerIds);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher une partie
     */
    public function show(Game $game)
    {
        $game->load(['gamePlayers.user']);
        
        // Récupérer les IDs des joueurs pour le bouton "Rejouer"
        $playerIds = $game->gamePlayers->pluck('user_id')->toArray();
        
        return view('games.show', compact('game', 'playerIds'));
    }

    /**
     * Afficher le classement des joueurs
     */
    public function leaderboard()
    {
        $users = User::where('games_played', '>', 0)
            ->orderBy('elo', 'desc')
            ->get();

        return view('games.leaderboard', compact('users'));
    }
}
