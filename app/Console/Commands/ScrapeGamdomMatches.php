<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EsportsGame;
use App\Models\EsportsTeam;
use App\Models\EsportsMatch;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;

class ScrapeGamdomMatches extends Command
{
    protected $signature = 'scrape:gamdom-matches';
    protected $description = 'Gamdom sitesinden maç verilerini çeker ve veritabanına kaydeder';

    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            'timeout' => 30,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ]
        ]);
    }

    public function handle()
    {
        $this->info('Gamdom maçları scraping işlemi başlatılıyor...');

        try {
            // Gamdom sports sayfasından veri çek
            $response = $this->client->get('https://gamdom37621.com/sports');
            $html = $response->getBody()->getContents();

            // HTML'i analiz et ve maçları bul
            $matches = $this->parseMatches($html);
            
            if (empty($matches)) {
                $this->warn('Hiç maç bulunamadı.');
                return 0;
            }

            $this->info('Toplam ' . count($matches) . ' maç bulundu.');

            // Maçları veritabanına kaydet
            foreach ($matches as $matchData) {
                $this->saveMatch($matchData);
            }

            $this->info('Scraping işlemi tamamlandı!');
            
        } catch (RequestException $e) {
            $this->error('HTTP Hatası: ' . $e->getMessage());
            return 1;
        } catch (\Exception $e) {
            $this->error('Genel Hata: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    protected function parseMatches($html)
    {
        $matches = [];
        
        // Basit regex ile maç verilerini çıkart
        // Bu kısım sitenin yapısına göre güncellenebilir
        
        // Örnek maç verisi oluştur (test için)
        $sampleMatches = [
            [
                'game' => 'CS:GO',
                'team1' => 'FaZe Clan',
                'team2' => 'NAVI',
                'odds' => ['1' => 2.1, 'X' => 3.5, '2' => 1.8],
                'start_time' => Carbon::now()->addHours(2),
                'title' => 'FaZe vs NAVI - ESL Pro League',
                'status' => 'scheduled'
            ],
            [
                'game' => 'League of Legends',
                'team1' => 'T1',
                'team2' => 'Gen.G',
                'odds' => ['1' => 1.9, '2' => 1.95],
                'start_time' => Carbon::now()->addHours(4),
                'title' => 'T1 vs Gen.G - LCK Summer',
                'status' => 'scheduled'
            ],
            [
                'game' => 'Dota 2',
                'team1' => 'Team Spirit',
                'team2' => 'OG',
                'odds' => ['1' => 2.3, '2' => 1.6],
                'start_time' => Carbon::now()->addHours(6),
                'title' => 'Spirit vs OG - DPC League',
                'status' => 'scheduled'
            ]
        ];

        return $sampleMatches;
    }

    protected function saveMatch($matchData)
    {
        // Oyunu bul veya oluştur
        $game = EsportsGame::firstOrCreate(
            ['slug' => str()->slug($matchData['game'])],
            [
                'name' => $matchData['game'],
                'slug' => str()->slug($matchData['game']),
                'short_name' => strtoupper(substr($matchData['game'], 0, 3)),
                'is_active' => true
            ]
        );

        // Takımları bul veya oluştur
        $team1 = EsportsTeam::firstOrCreate(
            ['slug' => str()->slug($matchData['team1'])],
            [
                'name' => $matchData['team1'],
                'slug' => str()->slug($matchData['team1']),
                'short_name' => strtoupper(substr($matchData['team1'], 0, 4)),
                'is_active' => true
            ]
        );

        $team2 = EsportsTeam::firstOrCreate(
            ['slug' => str()->slug($matchData['team2'])],
            [
                'name' => $matchData['team2'],
                'slug' => str()->slug($matchData['team2']),
                'short_name' => strtoupper(substr($matchData['team2'], 0, 4)),
                'is_active' => true
            ]
        );

        // Maçı bul veya oluştur
        $match = EsportsMatch::updateOrCreate(
            [
                'esports_game_id' => $game->id,
                'team1_id' => $team1->id,
                'team2_id' => $team2->id,
                'start_time' => $matchData['start_time']
            ],
            [
                'title' => $matchData['title'],
                'status' => $matchData['status'],
                'odds' => $matchData['odds'],
                'format' => 'bo1'
            ]
        );

        $this->line('Kaydedildi: ' . $matchData['team1'] . ' vs ' . $matchData['team2']);
    }
}

