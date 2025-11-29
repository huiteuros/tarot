<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamePlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'role',
        'score',
        'elo_before',
        'elo_after',
        'elo_change',
    ];

    /**
     * La partie associée
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Le joueur associé
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
