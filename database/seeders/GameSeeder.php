<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            // Slot Games
            [
                'name' => 'Sweet Bonanza',
                'slug' => 'sweet-bonanza',
                'category' => 'slots',
                'type' => 'Video Slot',
                'provider' => 'Pragmatic Play',
                'rtp' => 96.50,
                'min_bet' => 1.00,
                'max_bet' => 5000.00,
                'is_live' => false,
                'is_featured' => true,
                'is_active' => true,
                'order_index' => 1
            ],
            [
                'name' => 'Book of Dead',
                'slug' => 'book-of-dead',
                'category' => 'slots',
                'type' => 'Video Slot',
                'provider' => "Play'n GO",
                'rtp' => 94.25,
                'min_bet' => 0.50,
                'max_bet' => 2500.00,
                'is_live' => false,
                'is_featured' => true,
                'is_active' => true,
                'order_index' => 2
            ],
            [
                'name' => 'Starburst',
                'slug' => 'starburst',
                'category' => 'slots',
                'type' => 'Video Slot',
                'provider' => 'NetEnt',
                'rtp' => 96.10,
                'min_bet' => 0.25,
                'max_bet' => 1000.00,
                'is_live' => false,
                'is_featured' => false,
                'is_active' => true,
                'order_index' => 3
            ],
            [
                'name' => 'Wolf Gold',
                'slug' => 'wolf-gold',
                'category' => 'slots',
                'type' => 'Video Slot',
                'provider' => 'Pragmatic Play',
                'rtp' => 96.01,
                'min_bet' => 1.00,
                'max_bet' => 2500.00,
                'is_live' => false,
                'is_featured' => false,
                'is_active' => true,
                'order_index' => 4
            ],

            // Casino Games
            [
                'name' => 'Blackjack VIP',
                'slug' => 'blackjack-vip',
                'category' => 'casino',
                'type' => 'Table Game',
                'provider' => 'Evolution Gaming',
                'rtp' => 99.28,
                'min_bet' => 10.00,
                'max_bet' => 50000.00,
                'is_live' => true,
                'is_featured' => true,
                'is_active' => true,
                'order_index' => 1
            ],
            [
                'name' => 'Türk Ruleti',
                'slug' => 'turk-ruleti',
                'category' => 'casino',
                'type' => 'Table Game',
                'provider' => 'Evolution Gaming',
                'rtp' => 97.30,
                'min_bet' => 5.00,
                'max_bet' => 25000.00,
                'is_live' => true,
                'is_featured' => true,
                'is_active' => true,
                'order_index' => 2
            ],
            [
                'name' => 'Baccarat Salon',
                'slug' => 'baccarat-salon',
                'category' => 'casino',
                'type' => 'Table Game',
                'provider' => 'Evolution Gaming',
                'rtp' => 98.94,
                'min_bet' => 25.00,
                'max_bet' => 100000.00,
                'is_live' => true,
                'is_featured' => false,
                'is_active' => true,
                'order_index' => 3
            ],
            [
                'name' => 'Casino Hold\'em',
                'slug' => 'casino-holdem',
                'category' => 'casino',
                'type' => 'Table Game',
                'provider' => 'Evolution Gaming',
                'rtp' => 97.84,
                'min_bet' => 10.00,
                'max_bet' => 10000.00,
                'is_live' => true,
                'is_featured' => false,
                'is_active' => true,
                'order_index' => 4
            ],

            // Game Shows
            [
                'name' => 'Crazy Time',
                'slug' => 'crazy-time',
                'category' => 'casino',
                'type' => 'Game Show',
                'provider' => 'Evolution Gaming',
                'rtp' => 96.08,
                'min_bet' => 5.00,
                'max_bet' => 50000.00,
                'is_live' => true,
                'is_featured' => true,
                'is_active' => true,
                'order_index' => 5
            ],
            [
                'name' => 'Monopoly Live',
                'slug' => 'monopoly-live',
                'category' => 'casino',
                'type' => 'Game Show',
                'provider' => 'Evolution Gaming',
                'rtp' => 96.23,
                'min_bet' => 5.00,
                'max_bet' => 25000.00,
                'is_live' => true,
                'is_featured' => false,
                'is_active' => true,
                'order_index' => 6
            ],
            [
                'name' => 'Dream Catcher',
                'slug' => 'dream-catcher',
                'category' => 'casino',
                'type' => 'Game Show',
                'provider' => 'Evolution Gaming',
                'rtp' => 96.58,
                'min_bet' => 2.50,
                'max_bet' => 10000.00,
                'is_live' => true,
                'is_featured' => false,
                'is_active' => true,
                'order_index' => 7
            ],

            // Additional Slots
            [
                'name' => 'Gates of Olympus',
                'slug' => 'gates-of-olympus',
                'category' => 'slots',
                'type' => 'Video Slot',
                'provider' => 'Pragmatic Play',
                'rtp' => 96.50,
                'min_bet' => 1.00,
                'max_bet' => 2500.00,
                'is_live' => false,
                'is_featured' => true,
                'is_active' => true,
                'order_index' => 5
            ],
            [
                'name' => 'Big Bass Bonanza',
                'slug' => 'big-bass-bonanza',
                'category' => 'slots',
                'type' => 'Video Slot',
                'provider' => 'Pragmatic Play',
                'rtp' => 96.71,
                'min_bet' => 0.50,
                'max_bet' => 1250.00,
                'is_live' => false,
                'is_featured' => false,
                'is_active' => true,
                'order_index' => 6
            ]
        ];

        foreach ($games as $gameData) {
            Game::firstOrCreate(
                ['slug' => $gameData['slug']],
                $gameData
            );
        }

        $this->command->info('Örnek oyunlar oluşturuldu!');
        $this->command->info('- ' . count($games) . ' oyun eklendi');
        $this->command->info('- Slots: ' . collect($games)->where('category', 'slots')->count());
        $this->command->info('- Casino: ' . collect($games)->where('category', 'casino')->count());
    }
}