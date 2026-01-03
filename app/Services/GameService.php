<?php

namespace App\Services;

use App\Models\Game;
use App\Models\User;
use App\Repositories\GameRepository;
use Illuminate\Support\Facades\DB;

class GameService
{
    public function __construct(
        private GameRepository $gameRepository,
        private TarotScoreService $scoreService
    ) {}

    /**
     * Créer une nouvelle partie avec tous ses éléments
     */
    public function createGame(array $data): Game
    {
        DB::beginTransaction();

        try {
            // Créer la partie
            $game = $this->gameRepository->create($data);

            // Enregistrer les misères
            $this->saveMiseres($game, $data['miseres'] ?? []);

            // Préparer les joueurs avec leurs rôles
            $players = $this->preparePlayersWithRoles($data);

            // Calculer et enregistrer les scores et ELO
            $this->scoreService->updateEloRatings($game, $players);

            DB::commit();

            return $game;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mettre à jour une partie existante
     */
    public function updateGame(Game $game, array $data): Game
    {
        DB::beginTransaction();

        try {
            // Restaurer les ELO avant la modification
            $this->restoreEloRatings($game);

            // Supprimer les anciennes données
            $this->gameRepository->deleteGamePlayers($game);
            $this->gameRepository->deleteMiseres($game);

            // Mettre à jour la partie
            $this->gameRepository->update($game, $data);

            // Enregistrer les nouvelles misères
            $this->saveMiseres($game, $data['miseres'] ?? []);

            // Préparer les joueurs avec leurs rôles
            $players = $this->preparePlayersWithRoles($data);

            // Recalculer les scores et ELO
            $this->scoreService->updateEloRatings($game, $players);

            DB::commit();

            return $game->fresh(['gamePlayers.user', 'miseres']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Restaurer les ELO des joueurs avant une partie
     */
    private function restoreEloRatings(Game $game): void
    {
        foreach ($game->gamePlayers as $gamePlayer) {
            $user = $gamePlayer->user;
            $user->update([
                'elo' => $gamePlayer->elo_before,
                'games_played' => max(0, $user->games_played - 1),
            ]);
        }
    }

    /**
     * Enregistrer les misères d'une partie
     */
    private function saveMiseres(Game $game, array $miseres): void
    {
        foreach ($miseres as $userId => $misereTypes) {
            if (!empty($misereTypes['tetes'])) {
                $this->gameRepository->createMisere($game, $userId, 'tetes');
            }
            if (!empty($misereTypes['atouts'])) {
                $this->gameRepository->createMisere($game, $userId, 'atouts');
            }
        }
    }

    /**
     * Préparer les joueurs avec leurs rôles
     */
    private function preparePlayersWithRoles(array $data): array
    {
        $players = [];
        $preneurId = $data['preneur_id'];
        $attaquantId = $data['attaquant_id'] ?? null;

        // Le preneur
        $players[$preneurId] = 'preneur';

        // L'attaquant (si partie à 5)
        if ($attaquantId) {
            $players[$attaquantId] = 'attaquant';
        }

        // Les défenseurs (tous les autres)
        foreach ($data['player_ids'] as $playerId) {
            if ($playerId != $preneurId && $playerId != $attaquantId) {
                $players[$playerId] = 'defenseur';
            }
        }

        return $players;
    }

    /**
     * Récupérer les IDs des joueurs d'une partie
     */
    public function getPlayerIds(Game $game): array
    {
        return $game->gamePlayers->pluck('user_id')->toArray();
    }
}
