<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBonusClaim extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bonus_id',
        'status',
        'claimed_amount',
        'awarded_amount',
        'user_message',
        'admin_message',
        'processed_by',
        'processed_at',
        'bonus_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'claimed_amount' => 'decimal:2',
        'awarded_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'bonus_data' => 'array',
    ];

    /**
     * Get the user that owns the claim.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bonus that is claimed.
     */
    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    /**
     * Get the admin who processed the claim.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get status display name.
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'pending' => '⏳ Bekliyor',
            'approved' => '✅ Onaylandı',
            'rejected' => '❌ Reddedildi',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status color class.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-500 bg-opacity-20 text-yellow-400',
            'approved' => 'bg-green-500 bg-opacity-20 text-green-400',
            'rejected' => 'bg-red-500 bg-opacity-20 text-red-400',
            default => 'bg-gray-500 bg-opacity-20 text-gray-400',
        };
    }

    /**
     * Check if claim can be processed.
     */
    public function canBeProcessed()
    {
        return $this->status === 'pending';
    }

    /**
     * Approve the bonus claim.
     */
    public function approve($adminId, $awardedAmount = null, $adminMessage = null)
    {
        $this->update([
            'status' => 'approved',
            'awarded_amount' => $awardedAmount ?? $this->claimed_amount,
            'admin_message' => $adminMessage,
            'processed_by' => $adminId,
            'processed_at' => now(),
        ]);

        // Add bonus to user wallet
        $user = $this->user;
        $wallet = $user->wallet;
        
        if ($wallet) {
            $oldBalance = $wallet->bonus_balance;
            $newBalance = $oldBalance + ($awardedAmount ?? $this->claimed_amount);
            
            $wallet->update(['bonus_balance' => $newBalance]);

            // Create transaction record
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'bonus',
                'amount' => $awardedAmount ?? $this->claimed_amount,
                'balance_before' => $oldBalance,
                'balance_after' => $newBalance,
                'currency' => $wallet->currency,
                'description' => "Bonus onaylandı: {$this->bonus->name}",
                'reference_type' => 'bonus_claim',
                'reference_id' => $this->id,
                'status' => 'completed',
            ]);
        }

        return $this;
    }

    /**
     * Reject the bonus claim.
     */
    public function reject($adminId, $adminMessage = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_message' => $adminMessage,
            'processed_by' => $adminId,
            'processed_at' => now(),
        ]);

        return $this;
    }
}