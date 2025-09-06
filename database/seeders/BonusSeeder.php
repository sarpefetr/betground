<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bonus;
use Carbon\Carbon;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bonuses = [
            [
                'name' => 'Hoş Geldin Bonusu',
                'slug' => 'hos-geldin-bonusu',
                'description' => 'İlk yatırımınıza %200 bonus + 100 freespin! Minimum 50₺ yatırım ile maksimum 5.000₺ bonus kazanın.',
                'bonus_type' => 'welcome',
                'amount_type' => 'percentage',
                'amount_value' => 200.00,
                'min_deposit' => 50.00,
                'max_bonus' => 5000.00,
                'wagering_requirement' => 35,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(6),
                'terms_conditions' => 'Bonus sadece yeni üyelere verilir. 35x çevrim şartı uygulanır. Bonus kullanımı için minimum 50₺ yatırım gereklidir.',
                'is_active' => true,
                'is_featured' => true,
                'usage_limit' => null,
                'user_limit' => 1,
                'order_index' => 1,
                'countries' => ['TR'],
                'currencies' => ['TRY'],
            ],
            [
                'name' => 'Günlük Bonus',
                'slug' => 'gunluk-bonus',
                'description' => 'Her gün yatırımınıza %50 bonus kazanın! Günde bir kez kullanabilirsiniz.',
                'bonus_type' => 'daily',
                'amount_type' => 'percentage',
                'amount_value' => 50.00,
                'min_deposit' => 100.00,
                'max_bonus' => 500.00,
                'wagering_requirement' => 25,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'terms_conditions' => 'Günde bir kez kullanılabilir. 25x çevrim şartı uygulanır.',
                'is_active' => true,
                'is_featured' => false,
                'usage_limit' => null,
                'user_limit' => 1,
                'order_index' => 2,
                'countries' => ['TR'],
                'currencies' => ['TRY'],
            ],
            [
                'name' => 'Hafta Sonu Özel',
                'slug' => 'hafta-sonu-ozel',
                'description' => 'Hafta sonları özel %75 bonus! Cumartesi ve Pazar günleri geçerlidir.',
                'bonus_type' => 'weekly',
                'amount_type' => 'percentage',
                'amount_value' => 75.00,
                'min_deposit' => 200.00,
                'max_bonus' => 1500.00,
                'wagering_requirement' => 30,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'terms_conditions' => 'Sadece hafta sonları geçerlidir. 30x çevrim şartı uygulanır.',
                'is_active' => true,
                'is_featured' => true,
                'usage_limit' => null,
                'user_limit' => 1,
                'order_index' => 3,
                'countries' => ['TR'],
                'currencies' => ['TRY'],
            ],
            [
                'name' => 'Slot Turnuvası',
                'slug' => 'slot-turnuvasi',
                'description' => 'Toplam ₺50.000 ödül havuzu! En çok kazanan oyuncular ödül alacak.',
                'bonus_type' => 'tournament',
                'amount_type' => 'fixed',
                'amount_value' => 50000.00,
                'min_deposit' => 0.00,
                'max_bonus' => 15000.00,
                'wagering_requirement' => 1,
                'valid_from' => now(),
                'valid_until' => now()->addDays(30),
                'terms_conditions' => 'Turnuva süresince en çok kazanan 100 oyuncu ödül alacaktır.',
                'is_active' => true,
                'is_featured' => true,
                'usage_limit' => 100,
                'user_limit' => 1,
                'order_index' => 4,
                'countries' => ['TR'],
                'currencies' => ['TRY'],
            ],
            [
                'name' => 'Cashback %20',
                'slug' => 'cashback-20',
                'description' => 'Kayıplarınızın %20\'sini geri alın! Haftalık otomatik hesaplama.',
                'bonus_type' => 'cashback',
                'amount_type' => 'percentage',
                'amount_value' => 20.00,
                'min_deposit' => 500.00,
                'max_bonus' => 2000.00,
                'wagering_requirement' => 1,
                'valid_from' => now(),
                'valid_until' => null,
                'terms_conditions' => 'Minimum 500₺ kayıp şartı. Haftalık otomatik hesaplama. Maksimum 2000₺ cashback.',
                'is_active' => true,
                'is_featured' => false,
                'usage_limit' => null,
                'user_limit' => 1,
                'order_index' => 5,
                'countries' => ['TR'],
                'currencies' => ['TRY'],
            ],
            [
                'name' => 'VIP Program',
                'slug' => 'vip-program',
                'description' => 'VIP üyelerimize özel ayrıcalıklar! Kişisel müşteri temsilcisi ve özel bonuslar.',
                'bonus_type' => 'vip',
                'amount_type' => 'percentage',
                'amount_value' => 100.00,
                'min_deposit' => 1000.00,
                'max_bonus' => null,
                'wagering_requirement' => 15,
                'valid_from' => now(),
                'valid_until' => null,
                'terms_conditions' => 'Sadece davetiye ile. Aylık 10.000₺ üzeri yatırım şartı.',
                'is_active' => true,
                'is_featured' => true,
                'usage_limit' => null,
                'user_limit' => 1,
                'order_index' => 6,
                'countries' => ['TR'],
                'currencies' => ['TRY'],
            ],
            [
                'name' => 'Arkadaş Davet Et',
                'slug' => 'arkadas-davet-et',
                'description' => 'Arkadaş davet et, ₺250 bonus kazan! Arkadaşın da ₺100 bonus alır.',
                'bonus_type' => 'referral',
                'amount_type' => 'fixed',
                'amount_value' => 250.00,
                'min_deposit' => 0.00,
                'max_bonus' => null,
                'wagering_requirement' => 20,
                'valid_from' => now(),
                'valid_until' => null,
                'terms_conditions' => 'Davet edilen arkadaş minimum 100₺ yatırım yapmalıdır. Sınırsız davet hakkı.',
                'is_active' => true,
                'is_featured' => false,
                'usage_limit' => null,
                'user_limit' => 999,
                'order_index' => 7,
                'countries' => ['TR'],
                'currencies' => ['TRY'],
            ],
        ];

        foreach ($bonuses as $bonusData) {
            Bonus::firstOrCreate(
                ['slug' => $bonusData['slug']],
                $bonusData
            );
        }

        $this->command->info('Örnek bonuslar oluşturuldu!');
        $this->command->info('- ' . count($bonuses) . ' bonus eklendi');
        $this->command->info('- Hoş geldin bonusu: %200 + 5000₺ limit');
        $this->command->info('- Günlük bonus: %50 + 500₺ limit');
        $this->command->info('- Slot turnuvası: 50.000₺ ödül havuzu');
    }
}