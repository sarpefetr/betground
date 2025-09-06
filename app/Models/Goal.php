<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'manual_match_id',
        'team',
        'scorer',
        'minute',
        'is_penalty',
        'is_own_goal'
    ];
    
    protected $casts = [
        'minute' => 'integer',
        'is_penalty' => 'boolean',
        'is_own_goal' => 'boolean'
    ];
    
    public function match()
    {
        return $this->belongsTo(ManualMatch::class, 'manual_match_id');
    }
}