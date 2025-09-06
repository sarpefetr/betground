<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualMatch;
use Illuminate\Http\Request;

class ManualMatchController extends Controller
{
    /**
     * Maçları listele
     */
    public function index()
    {
        $matches = ManualMatch::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.manual-matches.index', compact('matches'));
    }
    
    /**
     * Yeni maç ekleme formu
     */
    public function create()
    {
        $defaultOdds = ManualMatch::getDefaultOdds();
        return view('admin.manual-matches.create', compact('defaultOdds'));
    }
    
    /**
     * Yeni maç kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'league' => 'nullable|string|max:255',
            'match_time' => 'required|date_format:H:i',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'is_live' => 'boolean',
            'odds' => 'array'
        ]);
        
        // start_minute'ı 0 olarak ayarla
        $validated['start_minute'] = 0;
        $validated['current_minute'] = 0;
        
        $match = ManualMatch::create($validated);
        
        // Eğer canlı olarak işaretlendiyse maçı başlat
        if ($request->boolean('is_live')) {
            $match->startMatch();
        }
        
        return redirect()->route('admin.manual-matches.index')
            ->with('success', 'Maç başarıyla eklendi!');
    }
    
    /**
     * Maç düzenleme formu
     */
    public function edit(ManualMatch $manualMatch)
    {
        return view('admin.manual-matches.edit', compact('manualMatch'));
    }
    
    /**
     * Maç güncelle
     */
    public function update(Request $request, ManualMatch $manualMatch)
    {
        $validated = $request->validate([
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'league' => 'nullable|string|max:255',
            'match_time' => 'required|date_format:H:i',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'is_live' => 'boolean',
            'odds' => 'array'
        ]);
        
        $manualMatch->update($validated);
        
        // Canlı durumu değiştiyse
        if ($request->boolean('is_live') && !$manualMatch->started_at) {
            $manualMatch->startMatch();
        } elseif (!$request->boolean('is_live')) {
            $manualMatch->update([
                'is_live' => false,
                'status' => 'finished'
            ]);
        }
        
        return redirect()->route('admin.manual-matches.index')
            ->with('success', 'Maç başarıyla güncellendi!');
    }
    
    /**
     * Maç sil
     */
    public function destroy(ManualMatch $manualMatch)
    {
        $manualMatch->delete();
        
        return redirect()->route('admin.manual-matches.index')
            ->with('success', 'Maç başarıyla silindi!');
    }
    
    /**
     * Maçı başlat/durdur (AJAX)
     */
    public function toggleLive(ManualMatch $manualMatch)
    {
        if ($manualMatch->is_live) {
            $manualMatch->update([
                'is_live' => false,
                'status' => 'finished'
            ]);
        } else {
            $manualMatch->startMatch();
        }
        
        return response()->json([
            'success' => true,
            'is_live' => $manualMatch->is_live,
            'current_minute' => $manualMatch->getCurrentMinuteAttribute()
        ]);
    }
    
    /**
     * Skor güncelle (AJAX)
     */
    public function updateScore(Request $request, ManualMatch $manualMatch)
    {
        $validated = $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0'
        ]);
        
        $manualMatch->update($validated);
        
        return response()->json([
            'success' => true,
            'home_score' => $manualMatch->home_score,
            'away_score' => $manualMatch->away_score
        ]);
    }
    
    /**
     * Oranları güncelle (AJAX)
     */
    public function updateOdds(Request $request, ManualMatch $manualMatch)
    {
        $validated = $request->validate([
            'odds' => 'required|array'
        ]);
        
        $manualMatch->update([
            'odds' => $validated['odds']
        ]);
        
        return response()->json([
            'success' => true,
            'odds' => $manualMatch->odds
        ]);
    }
    
    /**
     * Gol ekle (AJAX)
     */
    public function addGoal(Request $request, ManualMatch $manualMatch)
    {
        $validated = $request->validate([
            'team' => 'required|in:home,away',
            'scorer' => 'nullable|string',
            'minute' => 'nullable|integer|min:0|max:120',
            'is_penalty' => 'boolean',
            'is_own_goal' => 'boolean'
        ]);
        
        $manualMatch->addGoal(
            $validated['team'],
            $validated['scorer'] ?? null,
            $validated['minute'] ?? null,
            $validated['is_penalty'] ?? false,
            $validated['is_own_goal'] ?? false
        );
        
        return response()->json([
            'success' => true,
            'home_score' => $manualMatch->fresh()->home_score,
            'away_score' => $manualMatch->fresh()->away_score,
            'goals' => $manualMatch->goals
        ]);
    }
    
    /**
     * Maçı bitir (AJAX)
     */
    public function finishMatch(ManualMatch $manualMatch)
    {
        $manualMatch->finishMatch();
        
        return response()->json([
            'success' => true,
            'status' => $manualMatch->status
        ]);
    }
    
    /**
     * Maç detayları (modal için)
     */
    public function show(ManualMatch $manualMatch)
    {
        $manualMatch->load('goals');
        
        return view('admin.manual-matches.show', compact('manualMatch'));
    }
}