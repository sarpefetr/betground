<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Bonus extends Model
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
        'description',
        'bonus_type',
        'amount_type',
        'amount_value',
        'min_deposit',
        'max_bonus',
        'wagering_requirement',
        'valid_from',
        'valid_until',
        'image',
        'terms_conditions',
        'is_active',
        'is_featured',
        'usage_limit',
        'user_limit',
        'order_index',
        'countries',
        'currencies',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount_value' => 'decimal:2',
        'min_deposit' => 'decimal:2',
        'max_bonus' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'countries' => 'array',
        'currencies' => 'array',
    ];

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }
        
        // Fallback image based on bonus type
        return match($this->bonus_type) {
            'welcome' => 'https://via.placeholder.com/400x200/ffd700/000000?text=🎉+Hoş+Geldin+Bonusu',
            'daily' => 'https://via.placeholder.com/400x200/10b981/ffffff?text=📅+Günlük+Bonus',
            'weekly' => 'https://via.placeholder.com/400x200/8b5cf6/ffffff?text=🎉+Haftalık+Bonus',
            'cashback' => 'https://via.placeholder.com/400x200/f59e0b/000000?text=💰+Cashback',
            'referral' => 'https://via.placeholder.com/400x200/06b6d4/ffffff?text=🤝+Referans+Bonus',
            'vip' => 'https://via.placeholder.com/400x200/dc2626/ffffff?text=👑+VIP+Bonus',
            'tournament' => 'https://via.placeholder.com/400x200/7c3aed/ffffff?text=🏆+Turnuva+Bonus',
            default => 'https://via.placeholder.com/400x200/6b7280/ffffff?text=🎁+Özel+Bonus',
        };
    }

    /**
     * Get the bonus type display name.
     */
    public function getBonusTypeDisplayAttribute()
    {
        return match($this->bonus_type) {
            'welcome' => '🎉 Hoş Geldin Bonusu',
            'daily' => '📅 Günlük Bonus',
            'weekly' => '🎉 Haftalık Bonus',
            'cashback' => '💰 Cashback',
            'referral' => '🤝 Referans Bonusu',
            'vip' => '👑 VIP Bonus',
            'tournament' => '🏆 Turnuva Bonusu',
            'special' => '🎁 Özel Bonus',
            default => ucfirst($this->bonus_type),
        };
    }

    /**
     * Get formatted bonus amount.
     */
    public function getFormattedAmountAttribute()
    {
        if ($this->amount_type === 'percentage') {
            return '%' . number_format($this->amount_value, 0);
        }
        
        return '₺' . number_format($this->amount_value, 2);
    }

    /**
     * Check if bonus is currently valid.
     */
    public function isValid()
    {
        $now = now();
        
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }
        
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if user can claim this bonus.
     */
    public function canUserClaim($user)
    {
        if (!$this->isValid()) {
            return false;
        }
        
        // Check country restriction
        if ($this->countries && !in_array($user->country, $this->countries)) {
            return false;
        }
        
        // Check currency restriction
        if ($this->currencies && !in_array($user->currency, $this->currencies)) {
            return false;
        }
        
        // TODO: Check user claim history when user_bonuses table is created
        
        return true;
    }

    /**
     * Get active bonuses for frontend.
     */
    public static function getActiveBonuses()
    {
        return self::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('order_index')
            ->get();
    }

    /**
     * Get featured bonuses.
     */
    public static function getFeaturedBonuses()
    {
        return self::getActiveBonuses()->where('is_featured', true);
    }

    /**
     * Get the bonus claims for this bonus.
     */
    public function claims()
    {
        return $this->hasMany(UserBonusClaim::class);
    }

    /**
     * Check if user has already claimed this bonus.
     */
    public function hasUserClaimed($userId)
    {
        return $this->claims()->where('user_id', $userId)->exists();
    }

    /**
     * Get pending claims count.
     */
    public function getPendingClaimsCountAttribute()
    {
        return $this->claims()->where('status', 'pending')->count();
    }

    /**
     * Get total claims count.
     */
    public function getTotalClaimsCountAttribute()
    {
        return $this->claims()->count();
    }
}