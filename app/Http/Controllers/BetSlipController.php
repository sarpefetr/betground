<?php

namespace App\Http\Controllers;

use App\Models\BetSlip;
use App\Models\Bet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BetSlipController extends Controller
{
    /**
     * Kupona bahis ekle
     */
    public function addToBetSlip(Request $request)
    {
        $validated = $request->validate([
            'match_id' => 'required|string',
            'event_name' => 'required|string',
            'market_type' => 'required|string',
            'selection' => 'required|string',
            'selection_name' => 'required|string',
            'odds' => 'required|numeric|min:1.01'
        ]);
        
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        
        // Aynı maç ve market için önceki seçimi sil
        BetSlip::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
        ->where('match_id', $validated['match_id'])
        ->where('market_type', $validated['market_type'])
        ->where('status', 'pending')
        ->delete();
        
        // Yeni seçimi ekle
        $betSlip = BetSlip::create([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'match_id' => $validated['match_id'],
            'event_name' => $validated['event_name'],
            'market_type' => $validated['market_type'],
            'selection' => $validated['selection'],
            'selection_name' => $validated['selection_name'],
            'odds' => $validated['odds'],
            'status' => 'pending'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Bahis kupona eklendi',
            'betSlip' => $this->getBetSlipData($request)
        ]);
    }
    
    /**
     * Kupondan bahis çıkar
     */
    public function removeFromBetSlip(Request $request, $id)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        
        $betSlip = BetSlip::where('id', $id)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('status', 'pending')
            ->first();
            
        if ($betSlip) {
            $betSlip->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Bahis kupondan çıkarıldı',
                'betSlip' => $this->getBetSlipData($request)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Bahis bulunamadı'
        ], 404);
    }
    
    /**
     * Kuponu temizle
     */
    public function clearBetSlip(Request $request)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        
        BetSlip::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
        ->where('status', 'pending')
        ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Kupon temizlendi',
            'betSlip' => []
        ]);
    }
    
    /**
     * Kupon verilerini getir
     */
    public function getBetSlip(Request $request)
    {
        // Sadece AJAX istekleri için JSON döndür
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect('/');
        }
        
        return response()->json([
            'success' => true,
            'betSlip' => $this->getBetSlipData($request)
        ]);
    }
    
    /**
     * Bahis yap
     */
    public function placeBet(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bahis yapmak için giriş yapmalısınız'
            ], 401);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:10000'
        ]);
        
        $user = Auth::user();
        $betSlipData = $this->getBetSlipData($request);
        
        if (empty($betSlipData['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Kuponunuz boş'
            ], 400);
        }
        
        // Bakiye kontrolü
        if ($user->wallet->balance < $validated['amount']) {
            return response()->json([
                'success' => false,
                'message' => 'Yetersiz bakiye'
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            // Bahis oluştur
            $bet = Bet::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'odds' => $betSlipData['totalOdds'], // Single odds için
                'total_odds' => $betSlipData['totalOdds'],
                'potential_win' => $validated['amount'] * $betSlipData['totalOdds'],
                'type' => count($betSlipData['items']) > 1 ? 'multiple' : 'single',
                'status' => 'pending',
                'bet_details' => $betSlipData['items']->toArray()
            ]);
            
            // Bakiyeden düş
            $user->wallet->decrement('balance', $validated['amount']);
            
            // Transaction oluştur
            $user->wallet->transactions()->create([
                'user_id' => $user->id,
                'type' => 'bet',
                'amount' => -$validated['amount'],
                'balance_before' => $user->wallet->balance + $validated['amount'],
                'balance_after' => $user->wallet->balance,
                'currency' => 'TRY',
                'description' => 'Bahis #' . $bet->id,
                'reference_type' => 'App\Models\Bet',
                'reference_id' => $bet->id,
                'status' => 'completed'
            ]);
            
            // Kupondaki bahisleri temizle
            BetSlip::where('user_id', $user->id)
                ->where('status', 'pending')
                ->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Bahisiniz başarıyla alındı',
                'bet' => $bet,
                'newBalance' => $user->wallet->fresh()->balance
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Bahis alınırken bir hata oluştu'
            ], 500);
        }
    }
    
    /**
     * Kupon verilerini hazırla
     */
    private function getBetSlipData(Request $request)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        
        $items = BetSlip::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
        ->where('status', 'pending')
        ->get();
        
        $totalOdds = 1;
        foreach ($items as $item) {
            $totalOdds *= $item->odds;
        }
        
        return [
            'items' => $items,
            'totalOdds' => round($totalOdds, 2),
            'count' => $items->count()
        ];
    }
}