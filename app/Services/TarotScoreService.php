<?php

namespace App\Services;

use App\Models\Game;
use App\Models\User;
use App\Models\GamePlayer;

class TarotScoreService
{
    /**
     * Calcule le score d'une partie de tarot
     * 
     * @param string $contractType Type de contrat (petite, garde, garde_sans, garde_contre)
     * @param int $points Points du preneur
     * @param int $oudlers Nombre de bouts (0-3)
     * @param bool $success Si le contrat est réussi
     * @param array $bonus Tableau des bonus : ['petit_au_bout' => bool, 'petit_au_bout_team' => string, 'poignee' => string, 'poignee_team' => string, 'chelem' => string]
     * @return int Score de base pour le preneur
     */
    public function calculateScore(string $contractType, int $points, int $oudlers, bool $success, array $bonus = []): int
    {
        // Déterminer le seuil de points nécessaires selon les bouts
        $threshold = match($oudlers) {
            0 => 56,
            1 => 51,
            2 => 41,
            3 => 36,
            default => 56,
        };

        // Calculer la différence
        $difference = $points - $threshold;
        
        if (!$success) {
            $difference = abs($difference);
        }

        // Multiplicateur selon le contrat
        $multiplier = match($contractType) {
            'petite' => 1,
            'garde' => 2,
            'garde_sans' => 4,
            'garde_contre' => 6,
            default => 1,
        };

        // Score de base: 25 points + différence
        $baseScore = 25 + $difference;
        
        // Appliquer le multiplicateur
        $score = $baseScore * $multiplier;

        // Inverser le score si le contrat échoue
        if (!$success) {
            $score = -$score;
        }

        // Calculer les bonus
        $bonusPoints = 0;

        // Petit au bout : +10 points (* multiplicateur)
        if (!empty($bonus['petit_au_bout']) && $bonus['petit_au_bout']) {
            $petitPoints = 10 * $multiplier;
            // Si c'est la défense qui a le petit au bout, inverse le score
            if (isset($bonus['petit_au_bout_team']) && $bonus['petit_au_bout_team'] === 'defense') {
                $bonusPoints -= $petitPoints;
            } else {
                $bonusPoints += $petitPoints;
            }
        }

        // Poignée : simple = 20, double = 30, triple = 40 (pas multiplié par le contrat)
        if (!empty($bonus['poignee']) && $bonus['poignee'] !== 'aucune') {
            $poigneePoints = match($bonus['poignee']) {
                'simple' => 20,
                'double' => 30,
                'triple' => 40,
                default => 0,
            };
            
            // La poignée rapporte toujours à celui qui l'annonce
            if (isset($bonus['poignee_team']) && $bonus['poignee_team'] === 'defense') {
                $bonusPoints -= $poigneePoints;
            } else {
                $bonusPoints += $poigneePoints;
            }
        }

        // Chelem : 400 points (* multiplicateur)
        if (!empty($bonus['chelem'])) {
            $chelemPoints = match($bonus['chelem']) {
                'annonce_reussi' => 400,  // Chelem annoncé et réussi
                'annonce_chute' => -200,  // Chelem annoncé mais raté
                'non_annonce' => 200,     // Chelem réussi non annoncé
                default => 0,
            };
            $bonusPoints += $chelemPoints * $multiplier;
        }

        return $score + $bonusPoints;
    }

    /**
     * Calcule le changement d'ELO pour deux joueurs
     * 
     * @param int $playerElo ELO du joueur
     * @param int $opponentElo ELO moyen des adversaires
     * @param float $score Score du joueur (1 = victoire, 0 = défaite, 0.5 = égalité)
     * @param int $kFactor Facteur K (volatilité)
     * @return int Changement d'ELO
     */
    public function calculateEloChange(int $playerElo, int $opponentElo, float $score, int $kFactor = 32): int
    {
        // Calcul de la probabilité de victoire attendue
        $expectedScore = 1 / (1 + pow(10, ($opponentElo - $playerElo) / 400));
        
        // Changement d'ELO
        $eloChange = round($kFactor * ($score - $expectedScore));
        
        return (int) $eloChange;
    }

    /**
     * Met à jour les ELO de tous les joueurs d'une partie
     * 
     * @param Game $game La partie jouée
     * @param array $players Tableau des joueurs avec leurs rôles
     * @return void
     */
    public function updateEloRatings(Game $game, array $players): void
    {
        $preneurId = null;
        $attaquantIds = [];
        $defenseurIds = [];

        // Classifier les joueurs
        foreach ($players as $playerId => $role) {
            if ($role === 'preneur') {
                $preneurId = $playerId;
                $attaquantIds[] = $playerId;
            } elseif ($role === 'attaquant') {
                $attaquantIds[] = $playerId;
            } else {
                $defenseurIds[] = $playerId;
            }
        }

        // Récupérer tous les joueurs
        $allPlayers = User::whereIn('id', array_keys($players))->get()->keyBy('id');

        // Calculer les ELO moyens
        $attaquantEloMoyen = $attaquantIds 
            ? collect($attaquantIds)->avg(fn($id) => $allPlayers[$id]->elo)
            : 0;
        
        $defenseurEloMoyen = $defenseurIds
            ? collect($defenseurIds)->avg(fn($id) => $allPlayers[$id]->elo)
            : 0;

        // Déterminer qui a gagné (1 = attaquants gagnent, 0 = défenseurs gagnent)
        $attaquantScore = $game->contract_success ? 1 : 0;
        $defenseurScore = 1 - $attaquantScore;

        // Calculer le score de la partie pour chaque joueur
        $bonus = [
            'petit_au_bout' => $game->petit_au_bout,
            'petit_au_bout_team' => $game->petit_au_bout_team,
            'poignee' => $game->poignee,
            'poignee_team' => $game->poignee_team,
            'chelem' => $game->chelem,
        ];
        
        $gameScore = $this->calculateScore(
            $game->contract_type,
            $game->points,
            $game->oudlers,
            $game->contract_success,
            $bonus
        );

        // Calculer les bonus de misères pour chaque joueur
        // Règle : celui qui annonce gagne +10 × (nb joueurs - 1)
        // Tous les autres perdent -10
        $nombreJoueurs = count($players);
        $miseresBonus = array_fill_keys(array_keys($players), 0);
        
        foreach ($game->miseres as $misere) {
            // Le joueur qui annonce la misère
            $miseresBonus[$misere->user_id] += 10 * ($nombreJoueurs - 1);
            
            // Tous les autres joueurs perdent 10 points
            foreach (array_keys($players) as $playerId) {
                if ($playerId != $misere->user_id) {
                    $miseresBonus[$playerId] -= 10;
                }
            }
        }

        // Mettre à jour chaque joueur
        $nombreDefenseurs = count($defenseurIds);
        $aUnAppele = count($attaquantIds) > 1; // Y a-t-il un appelé ?
        
        foreach ($players as $playerId => $role) {
            $player = $allPlayers[$playerId];
            $eloBefore = $player->elo;

            // Calculer le score de la partie selon le rôle et le nombre de joueurs
            if ($role === 'preneur') {
                if ($nombreJoueurs == 4) {
                    // À 4 joueurs : preneur × 3 (contre 3 défenseurs)
                    $playerScore = $gameScore * 3;
                } elseif ($aUnAppele) {
                    // À 5 joueurs avec appelé : preneur × 2
                    $playerScore = $gameScore * 2;
                } else {
                    // À 5 joueurs sans appelé : preneur × 4 (contre 4 défenseurs)
                    $playerScore = $gameScore * 4;
                }
            } elseif ($role === 'attaquant') {
                // À 5 joueurs avec appelé : l'appelé × 1
                $playerScore = $gameScore;
            } else {
                // Les défenseurs : score × (-1) chacun
                $playerScore = -$gameScore;
            }

            // Ajouter les bonus de misères individuelles
            $playerScore += $miseresBonus[$playerId] ?? 0;

            // Le changement d'ELO est simplement le score de la partie
            $eloChange = $playerScore;
            $eloAfter = $eloBefore + $eloChange;

            // Créer l'entrée game_player
            GamePlayer::create([
                'game_id' => $game->id,
                'user_id' => $playerId,
                'role' => $role,
                'score' => $playerScore,
                'elo_before' => $eloBefore,
                'elo_after' => $eloAfter,
                'elo_change' => $eloChange,
            ]);

            // Mettre à jour l'utilisateur
            $player->update([
                'elo' => $eloAfter,
                'games_played' => $player->games_played + 1,
            ]);
        }
    }
}
