<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'elo',
        'games_played',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Les parties jouées par cet utilisateur
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_players')
            ->withPivot(['role', 'score', 'elo_before', 'elo_after', 'elo_change'])
            ->withTimestamps()
            ->orderBy('played_at', 'desc');
    }

    /**
     * Les entrées de game_players pour cet utilisateur
     */
    public function gamePlayers(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    /**
     * Obtenir le rang ELO du joueur
     */
    public function getEloRank(): int
    {
        return static::where('games_played', '>', 0)
            ->where('elo', '>', $this->elo)
            ->count() + 1;
    }

    /**
     * Obtenir le taux de victoire du joueur
     */
    public function getWinRate(): float
    {
        if ($this->games_played === 0) {
            return 0.0;
        }

        $wins = $this->gamePlayers()
            ->join('games', 'game_players.game_id', '=', 'games.id')
            ->where(function($query) {
                $query->where(function($q) {
                    // Victoire en tant que preneur ou attaquant
                    $q->whereIn('game_players.role', ['preneur', 'attaquant'])
                      ->where('games.contract_success', true);
                })->orWhere(function($q) {
                    // Victoire en tant que défenseur
                    $q->where('game_players.role', 'defenseur')
                      ->where('games.contract_success', false);
                });
            })
            ->count();

        return round(($wins / $this->games_played) * 100, 1);
    }

    /**
     * Scope pour les joueurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('games_played', '>', 0);
    }

    /**
     * Scope pour le classement
     */
    public function scopeLeaderboard($query)
    {
        return $query->active()->orderBy('elo', 'desc');
    }
}
