<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'type',
        'provider',
        'thumbnail',
        'rtp',
        'min_bet',
        'max_bet',
        'is_live',
        'is_featured',
        'is_active',
        'order_index',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rtp' => 'decimal:2',
        'min_bet' => 'decimal:2',
        'max_bet' => 'decimal:2',
        'is_live' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the bets for the game.
     */
    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    /**
     * Get the full thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset($this->thumbnail);
        }
        
        // Fallback thumbnail based on category
        return match($this->category) {
            'slots' => 'https://via.placeholder.com/300x200/6366f1/ffffff?text=🎰+' . urlencode($this->name),
            'casino' => 'https://via.placeholder.com/300x200/10b981/ffffff?text=🃏+' . urlencode($this->name),
            'sports' => 'https://via.placeholder.com/300x200/ef4444/ffffff?text=⚽+' . urlencode($this->name),
            'esports' => 'https://via.placeholder.com/300x200/8b5cf6/ffffff?text=🎮+' . urlencode($this->name),
            'virtual' => 'https://via.placeholder.com/300x200/6b7280/ffffff?text=🤖+' . urlencode($this->name),
            default => 'https://via.placeholder.com/300x200/374151/ffffff?text=🎯+' . urlencode($this->name),
        };
    }

    /**
     * Get category display name with icon.
     */
    public function getCategoryDisplayAttribute()
    {
        return match($this->category) {
            'slots' => '🎰 Slot Oyunları',
            'casino' => '🃏 Canlı Casino',
            'sports' => '⚽ Spor Bahisleri',
            'esports' => '🎮 E-Sporlar',
            'virtual' => '🤖 Sanal Sporlar',
            default => '🎯 ' . ucfirst($this->category),
        };
    }
}