<?php

namespace App\Http\Controllers;

use App\Services\SportsApiService;
use Illuminate\Http\Request;

class SportsBettingController extends Controller
{
    protected $sportsApi;

    public function __construct(SportsApiService $sportsApi)
    {
        $this->sportsApi = $sportsApi;
    }

    /**
     * Show sports betting page
     */
    public function index()
    {
        $liveMatches = $this->sportsApi->getLiveFootballMatches();
        $upcomingMatches = $this->sportsApi->getUpcomingFootballMatches();
        
        // Manuel maçları al
        $manualMatches = \App\Models\ManualMatch::where('status', '!=', 'finished')
            ->orderBy('is_live', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('games.sports-betting-table', compact('liveMatches', 'upcomingMatches', 'manualMatches'));
    }

    /**
     * Get live matches via AJAX
     */
    public function getLiveMatches()
    {
        $matches = $this->sportsApi->getLiveFootballMatches();
        return response()->json(['matches' => $matches]);
    }

    /**
     * Get upcoming matches via AJAX
     */
    public function getUpcomingMatches()
    {
        $matches = $this->sportsApi->getUpcomingFootballMatches();
        return response()->json(['matches' => $matches]);
    }

    /**
     * Get match details
     */
    public function getMatchDetails($matchId)
    {
        $match = $this->sportsApi->getMatchDetails($matchId);
        
        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        return response()->json(['match' => $match]);
    }

    /**
     * Refresh odds for a specific match
     */
    public function refreshOdds($matchId)
    {
        // Clear cache to get fresh odds
        cache()->forget('live_football_matches');
        cache()->forget('upcoming_football_matches');
        
        $match = $this->sportsApi->getMatchDetails($matchId);
        
        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        return response()->json([
            'success' => true,
            'match' => $match
        ]);
    }
    
    /**
     * Get all markets for a match
     */
    public function getMatchAllMarkets($matchId)
    {
        $markets = $this->sportsApi->getMatchAllMarkets($matchId);
        
        if (!$markets) {
            return response()->json(['error' => 'Markets not found'], 404);
        }
        
        return response()->json([
            'success' => true,
            'markets' => $markets
        ]);
    }
}
