<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EsportsTeam extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_name',
        'logo',
        'country',
        'description',
        'social_links',
        'is_active'
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean'
    ];

    public function homeMatches(): HasMany
    {
        return $this->hasMany(EsportsMatch::class, 'team1_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(EsportsMatch::class, 'team2_id');
    }
}
