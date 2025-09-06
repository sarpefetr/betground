<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'transaction_id',
        'amount',
        'potential_win',
        'odds',
        'total_odds',
        'status',
        'type',
        'result',
        'bet_data',
        'bet_details',
        'settled_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'potential_win' => 'decimal:2',
        'odds' => 'decimal:2',
        'total_odds' => 'decimal:2',
        'result' => 'decimal:2',
        'bet_data' => 'array',
        'bet_details' => 'array',
        'settled_at' => 'datetime',
    ];

    /**
     * Get the user that owns the bet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the game that owns the bet.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the transaction that owns the bet.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}