<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetSlip extends Model
{
    protected $fillable = [
        'user_id',
        'match_id',
        'event_name',
        'market_type',
        'selection',
        'selection_name',
        'odds',
        'status',
        'session_id'
    ];
    
    protected $casts = [
        'odds' => 'decimal:2'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Aktif kupondaki bahisleri getir
    public function scopeActive($query)
    {
        return $query->where('status', 'pending');
    }
    
    // Kullanıcı veya session'a göre filtrele
    public function scopeForUser($query, $userId = null, $sessionId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        } elseif ($sessionId) {
            return $query->where('session_id', $sessionId);
        }
        
        return $query;
    }
}
