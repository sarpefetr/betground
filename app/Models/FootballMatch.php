<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $table = 'matches';
    
    protected $fillable = [
        'api_id',
        'sport_key',
        'sport_title',
        'league_id',
        'home_team',
        'away_team',
        'commence_time',
        'is_live',
        'home_score',
        'away_score',
        'minute',
        'status',
        'odds_home',
        'odds_draw',
        'odds_away',
        'additional_markets'
    ];
    
    protected $casts = [
        'commence_time' => 'datetime',
        'is_live' => 'boolean',
        'additional_markets' => 'array',
        'odds_home' => 'decimal:2',
        'odds_draw' => 'decimal:2',
        'odds_away' => 'decimal:2',
    ];
    
    // Canlı maçları getir
    public function scopeLive($query)
    {
        return $query->where('is_live', true);
    }
    
    // Yaklaşan maçları getir
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('commence_time', '>', now());
    }
    
    // Tamamlanan maçları getir
    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }
}
