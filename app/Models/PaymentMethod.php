<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
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
        'type',
        'parent_id',
        'method_code',
        'image',
        'min_amount',
        'max_amount',
        'commission_rate',
        'processing_time',
        'bank_details',
        'form_fields',
        'instructions',
        'is_active',
        'is_featured',
        'order_index',
        'supported_currencies',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'bank_details' => 'array',
        'form_fields' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'supported_currencies' => 'array',
    ];

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }
        
        // Fallback image based on method type
        return match($this->method_code ?? $this->name) {
            'bank_transfer' => 'https://via.placeholder.com/300x150/10b981/ffffff?text=🏦+Banka+Transferi',
            'credit_card' => 'https://via.placeholder.com/300x150/3b82f6/ffffff?text=💳+Kredi+Kartı',
            'crypto' => 'https://via.placeholder.com/300x150/f59e0b/000000?text=₿+Kripto+Para',
            'ewallet' => 'https://via.placeholder.com/300x150/8b5cf6/ffffff?text=💰+E-Cüzdan',
            'mobile' => 'https://via.placeholder.com/300x150/ef4444/ffffff?text=📱+Mobil+Ödeme',
            default => 'https://via.placeholder.com/300x150/6b7280/ffffff?text=💳+' . urlencode($this->name),
        };
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(PaymentMethod::class, 'parent_id');
    }

    /**
     * Get the child methods.
     */
    public function children()
    {
        return $this->hasMany(PaymentMethod::class, 'parent_id')->orderBy('order_index');
    }

    /**
     * Get active child methods.
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Check if this is a category.
     */
    public function isCategory()
    {
        return $this->type === 'category';
    }

    /**
     * Check if this is a payment method.
     */
    public function isMethod()
    {
        return $this->type === 'method';
    }

    /**
     * Get all categories.
     */
    public static function getCategories()
    {
        return self::where('type', 'category')
                   ->where('is_active', true)
                   ->orderBy('order_index')
                   ->get();
    }

    /**
     * Get all active payment methods.
     */
    public static function getActiveMethods()
    {
        return self::where('type', 'method')
                   ->where('is_active', true)
                   ->orderBy('order_index')
                   ->get();
    }

    /**
     * Get formatted amount range.
     */
    public function getAmountRangeAttribute()
    {
        return "₺" . number_format($this->min_amount, 2) . " - ₺" . number_format($this->max_amount, 2);
    }

    /**
     * Get commission display.
     */
    public function getCommissionDisplayAttribute()
    {
        return $this->commission_rate > 0 ? "%{$this->commission_rate}" : "Komisyonsuz";
    }

    /**
     * Check if method supports currency.
     */
    public function supportsCurrency($currency)
    {
        if (!$this->supported_currencies) {
            return true; // If no restriction, supports all
        }
        
        return in_array($currency, $this->supported_currencies);
    }

    /**
     * Get deposits using this method.
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'method', 'method_code');
    }
}