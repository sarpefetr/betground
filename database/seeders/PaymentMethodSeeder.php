<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ana Kategoriler
        $categories = [
            [
                'name' => 'Banka Transferi',
                'slug' => 'banka-transferi',
                'description' => 'Güvenli banka havalesi ve EFT işlemleri',
                'type' => 'category',
                'method_code' => 'bank_transfer',
                'is_active' => true,
                'order_index' => 1,
            ],
            [
                'name' => 'Kripto Para',
                'slug' => 'kripto-para',
                'description' => 'Bitcoin, Ethereum ve diğer kripto para yatırma işlemleri',
                'type' => 'category',
                'method_code' => 'crypto',
                'is_active' => true,
                'order_index' => 2,
            ],
            [
                'name' => 'E-Cüzdanlar',
                'slug' => 'e-cuzdanlar',
                'description' => 'Dijital cüzdan sistemleri ile hızlı ödeme',
                'type' => 'category',
                'method_code' => 'ewallet',
                'is_active' => true,
                'order_index' => 3,
            ],
            [
                'name' => 'ATM ve Kart',
                'slug' => 'atm-ve-kart',
                'description' => 'ATM ve kredi kartı işlemleri',
                'type' => 'category',
                'method_code' => 'atm',
                'is_active' => true,
                'order_index' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = PaymentMethod::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            // Alt kategoriler/ödeme yöntemleri
            $methods = [];

            if ($categoryData['slug'] === 'banka-transferi') {
                $methods = [
                    [
                        'name' => 'Xpay',
                        'slug' => 'xpay',
                        'description' => 'Anında banka transferi sistemi',
                        'method_code' => 'bank_transfer',
                        'min_amount' => 50.00,
                        'max_amount' => 50000.00,
                        'commission_rate' => 0.00,
                        'processing_time' => 'ANLIK',
                        'bank_details' => [
                            'bank_name' => 'Türkiye İş Bankası',
                            'bank_iban' => 'TR39 0020 6002 0343 1131 7760 464',
                            'account_holder' => 'Pratik İşlem Ödeme ve Elektronik Para A.Ş.',
                        ],
                        'instructions' => 'Havale/EFT açıklama kısmına kullanıcı adınızı yazınız. İşleminiz otomatik olarak hesabınıza yansıyacaktır.',
                        'is_featured' => true,
                        'order_index' => 1,
                    ],
                    [
                        'name' => 'Anında Banka',
                        'slug' => 'aninda-banka',
                        'description' => 'Hızlı banka havalesi sistemi',
                        'method_code' => 'bank_transfer',
                        'min_amount' => 100.00,
                        'max_amount' => 100000.00,
                        'commission_rate' => 0.00,
                        'processing_time' => '0-24 SAAT',
                        'bank_details' => [
                            'bank_name' => 'Garanti BBVA',
                            'bank_iban' => 'TR12 0006 2000 1234 0006 2987 54',
                            'account_holder' => 'BetGround Ödeme Sistemleri Ltd.',
                        ],
                        'instructions' => 'Havale açıklama kısmına TC kimlik numaranızı yazınız.',
                        'order_index' => 2,
                    ],
                ];
            } elseif ($categoryData['slug'] === 'kripto-para') {
                $methods = [
                    [
                        'name' => 'Anında Kripto',
                        'slug' => 'aninda-kripto',
                        'description' => 'Bitcoin, Ethereum, USDT anında transfer',
                        'method_code' => 'crypto',
                        'min_amount' => 200.00,
                        'max_amount' => 200000.00,
                        'commission_rate' => 0.00,
                        'processing_time' => 'ANLIK',
                        'instructions' => 'QR kodu okutarak veya adres kopyalayarak ödeme yapabilirsiniz.',
                        'is_featured' => true,
                        'order_index' => 1,
                    ],
                    [
                        'name' => 'Hızlı Kripto',
                        'slug' => 'hizli-kripto',
                        'description' => 'Binance, Tether, Litecoin transferi',
                        'method_code' => 'crypto',
                        'min_amount' => 100.00,
                        'max_amount' => 100000.00,
                        'commission_rate' => 1.00,
                        'processing_time' => '0-2 SAAT',
                        'instructions' => 'Network ücretleri hesabınıza yansır.',
                        'order_index' => 2,
                    ],
                ];
            } elseif ($categoryData['slug'] === 'e-cuzdanlar') {
                $methods = [
                    [
                        'name' => 'PayPal Express',
                        'slug' => 'paypal-express',
                        'description' => 'PayPal ile hızlı ödeme',
                        'method_code' => 'ewallet',
                        'min_amount' => 75.00,
                        'max_amount' => 25000.00,
                        'commission_rate' => 2.50,
                        'processing_time' => 'ANLIK',
                        'instructions' => 'PayPal hesabınızla giriş yaparak ödeme tamamlayın.',
                        'order_index' => 1,
                    ],
                    [
                        'name' => 'Skrill Hızlı',
                        'slug' => 'skrill-hizli',
                        'description' => 'Skrill e-cüzdan ile ödeme',
                        'method_code' => 'ewallet',
                        'min_amount' => 50.00,
                        'max_amount' => 15000.00,
                        'commission_rate' => 3.00,
                        'processing_time' => 'ANLIK',
                        'instructions' => 'Skrill hesabınızdan direkt transfer yapın.',
                        'order_index' => 2,
                    ],
                ];
            } elseif ($categoryData['slug'] === 'atm-ve-kart') {
                $methods = [
                    [
                        'name' => 'Hızlı Kart',
                        'slug' => 'hizli-kart',
                        'description' => 'Visa, MasterCard ile anında ödeme',
                        'method_code' => 'credit_card',
                        'min_amount' => 50.00,
                        'max_amount' => 50000.00,
                        'commission_rate' => 0.00,
                        'processing_time' => 'ANLIK',
                        'instructions' => 'Kart bilgilerinizi güvenle girin, 3D Secure ile korumalıdır.',
                        'is_featured' => true,
                        'order_index' => 1,
                    ],
                    [
                        'name' => 'Mobil Ödeme',
                        'slug' => 'mobil-odeme',
                        'description' => 'Turkcell, Vodafone, Türk Telekom',
                        'method_code' => 'mobile',
                        'min_amount' => 50.00,
                        'max_amount' => 500.00,
                        'commission_rate' => 8.00,
                        'processing_time' => 'ANLIK',
                        'instructions' => 'Hat numaranızı girin, SMS ile onaylayın.',
                        'order_index' => 2,
                    ],
                ];
            }

            // Alt ödeme yöntemlerini oluştur
            foreach ($methods as $methodData) {
                $methodData['type'] = 'method';
                $methodData['parent_id'] = $category->id;
                $methodData['is_active'] = $methodData['is_active'] ?? true;
                $methodData['supported_currencies'] = ['TRY', 'USD', 'EUR'];

                PaymentMethod::firstOrCreate(
                    ['slug' => $methodData['slug']],
                    $methodData
                );
            }
        }

        $this->command->info('Örnek ödeme yöntemleri oluşturuldu!');
        $this->command->info('- 4 ana kategori eklendi');
        $this->command->info('- 8 ödeme yöntemi eklendi');
        $this->command->info('- Xpay, Anında Banka, Kripto yöntemleri hazır');
    }
}