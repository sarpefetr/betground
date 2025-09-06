<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EsportsGame extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_name',
        'icon',
        'description',
        'is_active',
        'order_index'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(EsportsMatch::class);
    }
}
