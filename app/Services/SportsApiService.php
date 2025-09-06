<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SportsApiService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('SPORTS_API_KEY', 'c2af01689c2b194053fb461e45f52841');
        $this->baseUrl = env('SPORTS_API_URL', 'https://api.sportsgameodds.com/v2');
    }

    /**
     * Get available sports
     */
    public function getSports()
    {
        return Cache::remember('sports_list', 300, function () {
            try {
                $response = Http::withHeaders([
                    'X-Api-Key' => $this->apiKey
                ])->get($this->baseUrl . '/sports/');

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('Sports API Error: ' . $response->body());
                return [];
            } catch (\Exception $e) {
                Log::error('Sports API Exception: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get all soccer leagues (API uses SOCCER not FOOTBALL)
     */
    public function getFootballLeagues()
    {
        return Cache::remember('soccer_leagues', 3600, function () {
            try {
                $response = Http::withHeaders([
                    'X-Api-Key' => $this->apiKey
                ])->get($this->baseUrl . '/leagues/', [
                    'sportID' => 'SOCCER'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? [];
                }

                Log::error('Leagues API Error: ' . $response->body());
                return [];
            } catch (\Exception $e) {
                Log::error('Leagues API Exception: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get football matches (live and upcoming)
     */
    public function getFootballMatches($live = false)
    {
        try {
            $allEvents = [];
            
            // Önce ligleri al
            $leagues = $this->getFootballLeagues();
            
            if (empty($leagues)) {
                Log::error('No leagues found');
                return [];
            }
            
            // İlk birkaç popüler lig için events çek
            $popularLeagues = ['EPL', 'BUNDESLIGA', 'LA_LIGA', 'SERIE_A', 'FR_LIGUE_1', 'CHAMPIONS_LEAGUE'];
            $processedLeagues = 0;
            
            foreach ($leagues as $league) {
                // Sadece popüler ligleri al
                if (!in_array($league['leagueID'], $popularLeagues) && $processedLeagues > 5) {
                    continue;
                }
                
                $nextCursor = null;
                $leagueEvents = [];
                
                do {
                    $params = [
                        'leagueID' => $league['leagueID'],
                        'marketOddsAvailable' => 'true',
                        'limit' => 20
                    ];
                    
                    if ($nextCursor) {
                        $params['cursor'] = $nextCursor;
                    }
                    
                    if ($live) {
                        $params['live'] = 'true';
                    }
                    
                    $response = Http::withHeaders([
                        'X-Api-Key' => $this->apiKey
                    ])->get($this->baseUrl . '/events', $params);
                    
                    if ($response->successful()) {
                        $data = $response->json();
                        
                        if (isset($data['data']) && is_array($data['data'])) {
                            $leagueEvents = array_merge($leagueEvents, $data['data']);
                        }
                        
                        $nextCursor = $data['nextCursor'] ?? null;
                    } else {
                        Log::error('Events API Error for league ' . $league['leagueID'] . ': ' . $response->body());
                        break;
                    }
                    
                } while ($nextCursor && count($leagueEvents) < 20); // Her lig için max 20 maç
                
                $allEvents = array_merge($allEvents, $leagueEvents);
                $processedLeagues++;
                
                // Yeterli maç bulduysak durdur
                if (count($allEvents) >= 50) {
                    break;
                }
            }
            
            Log::info("Total events found: " . count($allEvents));
            
            // Maçları formatla
            return $this->formatEvents($allEvents);
            
        } catch (\Exception $e) {
            Log::error('Football Matches API Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get live football matches
     */
    public function getLiveFootballMatches()
    {
        return $this->getFootballMatches(true);
    }

    /**
     * Get upcoming football matches
     */
    public function getUpcomingFootballMatches()
    {
        return $this->getFootballMatches(false);
    }

    /**
     * Get odds for a specific match
     */
    public function getMatchOdds($eventID)
    {
        try {
            $response = Http::withHeaders([
                'X-Api-Key' => $this->apiKey
            ])->get($this->baseUrl . '/odds', [
                'eventID' => $eventID,
                'limit' => 100
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? [];
            }

            Log::error('Odds API Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Odds API Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format events from API to our structure
     */
    private function formatEvents($events)
    {
        $formattedMatches = [];

        foreach ($events as $event) {
            // Takım bilgileri
            $homeTeamName = 'Home Team';
            $awayTeamName = 'Away Team';
            
            if (isset($event['teams']['home'])) {
                $homeTeamName = $event['teams']['home']['names']['long'] ?? 
                               $event['teams']['home']['names']['medium'] ?? 
                               $event['teams']['home']['names']['short'] ?? 
                               $event['teams']['home']['teamID'] ?? 'Home Team';
            }
            
            if (isset($event['teams']['away'])) {
                $awayTeamName = $event['teams']['away']['names']['long'] ?? 
                               $event['teams']['away']['names']['medium'] ?? 
                               $event['teams']['away']['names']['short'] ?? 
                               $event['teams']['away']['teamID'] ?? 'Away Team';
            }
            
            // Maç zamanı ve canlı durumu
            $startTime = null;
            if (isset($event['info']['startTime'])) {
                $startTime = $event['info']['startTime'];
            } elseif (isset($event['startTime'])) {
                $startTime = $event['startTime'];
            }
            
            $isLive = false;
            $currentScore = ['home' => 0, 'away' => 0];
            $minute = 0;
            
            // Live kontrolü
            if (isset($event['info']['live'])) {
                $isLive = $event['info']['live'];
            } elseif (isset($event['status'])) {
                $isLive = in_array($event['status'], ['live', 'in_progress', 'LIVE']);
                
                if (isset($event['status']['period'])) {
                    $minute = $this->calculateMinuteFromPeriod($event['status']['period']);
                }
                
                if (isset($event['status']['score'])) {
                    $currentScore = [
                        'home' => $event['status']['score']['home'] ?? 0,
                        'away' => $event['status']['score']['away'] ?? 0
                    ];
                }
            }
            
            // Lig bilgisi
            $leagueName = $this->getLeagueName($event['leagueID'] ?? '');
            
            $match = [
                'id' => $event['eventID'] ?? uniqid(),
                'sport_key' => 'soccer',
                'sport_title' => $leagueName,
                'league_id' => $event['leagueID'] ?? '',
                'home_team' => $homeTeamName,
                'away_team' => $awayTeamName,
                'commence_time' => $startTime,
                'is_live' => $isLive,
                'score' => $currentScore,
                'minute' => $minute,
                'odds' => [
                    'home' => 2.00,
                    'draw' => 3.20,
                    'away' => 3.50
                ],
                'additional_markets' => $this->getDefaultAdditionalMarkets()
            ];

            // Maç oranlarını çek
            $odds = $event['odds'] ?? [];
            if (!empty($odds)) {
                $match['odds'] = $this->extractBestOddsFromEvent($odds);
                $match['additional_markets'] = $this->extractAdditionalMarketsFromEvent($odds);
            }

            $formattedMatches[] = $match;
        }

        return $formattedMatches;
    }

    /**
     * Get league display name
     */
    private function getLeagueName($leagueID)
    {
        $leagueNames = [
            'EPL' => 'Premier League',
            'BUNDESLIGA' => 'Bundesliga',
            'LA_LIGA' => 'La Liga',
            'SERIE_A' => 'Serie A',
            'FR_LIGUE_1' => 'Ligue 1',
            'CHAMPIONS_LEAGUE' => 'Champions League',
            'EUROPA_LEAGUE' => 'Europa League',
            'EREDIVISIE' => 'Eredivisie'
        ];
        
        return $leagueNames[$leagueID] ?? $leagueID;
    }

    /**
     * Calculate current minute from period
     */
    private function calculateMinuteFromPeriod($period)
    {
        switch ($period) {
            case '1H':
            case '1h':
            case 'FIRST_HALF':
                return rand(1, 45);
            case '2H':
            case '2h':
            case 'SECOND_HALF':
                return rand(46, 90);
            case 'HT':
            case 'HALF_TIME':
                return 45;
            default:
                return 0;
        }
    }

    /**
     * Get default additional markets
     */
    private function getDefaultAdditionalMarkets()
    {
        return [
            'over_under_2_5' => ['over' => '1.85', 'under' => '1.95'],
            'double_chance' => ['1X' => '1.35', '12' => '1.30', 'X2' => '1.85'],
            'both_teams_score' => ['yes' => '1.80', 'no' => '2.00']
        ];
    }

    /**
     * Calculate current minute for live matches
     */
    private function calculateMinute($event)
    {
        if (!($event['live'] ?? false)) {
            return 0;
        }

        // Eğer API'de period bilgisi varsa kullan
        if (isset($event['currentPeriod'])) {
            return $this->calculateMinuteFromPeriod($event['currentPeriod']);
        }

        return rand(1, 90);
    }

    /**
     * Extract best odds from event data
     */
    private function extractBestOddsFromEvent($odds)
    {
        $bestOdds = [
            'home' => 2.00,
            'draw' => 3.20,
            'away' => 3.50
        ];

        // Soccer için goals tabanlı oddID'ler kullan
        foreach ($odds as $oddID => $oddData) {
            // Odd değerini al
            $oddValue = null;
            if (isset($oddData['bookOdds'])) {
                $oddValue = $oddData['bookOdds'];
            } elseif (isset($oddData['odds'])) {
                $oddValue = $oddData['odds'];
            } elseif (isset($oddData['currentOdds'])) {
                $oddValue = $oddData['currentOdds'];
            } elseif (isset($oddData['openOdds'])) {
                $oddValue = $oddData['openOdds'];
            }
            
            if (!$oddValue) continue;
            
            // 3-way moneyline oranları (soccer için points kullanılıyor)
            if ($oddID == 'points-home-game-ml3way-home' || 
                $oddID == 'points-home-reg-ml3way-home') {
                $bestOdds['home'] = $this->convertAmericanToDecimal($oddValue);
            }
            elseif ($oddID == 'points-away-game-ml3way-away' || 
                    $oddID == 'points-away-reg-ml3way-away') {
                $bestOdds['away'] = $this->convertAmericanToDecimal($oddValue);
            }
            elseif ($oddID == 'points-all-game-ml3way-draw' || 
                    $oddID == 'points-all-reg-ml3way-draw') {
                $bestOdds['draw'] = $this->convertAmericanToDecimal($oddValue);
            }
        }

        return $bestOdds;
    }

    /**
     * Extract additional markets from event data
     */
    private function extractAdditionalMarketsFromEvent($odds)
    {
        $markets = $this->getDefaultAdditionalMarkets();

        foreach ($odds as $oddID => $oddData) {
            // Odd değerini al
            $oddValue = null;
            if (isset($oddData['bookOdds'])) {
                $oddValue = $oddData['bookOdds'];
            } elseif (isset($oddData['odds'])) {
                $oddValue = $oddData['odds'];
            } elseif (isset($oddData['currentOdds'])) {
                $oddValue = $oddData['currentOdds'];
            } elseif (isset($oddData['openOdds'])) {
                $oddValue = $oddData['openOdds'];
            }
            
            if (!$oddValue) continue;
            
            // Over/Under 2.5 (soccer için points kullanılıyor)
            if (strpos($oddID, 'points-all-game-ou-over') !== false || 
                strpos($oddID, 'points-all-reg-ou-over') !== false) {
                $markets['over_under_2_5']['over'] = number_format($this->convertAmericanToDecimal($oddValue), 2);
            }
            elseif (strpos($oddID, 'points-all-game-ou-under') !== false || 
                    strpos($oddID, 'points-all-reg-ou-under') !== false) {
                $markets['over_under_2_5']['under'] = number_format($this->convertAmericanToDecimal($oddValue), 2);
            }
            
            // Double chance
            elseif (strpos($oddID, 'points-away-game-ml3way-away+draw') !== false || 
                    strpos($oddID, 'points-away-reg-ml3way-away+draw') !== false) {
                $markets['double_chance']['X2'] = number_format($this->convertAmericanToDecimal($oddValue), 2);
            }
            elseif (strpos($oddID, 'points-home-game-ml3way-home+draw') !== false || 
                    strpos($oddID, 'points-home-reg-ml3way-home+draw') !== false) {
                $markets['double_chance']['1X'] = number_format($this->convertAmericanToDecimal($oddValue), 2);
            }
            
            // Both teams to score
            elseif (strpos($oddID, 'points-all-game-yn-yes') !== false) {
                $markets['both_teams_score']['yes'] = number_format($this->convertAmericanToDecimal($oddValue), 2);
            }
            elseif (strpos($oddID, 'points-all-game-yn-no') !== false) {
                $markets['both_teams_score']['no'] = number_format($this->convertAmericanToDecimal($oddValue), 2);
            }
        }

        return $markets;
    }

    /**
     * Convert American odds to decimal odds
     */
    private function convertAmericanToDecimal($americanOdds)
    {
        // Eğer zaten decimal ise direkt döndür
        if (is_numeric($americanOdds) && $americanOdds > 0 && $americanOdds < 10) {
            return $americanOdds;
        }
        
        // American odds görünüşünde string olarak gelir (+150, -200 gibi)
        $odds = str_replace(['+', ' '], '', $americanOdds);
        $odds = floatval($odds);
        
        if ($odds > 0) {
            // Positive odds (+150 -> 2.50)
            return round(($odds / 100) + 1, 2);
        } else {
            // Negative odds (-200 -> 1.50)
            return round((100 / abs($odds)) + 1, 2);
        }
    }

    /**
     * Check if match is currently live
     */
    private function isMatchLive($commenceTime)
    {
        if (!$commenceTime) return false;
        
        $matchStart = \Carbon\Carbon::parse($commenceTime);
        $now = now();
        
        // Match is live if it started less than 2 hours ago
        return $matchStart->isPast() && $matchStart->diffInHours($now) < 2;
    }

    /**
     * Get specific match details
     */
    public function getMatchDetails($matchId)
    {
        $matches = $this->getLiveFootballMatches();
        
        foreach ($matches as $match) {
            if ($match['id'] === $matchId) {
                return $match;
            }
        }
        
        return null;
    }
    
    /**
     * Get all available markets for a match
     */
    public function getMatchAllMarkets($eventID)
    {
        try {
            // Önce event detayını al
            $response = Http::withHeaders([
                'X-Api-Key' => $this->apiKey
            ])->get($this->baseUrl . '/events', [
                'eventID' => $eventID
            ]);
            
            if (!$response->successful()) {
                Log::error('Event detail API Error: ' . $response->body());
                return null;
            }
            
            $data = $response->json();
            if (!isset($data['data'][0])) {
                return null;
            }
            
            $event = $data['data'][0];
            $allMarkets = [];
            
            // Tüm odds'ları kategorize et
            if (isset($event['odds'])) {
                foreach ($event['odds'] as $oddID => $oddData) {
                    $marketInfo = $this->parseOddID($oddID);
                    if ($marketInfo) {
                        $marketKey = $marketInfo['market'];
                        if (!isset($allMarkets[$marketKey])) {
                            $allMarkets[$marketKey] = [
                                'name' => $this->getMarketName($marketKey),
                                'selections' => []
                            ];
                        }
                        
                        $oddValue = $oddData['bookOdds'] ?? $oddData['odds'] ?? $oddData['currentOdds'] ?? null;
                        if ($oddValue) {
                            $allMarkets[$marketKey]['selections'][] = [
                                'oddID' => $oddID,
                                'name' => $marketInfo['selectionName'],
                                'odds' => $this->convertAmericanToDecimal($oddValue),
                                'selection' => $marketInfo['selection']
                            ];
                        }
                    }
                }
            }
            
            return $allMarkets;
            
        } catch (\Exception $e) {
            Log::error('Get all markets exception: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Parse oddID to extract market information
     */
    private function parseOddID($oddID)
    {
        // points-home-reg-ml3way-home formatını parse et
        $parts = explode('-', $oddID);
        if (count($parts) < 4) return null;
        
        $statType = $parts[0]; // points, goals vs
        $entity = $parts[1]; // home, away, all
        $period = $parts[2]; // game, reg, 1h, 2h
        
        // Market ve selection'ı belirle
        if (strpos($oddID, 'ml3way') !== false) {
            $market = 'ml3way_' . $period;
            if (strpos($oddID, 'home+draw') !== false) {
                $selection = '1X';
                $selectionName = 'Ev Sahibi veya Beraberlik';
            } elseif (strpos($oddID, 'away+draw') !== false) {
                $selection = 'X2';
                $selectionName = 'Deplasman veya Beraberlik';
            } elseif ($entity == 'home') {
                $selection = '1';
                $selectionName = 'Ev Sahibi';
            } elseif ($entity == 'away') {
                $selection = '2';
                $selectionName = 'Deplasman';
            } else {
                $selection = 'X';
                $selectionName = 'Beraberlik';
            }
        } elseif (strpos($oddID, '-ou-') !== false) {
            $market = 'ou_' . $period . '_' . $entity;
            if (strpos($oddID, 'over') !== false) {
                $selection = 'over';
                $selectionName = 'Üst';
            } else {
                $selection = 'under';
                $selectionName = 'Alt';
            }
        } elseif (strpos($oddID, '-sp-') !== false) {
            $market = 'spread_' . $period;
            $selection = $entity;
            $selectionName = $entity == 'home' ? 'Ev Sahibi Handikap' : 'Deplasman Handikap';
        } elseif (strpos($oddID, '-yn-') !== false) {
            $market = 'btts_' . $period;
            $selection = strpos($oddID, 'yes') !== false ? 'yes' : 'no';
            $selectionName = $selection == 'yes' ? 'Var' : 'Yok';
        } else {
            return null;
        }
        
        return [
            'market' => $market,
            'selection' => $selection,
            'selectionName' => $selectionName
        ];
    }
    
    /**
     * Get market display name
     */
    private function getMarketName($marketKey)
    {
        $marketNames = [
            'ml3way_game' => 'Maç Sonucu',
            'ml3way_reg' => 'Normal Süre Sonucu',
            'ml3way_1h' => 'İlk Yarı Sonucu',
            'ml3way_2h' => 'İkinci Yarı Sonucu',
            'ou_game_all' => 'Toplam Gol Alt/Üst',
            'ou_reg_all' => 'Normal Süre Toplam Gol',
            'ou_1h_all' => 'İlk Yarı Toplam Gol',
            'ou_2h_all' => 'İkinci Yarı Toplam Gol',
            'ou_game_home' => 'Ev Sahibi Toplam Gol',
            'ou_game_away' => 'Deplasman Toplam Gol',
            'spread_game' => 'Handikap',
            'spread_1h' => 'İlk Yarı Handikap',
            'btts_game' => 'Karşılıklı Gol',
            'btts_1h' => 'İlk Yarı Karşılıklı Gol'
        ];
        
        return $marketNames[$marketKey] ?? $marketKey;
    }
}