<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EsportsMatch extends Model
{
    protected $fillable = [
        'esports_game_id',
        'tournament_id',
        'team1_id',
        'team2_id',
        'title',
        'format',
        'status',
        'start_time',
        'end_time',
        'team1_score',
        'team2_score',
        'odds',
        'maps_data',
        'is_featured'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'odds' => 'array',
        'maps_data' => 'array',
        'is_featured' => 'boolean'
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(EsportsGame::class, 'esports_game_id');
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo(EsportsTeam::class, 'team1_id');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(EsportsTeam::class, 'team2_id');
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(EsportsTournament::class, 'tournament_id');
    }
}
