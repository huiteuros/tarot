<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'played_at',
        'nombre_joueurs',
        'contract_type',
        'contract_success',
        'points',
        'oudlers',
        'bonus_points',
        'petit_au_bout',
        'petit_au_bout_team',
        'poignee',
        'poignee_team',
        'chelem',
    ];

    protected $casts = [
        'played_at' => 'datetime',
        'contract_success' => 'boolean',
        'petit_au_bout' => 'boolean',
    ];

    /**
     * Les joueurs de cette partie
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_players')
            ->withPivot(['role', 'score', 'elo_before', 'elo_after', 'elo_change'])
            ->withTimestamps();
    }

    /**
     * Les entrées de game_players pour cette partie
     */
    public function gamePlayers(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    /**
     * Le preneur de la partie
     */
    public function preneur()
    {
        return $this->players()->wherePivot('role', 'preneur')->first();
    }

    /**
     * Les attaquants (incluant le preneur si appelé)
     */
    public function attaquants()
    {
        return $this->players()->wherePivot('role', 'attaquant');
    }

    /**
     * Les défenseurs
     */
    public function defenseurs()
    {
        return $this->players()->wherePivot('role', 'defenseur');
    }

    /**
     * Les misères annoncées pour cette partie
     */
    public function miseres(): HasMany
    {
        return $this->hasMany(GameMisere::class);
    }

    /**
     * Obtenir le nom formaté du contrat
     */
    public function getContractTypeName(): string
    {
        return match($this->contract_type) {
            'petite' => 'Petite',
            'garde' => 'Garde',
            'garde_sans' => 'Garde Sans',
            'garde_contre' => 'Garde Contre',
            default => ucfirst($this->contract_type),
        };
    }

    /**
     * Vérifier si le contrat a été réussi
     */
    public function isSuccessful(): bool
    {
        return $this->contract_success;
    }

    /**
     * Obtenir tous les bonus de la partie sous forme de tableau
     */
    public function getBonusArray(): array
    {
        return [
            'petit_au_bout' => $this->petit_au_bout,
            'petit_au_bout_team' => $this->petit_au_bout_team,
            'poignee' => $this->poignee,
            'poignee_team' => $this->poignee_team,
            'chelem' => $this->chelem,
        ];
    }

    /**
     * Scope pour les parties récentes
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('played_at', '>=', now()->subDays($days));
    }

    /**
     * Scope pour les parties d'un joueur
     */
    public function scopeForPlayer($query, int $userId)
    {
        return $query->whereHas('gamePlayers', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
