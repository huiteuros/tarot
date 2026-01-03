<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class GameRepository
{
    /**
     * Récupérer toutes les parties paginées
     */
    public function getPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return Game::with(['gamePlayers.user'])
            ->orderBy('played_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Récupérer une partie avec ses relations
     */
    public function findWithRelations(int $id): ?Game
    {
        return Game::with(['gamePlayers.user', 'miseres'])
            ->findOrFail($id);
    }

    /**
     * Créer une nouvelle partie
     */
    public function create(array $data): Game
    {
        return Game::create([
            'played_at' => $data['played_at'],
            'nombre_joueurs' => $data['nombre_joueurs'],
            'contract_type' => $data['contract_type'],
            'contract_success' => $data['contract_success'],
            'points' => $data['points'],
            'oudlers' => $data['oudlers'],
            'bonus_points' => 0,
            'petit_au_bout' => $data['petit_au_bout'] ?? false,
            'petit_au_bout_team' => $data['petit_au_bout_team'] ?? null,
            'poignee' => $data['poignee'] ?? 'aucune',
            'poignee_team' => $data['poignee_team'] ?? null,
            'chelem' => $data['chelem'] ?? 'aucun',
        ]);
    }

    /**
     * Mettre à jour une partie
     */
    public function update(Game $game, array $data): bool
    {
        return $game->update([
            'played_at' => $data['played_at'],
            'nombre_joueurs' => $data['nombre_joueurs'],
            'contract_type' => $data['contract_type'],
            'contract_success' => $data['contract_success'],
            'points' => $data['points'],
            'oudlers' => $data['oudlers'],
            'petit_au_bout' => $data['petit_au_bout'] ?? false,
            'petit_au_bout_team' => $data['petit_au_bout_team'] ?? null,
            'poignee' => $data['poignee'] ?? 'aucune',
            'poignee_team' => $data['poignee_team'] ?? null,
            'chelem' => $data['chelem'] ?? 'aucun',
        ]);
    }

    /**
     * Supprimer une partie
     */
    public function delete(Game $game): bool
    {
        return $game->delete();
    }

    /**
     * Récupérer tous les utilisateurs triés par nom
     */
    public function getAllUsers(): Collection
    {
        return User::orderBy('name')->get();
    }

    /**
     * Récupérer le classement des joueurs
     */
    public function getLeaderboard(): Collection
    {
        return User::where('games_played', '>', 0)
            ->orderBy('elo', 'desc')
            ->get();
    }

    /**
     * Récupérer les joueurs d'une partie
     */
    public function getGamePlayers(Game $game): Collection
    {
        return $game->gamePlayers()
            ->with('user')
            ->get();
    }

    /**
     * Créer une misère pour une partie
     */
    public function createMisere(Game $game, int $userId, string $type): void
    {
        $game->miseres()->create([
            'user_id' => $userId,
            'type' => $type,
        ]);
    }

    /**
     * Supprimer toutes les misères d'une partie
     */
    public function deleteMiseres(Game $game): void
    {
        $game->miseres()->delete();
    }

    /**
     * Supprimer tous les joueurs d'une partie
     */
    public function deleteGamePlayers(Game $game): void
    {
        $game->gamePlayers()->delete();
    }
}
