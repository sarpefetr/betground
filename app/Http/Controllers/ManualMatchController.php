<?php

namespace App\Http\Controllers;

use App\Models\ManualMatch;
use Illuminate\Http\Request;

class ManualMatchController extends Controller
{
    /**
     * Get all markets for a manual match
     */
    public function getMarkets(ManualMatch $manualMatch)
    {
        $odds = $manualMatch->odds ?? ManualMatch::getDefaultOdds();
        
        $markets = [
            'match_result' => [
                'name' => 'Maç Sonucu',
                'selections' => [
                    ['selection' => 'home', 'name' => 'Ev Sahibi', 'odds' => $odds['match_result']['home'] ?? 2.00],
                    ['selection' => 'draw', 'name' => 'Beraberlik', 'odds' => $odds['match_result']['draw'] ?? 3.20],
                    ['selection' => 'away', 'name' => 'Deplasman', 'odds' => $odds['match_result']['away'] ?? 3.50],
                ]
            ],
            'over_under_2_5' => [
                'name' => 'Alt/Üst 2.5 Gol',
                'selections' => [
                    ['selection' => 'over', 'name' => 'Üst 2.5', 'odds' => $odds['over_under_2_5']['over'] ?? 1.85],
                    ['selection' => 'under', 'name' => 'Alt 2.5', 'odds' => $odds['over_under_2_5']['under'] ?? 1.95],
                ]
            ],
            'both_teams_score' => [
                'name' => 'Karşılıklı Gol',
                'selections' => [
                    ['selection' => 'yes', 'name' => 'Var', 'odds' => $odds['both_teams_score']['yes'] ?? 1.75],
                    ['selection' => 'no', 'name' => 'Yok', 'odds' => $odds['both_teams_score']['no'] ?? 2.05],
                ]
            ],
            'double_chance' => [
                'name' => 'Çifte Şans',
                'selections' => [
                    ['selection' => '1X', 'name' => 'Ev Sahibi veya Beraberlik', 'odds' => $odds['double_chance']['1X'] ?? 1.30],
                    ['selection' => '12', 'name' => 'Ev Sahibi veya Deplasman', 'odds' => $odds['double_chance']['12'] ?? 1.25],
                    ['selection' => 'X2', 'name' => 'Beraberlik veya Deplasman', 'odds' => $odds['double_chance']['X2'] ?? 1.45],
                ]
            ],
            'first_half_result' => [
                'name' => 'İlk Yarı Sonucu',
                'selections' => [
                    ['selection' => 'home', 'name' => 'Ev Sahibi', 'odds' => $odds['first_half_result']['home'] ?? 2.80],
                    ['selection' => 'draw', 'name' => 'Beraberlik', 'odds' => $odds['first_half_result']['draw'] ?? 2.20],
                    ['selection' => 'away', 'name' => 'Deplasman', 'odds' => $odds['first_half_result']['away'] ?? 4.00],
                ]
            ],
            'total_goals' => [
                'name' => 'Toplam Gol',
                'selections' => [
                    ['selection' => '0-1', 'name' => '0-1 Gol', 'odds' => $odds['total_goals']['0-1'] ?? 3.50],
                    ['selection' => '2-3', 'name' => '2-3 Gol', 'odds' => $odds['total_goals']['2-3'] ?? 2.10],
                    ['selection' => '4+', 'name' => '4+ Gol', 'odds' => $odds['total_goals']['4+'] ?? 3.00],
                ]
            ],
            'handicap' => [
                'name' => 'Handikap',
                'selections' => [
                    ['selection' => 'home_-1', 'name' => 'Ev Sahibi -1', 'odds' => $odds['handicap']['home_-1'] ?? 3.00],
                    ['selection' => 'home_+1', 'name' => 'Ev Sahibi +1', 'odds' => $odds['handicap']['home_+1'] ?? 1.40],
                    ['selection' => 'away_-1', 'name' => 'Deplasman -1', 'odds' => $odds['handicap']['away_-1'] ?? 4.50],
                    ['selection' => 'away_+1', 'name' => 'Deplasman +1', 'odds' => $odds['handicap']['away_+1'] ?? 1.20],
                ]
            ],
            'over_under_1_5' => [
                'name' => 'Alt/Üst 1.5 Gol',
                'selections' => [
                    ['selection' => 'over', 'name' => 'Üst 1.5', 'odds' => 1.30],
                    ['selection' => 'under', 'name' => 'Alt 1.5', 'odds' => 3.50],
                ]
            ],
            'over_under_3_5' => [
                'name' => 'Alt/Üst 3.5 Gol',
                'selections' => [
                    ['selection' => 'over', 'name' => 'Üst 3.5', 'odds' => 2.60],
                    ['selection' => 'under', 'name' => 'Alt 3.5', 'odds' => 1.45],
                ]
            ],
            'exact_goals' => [
                'name' => 'Tam Gol Sayısı',
                'selections' => [
                    ['selection' => '0', 'name' => '0 Gol', 'odds' => 9.00],
                    ['selection' => '1', 'name' => '1 Gol', 'odds' => 5.50],
                    ['selection' => '2', 'name' => '2 Gol', 'odds' => 3.80],
                    ['selection' => '3', 'name' => '3 Gol', 'odds' => 4.50],
                    ['selection' => '4', 'name' => '4 Gol', 'odds' => 6.00],
                    ['selection' => '5+', 'name' => '5+ Gol', 'odds' => 8.00],
                ]
            ],
            'first_team_to_score' => [
                'name' => 'İlk Golü Atan',
                'selections' => [
                    ['selection' => 'home', 'name' => 'Ev Sahibi', 'odds' => 1.85],
                    ['selection' => 'away', 'name' => 'Deplasman', 'odds' => 2.20],
                    ['selection' => 'none', 'name' => 'Gol Yok', 'odds' => 9.00],
                ]
            ],
            'correct_score' => [
                'name' => 'Tam Skor',
                'selections' => [
                    ['selection' => '1-0', 'name' => '1-0', 'odds' => 7.50],
                    ['selection' => '2-0', 'name' => '2-0', 'odds' => 11.00],
                    ['selection' => '2-1', 'name' => '2-1', 'odds' => 8.00],
                    ['selection' => '0-0', 'name' => '0-0', 'odds' => 9.00],
                    ['selection' => '1-1', 'name' => '1-1', 'odds' => 6.50],
                    ['selection' => '2-2', 'name' => '2-2', 'odds' => 14.00],
                    ['selection' => '0-1', 'name' => '0-1', 'odds' => 9.00],
                    ['selection' => '0-2', 'name' => '0-2', 'odds' => 16.00],
                    ['selection' => '1-2', 'name' => '1-2', 'odds' => 9.50],
                    ['selection' => 'other', 'name' => 'Diğer', 'odds' => 4.00],
                ]
            ]
        ];
        
        return response()->json([
            'success' => true,
            'markets' => $markets
        ]);
    }
    
    /**
     * Get live manual matches
     */
    public function getLiveMatches()
    {
        $liveMatches = ManualMatch::where('is_live', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($match) {
                return [
                    'id' => 'manual-' . $match->id,
                    'home_team' => $match->home_team,
                    'away_team' => $match->away_team,
                    'league' => $match->league,
                    'home_score' => $match->home_score,
                    'away_score' => $match->away_score,
                    'minute' => $match->getCurrentMinuteAttribute(),
                    'odds' => [
                        'home' => $match->odds['match_result']['home'] ?? 2.00,
                        'draw' => $match->odds['match_result']['draw'] ?? 3.20,
                        'away' => $match->odds['match_result']['away'] ?? 3.50,
                    ],
                    'bestOdds' => [
                        'home' => $match->odds['match_result']['home'] ?? 2.00,
                        'draw' => $match->odds['match_result']['draw'] ?? 3.20,
                        'away' => $match->odds['match_result']['away'] ?? 3.50,
                    ]
                ];
            });
        
        return response()->json([
            'success' => true,
            'matches' => $liveMatches
        ]);
    }
}