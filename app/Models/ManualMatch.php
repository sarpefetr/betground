<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ManualMatch extends Model
{
    protected $fillable = [
        'home_team',
        'away_team',
        'league',
        'match_time',
        'home_score',
        'away_score',
        'current_minute',
        'start_minute',
        'started_at',
        'is_live',
        'status',
        'odds'
    ];
    
    protected $casts = [
        'odds' => 'array',
        'is_live' => 'boolean',
        'started_at' => 'datetime',
        'home_score' => 'integer',
        'away_score' => 'integer',
        'current_minute' => 'integer',
        'start_minute' => 'integer'
    ];
    
    // Mevcut dakikayı hesapla
    public function getCurrentMinuteAttribute()
    {
        if (!$this->is_live) {
            return 0;
        }
        
        // match_time'a göre hesapla
        if ($this->match_time) {
            $now = Carbon::now();
            $matchTime = Carbon::today()->setTimeFromTimeString($this->match_time);
            
            // Eğer maç saati henüz gelmemişse 0 döndür
            if ($now->lt($matchTime)) {
                return 0;
            }
            
            // Geçen dakikaları hesapla (absolute değer al)
            $elapsedMinutes = $matchTime->diffInMinutes($now);
            
            // 90 dakikayı geçmesin ve tam sayıya yuvarla
            return min(floor($elapsedMinutes), 90);
        }
        
        // Eğer match_time yoksa started_at'e göre hesapla (eski yöntem)
        if ($this->started_at) {
            $elapsedMinutes = Carbon::now()->diffInMinutes($this->started_at);
            return min($elapsedMinutes, 90);
        }
        
        return 0;
    }
    
    // Maç bitti mi kontrolü
    public function getIsFinishedAttribute()
    {
        return $this->getCurrentMinuteAttribute() >= 90;
    }
    
    // Dakikayı güncelle (Otomatik güncelleme için)
    public function updateMinute()
    {
        if ($this->is_live && $this->started_at) {
            $this->current_minute = $this->getCurrentMinuteAttribute();
            
            if ($this->current_minute >= 90) {
                $this->status = 'finished';
                $this->is_live = false;
            }
            
            $this->save();
        }
    }
    
    // Maçı başlat
    public function startMatch()
    {
        // Eğer match_time belirtilmemişse şu anki saati kullan
        if (!$this->match_time) {
            $this->match_time = Carbon::now()->format('H:i');
        }
        
        $this->started_at = Carbon::now();
        $this->is_live = true;
        $this->status = 'live';
        $this->save();
    }
    
    // Varsayılan odds yapısı
    public static function getDefaultOdds()
    {
        return [
            // Maç Sonucu
            'match_result' => [
                'home' => 2.00,
                'draw' => 3.20,
                'away' => 3.50
            ],
            // Alt/Üst 2.5
            'over_under_2_5' => [
                'over' => 1.85,
                'under' => 1.95
            ],
            // Karşılıklı Gol
            'both_teams_score' => [
                'yes' => 1.75,
                'no' => 2.05
            ],
            // Çifte Şans
            'double_chance' => [
                '1X' => 1.30,
                '12' => 1.25,
                'X2' => 1.45
            ],
            // İlk Yarı Sonucu
            'first_half_result' => [
                'home' => 2.80,
                'draw' => 2.20,
                'away' => 4.00
            ],
            // Toplam Gol
            'total_goals' => [
                '0-1' => 3.50,
                '2-3' => 2.10,
                '4+' => 3.00
            ],
            // Handikap
            'handicap' => [
                'home_-1' => 3.00,
                'home_+1' => 1.40,
                'away_-1' => 4.50,
                'away_+1' => 1.20
            ]
        ];
    }
    
    // Goller ilişkisi
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
    
    // Maçı bitir
    public function finishMatch()
    {
        $this->update([
            'is_live' => false,
            'status' => 'finished',
            'current_minute' => 90
        ]);
        
        // Bu maça yapılan bahisleri değerlendir
        $this->evaluateBets();
    }
    
    // Bahisleri değerlendir
    protected function evaluateBets()
    {
        // Bu maça ait pending bahisleri bul
        $bets = \App\Models\Bet::where('status', 'pending')
            ->where('bet_details', 'like', '%"match_id":"manual-' . $this->id . '"%')
            ->get();
        
        foreach ($bets as $bet) {
            $allWon = true;
            $betDetails = $bet->bet_details;
            
            // Her bir bahis seçimini kontrol et
            foreach ($betDetails as $detail) {
                if (isset($detail['match_id']) && $detail['match_id'] == 'manual-' . $this->id) {
                    // Maç sonucu kontrolü
                    if ($detail['market_type'] == 'match_result') {
                        $won = $this->checkMatchResult($detail['selection']);
                        if (!$won) {
                            $allWon = false;
                            break;
                        }
                    }
                    // Diğer market tipleri için kontroller eklenebilir
                }
            }
            
            // Eğer tüm seçimler kazandıysa
            if ($allWon) {
                $bet->update(['status' => 'won']);
                
                // Kazancı öde
                $user = $bet->user;
                if ($user && $user->wallet) {
                    $winAmount = $bet->potential_win;
                    $user->wallet->increment('balance', $winAmount);
                    
                    // Transaction kaydı oluştur
                    $user->wallet->transactions()->create([
                        'user_id' => $user->id,
                        'type' => 'win',
                        'amount' => $winAmount,
                        'balance_before' => $user->wallet->balance - $winAmount,
                        'balance_after' => $user->wallet->balance,
                        'currency' => 'TRY',
                        'description' => 'Bahis Kazancı #' . $bet->id,
                        'reference_type' => 'App\Models\Bet',
                        'reference_id' => $bet->id,
                        'status' => 'completed'
                    ]);
                }
            } else {
                // Kaybetti
                $bet->update(['status' => 'lost']);
            }
        }
    }
    
    // Maç sonucu kontrolü
    protected function checkMatchResult($selection)
    {
        $homeScore = $this->home_score;
        $awayScore = $this->away_score;
        
        switch ($selection) {
            case 'home':
            case '1':
                return $homeScore > $awayScore;
            case 'draw':
            case 'X':
                return $homeScore == $awayScore;
            case 'away':
            case '2':
                return $awayScore > $homeScore;
            default:
                return false;
        }
    }
    
    // Gol ekle
    public function addGoal($team, $scorer = null, $minute = null, $isPenalty = false, $isOwnGoal = false)
    {
        // Gol ekle
        $this->goals()->create([
            'team' => $team,
            'scorer' => $scorer,
            'minute' => $minute ?? $this->getCurrentMinuteAttribute(),
            'is_penalty' => $isPenalty,
            'is_own_goal' => $isOwnGoal
        ]);
        
        // Skoru güncelle
        if ($team === 'home') {
            $this->increment('home_score');
        } else {
            $this->increment('away_score');
        }
    }
}