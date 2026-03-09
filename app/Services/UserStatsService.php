<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserStatsService
{
    /**
     * Obtenir toutes les statistiques d'un utilisateur
     */
    public function getUserStats(User $user): array
    {
        // Récupérer toutes les parties du joueur
        $gamePlayers = DB::table('game_players')
            ->join('games', 'game_players.game_id', '=', 'games.id')
            ->where('game_players.user_id', $user->id)
            ->orderBy('games.played_at', 'asc')
            ->select(
                'games.id',
                'games.played_at',
                'games.contract_type',
                'games.contract_success',
                'games.points',
                'games.oudlers',
                'game_players.role',
                'game_players.score',
                'game_players.elo_before',
                'game_players.elo_after',
                'game_players.elo_change'
            )
            ->get();

        $eloHistory = $this->getEloHistory($gamePlayers);
        $totalGames = $gamePlayers->count();
        $winRate = $this->calculateWinRate($gamePlayers);
        $timesTaken = $this->getTimesTaken($gamePlayers);
        $avgOudlers = $this->getAverageOudlers($gamePlayers);
        $avgPoints = $this->getAveragePoints($gamePlayers);
        $preneurWinRate = $this->getPreneurWinRate($gamePlayers);
        $peakElo = $this->getPeakElo($gamePlayers, $user);
        $statsByContract = $this->getStatsByContractType($gamePlayers);
        $bestWin = $this->getBestWin($gamePlayers);
        $worstDefeat = $this->getWorstDefeat($gamePlayers);
        $timesCalled = $this->getTimesCalled($gamePlayers);
        $partnerStats = $this->getPartnerStats($user, $gamePlayers);
        $bestPartner = !empty($partnerStats) ? $partnerStats[0] : null;

        return compact(
            'eloHistory',
            'totalGames',
            'winRate',
            'timesTaken',
            'timesCalled',
            'avgOudlers',
            'avgPoints',
            'preneurWinRate',
            'peakElo',
            'statsByContract',
            'bestWin',
            'worstDefeat',
            'bestPartner',
            'partnerStats'
        );
    }

    /**
     * Obtenir l'historique ELO
     */
    private function getEloHistory($gamePlayers): array
    {
        return $gamePlayers->map(function($gp) {
            return [
                'date' => $gp->played_at,
                'elo_before' => $gp->elo_before,
                'elo_after' => $gp->elo_after
            ];
        })->toArray();
    }

    /**
     * Calculer le taux de victoire
     */
    private function calculateWinRate($gamePlayers): float
    {
        $totalGames = $gamePlayers->count();
        
        if ($totalGames === 0) {
            return 0.0;
        }

        $wins = $gamePlayers->filter(function($gp) {
            if ($gp->role === 'preneur' || $gp->role === 'attaquant') {
                return $gp->contract_success;
            } else {
                return !$gp->contract_success;
            }
        })->count();

        return round(($wins / $totalGames) * 100, 1);
    }

    /**
     * Obtenir le nombre de fois où le joueur a pris
     */
    private function getTimesTaken($gamePlayers): int
    {
        return $gamePlayers->where('role', 'preneur')->count();
    }

    /**
     * Obtenir la moyenne de bouts quand le joueur prend
     */
    private function getAverageOudlers($gamePlayers): float
    {
        $oudlersWhenTaking = $gamePlayers->where('role', 'preneur')->pluck('oudlers');
        
        return $oudlersWhenTaking->count() > 0 
            ? round($oudlersWhenTaking->avg(), 2) 
            : 0.0;
    }

    /**
     * Obtenir la moyenne de points quand le joueur prend
     */
    private function getAveragePoints($gamePlayers): float
    {
        $pointsWhenTaking = $gamePlayers->where('role', 'preneur')->pluck('points');
        
        return $pointsWhenTaking->count() > 0 
            ? round($pointsWhenTaking->avg(), 1) 
            : 0.0;
    }

    /**
     * Calculer le taux de victoire en tant que preneur
     */
    private function getPreneurWinRate($gamePlayers): float
    {
        $preneurGames = $gamePlayers->where('role', 'preneur');
        $preneurWins = $preneurGames->where('contract_success', true)->count();
        
        return $preneurGames->count() > 0 
            ? round(($preneurWins / $preneurGames->count()) * 100, 1) 
            : 0.0;
    }

    /**
     * Obtenir le nombre de fois où le joueur a été appelé (attaquant)
     */
    private function getTimesCalled($gamePlayers): int
    {
        return $gamePlayers->where('role', 'attaquant')->count();
    }

    /**
     * Obtenir la meilleure victoire (score le plus élevé positif)
     */
    private function getBestWin($gamePlayers): ?int
    {
        $positiveScores = $gamePlayers->where('score', '>', 0);

        return $positiveScores->isNotEmpty() ? (int) $positiveScores->max('score') : null;
    }

    /**
     * Obtenir la plus grosse défaite (score le plus négatif)
     */
    private function getWorstDefeat($gamePlayers): ?int
    {
        $negativeScores = $gamePlayers->where('score', '<', 0);

        return $negativeScores->isNotEmpty() ? (int) $negativeScores->min('score') : null;
    }

    /**
     * Obtenir le meilleur ELO atteint par le joueur
     */
    private function getPeakElo($gamePlayers, User $user): int
    {
        if ($gamePlayers->isEmpty()) {
            return $user->elo;
        }

        $maxEloAfter = $gamePlayers->max('elo_after');
        $maxEloBefore = $gamePlayers->max('elo_before');

        return (int) max($maxEloAfter, $maxEloBefore, $user->elo);
    }

    /**
     * Obtenir les statistiques par type de contrat en tant que preneur
     */
    private function getStatsByContractType($gamePlayers): array
    {
        $contractTypes = ['petite', 'garde', 'garde_sans', 'garde_contre'];
        $contractLabels = [
            'petite' => 'Petite',
            'garde' => 'Garde',
            'garde_sans' => 'Garde Sans',
            'garde_contre' => 'Garde Contre',
        ];

        $preneurGames = $gamePlayers->where('role', 'preneur');
        $stats = [];

        foreach ($contractTypes as $type) {
            $gamesOfType = $preneurGames->where('contract_type', $type);
            $count = $gamesOfType->count();

            if ($count === 0) {
                $stats[$type] = [
                    'label' => $contractLabels[$type],
                    'count' => 0,
                    'wins' => 0,
                    'winRate' => 0.0,
                    'avgPoints' => 0.0,
                    'avgOudlers' => 0.0,
                ];
                continue;
            }

            $wins = $gamesOfType->where('contract_success', true)->count();

            $stats[$type] = [
                'label' => $contractLabels[$type],
                'count' => $count,
                'wins' => $wins,
                'winRate' => round(($wins / $count) * 100, 1),
                'avgPoints' => round($gamesOfType->avg('points'), 1),
                'avgOudlers' => round($gamesOfType->avg('oudlers'), 2),
            ];
        }

        return $stats;
    }

    /**
     * Obtenir les statistiques des partenaires
     */
    private function getPartnerStats(User $user, $gamePlayers): array
    {
        $partnerStats = [];
        $preneurOrAttaquantGames = $gamePlayers->whereIn('role', ['preneur', 'attaquant']);
        
        foreach ($preneurOrAttaquantGames as $game) {
            // Récupérer tous les coéquipiers de cette partie
            $teammates = DB::table('game_players')
                ->join('users', 'game_players.user_id', '=', 'users.id')
                ->where('game_players.game_id', $game->id)
                ->whereIn('game_players.role', ['preneur', 'attaquant'])
                ->where('game_players.user_id', '!=', $user->id)
                ->select('users.id', 'users.name', 'game_players.role')
                ->get();

            foreach ($teammates as $teammate) {
                if (!isset($partnerStats[$teammate->id])) {
                    $partnerStats[$teammate->id] = [
                        'name' => $teammate->name,
                        'games' => 0,
                        'wins' => 0
                    ];
                }
                $partnerStats[$teammate->id]['games']++;
                if ($game->contract_success) {
                    $partnerStats[$teammate->id]['wins']++;
                }
            }
        }

        // Calculer le win rate pour chaque partenaire
        foreach ($partnerStats as $id => $stats) {
            $partnerStats[$id]['winRate'] = $stats['games'] > 0 
                ? round(($stats['wins'] / $stats['games']) * 100, 1) 
                : 0.0;
        }

        // Trier par nombre de parties jouées ensemble
        usort($partnerStats, function($a, $b) {
            return $b['games'] - $a['games'];
        });

        return $partnerStats;
    }
}
