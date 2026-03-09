<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use App\Models\User;
use App\Repositories\GameRepository;
use App\Services\GameService;
use App\Services\UserStatsService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct(
        private GameRepository $gameRepository,
        private GameService $gameService,
        private UserStatsService $userStatsService
    ) {}

    /**
     * Afficher la liste des parties
     */
    public function index()
    {
        $games = $this->gameRepository->getPaginated(20);
        $latestGame = $this->gameRepository->getLatestGame();

        return view('games.index', compact('games', 'latestGame'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $users = $this->gameRepository->getAllUsers();
        
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
    public function store(StoreGameRequest $request)
    {
        try {
            $game = $this->gameService->createGame($request->validated());
            $playerIds = $this->gameService->getPlayerIds($game);

            return redirect()
                ->route('games.show', $game)
                ->with('success', 'Partie enregistrée avec succès !')
                ->with('player_ids', $playerIds);

        } catch (\Exception $e) {
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
        $playerIds = $this->gameService->getPlayerIds($game);
        
        // Vérifier si c'est la dernière partie
        $latestGame = $this->gameRepository->getLatestGame();
        $isLatestGame = $latestGame && $game->id === $latestGame->id;
        
        return view('games.show', compact('game', 'playerIds', 'isLatestGame'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Game $game)
    {
        // Vérifier que c'est bien la dernière partie
        $latestGame = $this->gameRepository->getLatestGame();
        
        if (!$latestGame || $game->id !== $latestGame->id) {
            return redirect()
                ->route('games.index')
                ->with('error', 'Seule la dernière partie jouée peut être modifiée.');
        }
        
        $game = $this->gameRepository->findWithRelations($game->id);
        $users = $this->gameRepository->getAllUsers();
        
        return view('games.edit', compact('game', 'users'));
    }

    /**
     * Mettre à jour une partie existante
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        try {
            $this->gameService->updateGame($game, $request->validated());

            return redirect()
                ->route('games.show', $game)
                ->with('success', 'Partie modifiée avec succès !');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher le classement des joueurs
     */
    public function leaderboard()
    {
        $users = $this->gameRepository->getLeaderboard();

        return view('games.leaderboard', compact('users'));
    }

    /**
     * Afficher les statistiques du joueur connecté
     */
    public function stats()
    {
        $user = auth()->user();
        $stats = $this->userStatsService->getUserStats($user);

        return view('games.stats', array_merge(['user' => $user, 'isOwnProfile' => true], $stats));
    }

    /**
     * Afficher les statistiques d'un joueur donné (route publique)
     */
    public function userStats(User $user)
    {
        $stats = $this->userStatsService->getUserStats($user);
        $isOwnProfile = auth()->check() && auth()->id() === $user->id;

        return view('games.stats', array_merge(['user' => $user, 'isOwnProfile' => $isOwnProfile], $stats));
    }
}

