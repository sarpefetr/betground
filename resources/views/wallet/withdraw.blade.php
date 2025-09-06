@extends('layouts.app')

@section('title', 'Para Çek - BetGround')

@push('styles')
<style>
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 215, 0, 0.1);
    }
    .withdrawal-method {
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .withdrawal-method.selected {
        border-color: #ffd700;
        background: rgba(255, 215, 0, 0.1);
    }
    .withdrawal-card {
        backdrop-filter: blur(10px);
        background: rgba(42, 42, 42, 0.95);
    }
    .input-focus:focus {
        border-color: #ffd700;
        box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-money-bill-wave mr-3"></i>Para Çek
            </h1>
            <p class="text-xl text-gray-300">Kazancınızı hızlı ve güvenli şekilde çekin</p>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Withdrawal Methods -->
        <div class="lg:col-span-2">
            <div class="withdrawal-card rounded-2xl p-6 border border-accent">
                <h2 class="text-2xl font-bold mb-6">Para Çekme Yöntemi Seçin</h2>
                
                <form method="POST" action="{{ route('withdraw') }}" x-data="{ 
                    selectedMethod: 'bank-transfer',
                    maxBalance: {{ $userWallet->balance ?? 0 }}
                }">
                    @csrf
                    <div class="space-y-4">
                        <!-- Bank Transfer -->
                        <div @click="selectedMethod = 'bank-transfer'" 
                             :class="selectedMethod === 'bank-transfer' ? 'selected' : ''"
                             class="withdrawal-method bg-secondary rounded-xl p-4 cursor-pointer card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="text-gold text-2xl mr-4">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg">Banka Havalesi</h3>
                                        <p class="text-sm text-gray-400">Kendi banka hesabınıza</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs">1-24 SAAT</span>
                                </div>
                            </div>
                            <div class="mt-3 text-sm text-gray-300">
                                Min: ₺100 - Maks: ₺50.000 | Komisyon: %0
                            </div>
                        </div>

                        <!-- Crypto -->
                        <div @click="selectedMethod = 'crypto'" 
                             :class="selectedMethod === 'crypto' ? 'selected' : ''"
                             class="withdrawal-method bg-secondary rounded-xl p-4 cursor-pointer card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="text-gold text-2xl mr-4">
                                        <i class="fab fa-bitcoin"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg">Kripto Para</h3>
                                        <p class="text-sm text-gray-400">Bitcoin, Ethereum, USDT</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">ANLIK</span>
                                    <i class="fab fa-bitcoin text-2xl text-orange-500"></i>
                                </div>
                            </div>
                            <div class="mt-3 text-sm text-gray-300">
                                Min: ₺200 - Maks: ₺100.000 | Komisyon: %1
                            </div>
                        </div>

                        <!-- E-Wallet -->
                        <div @click="selectedMethod = 'ewallet'" 
                             :class="selectedMethod === 'ewallet' ? 'selected' : ''"
                             class="withdrawal-method bg-secondary rounded-xl p-4 cursor-pointer card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="text-gold text-2xl mr-4">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg">E-Cüzdan</h3>
                                        <p class="text-sm text-gray-400">PayPal, Skrill, Neteller</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">ANLIK</span>
                                </div>
                            </div>
                            <div class="mt-3 text-sm text-gray-300">
                                Min: ₺75 - Maks: ₺15.000 | Komisyon: %3
                            </div>
                        </div>

                        <!-- Hidden input for selected method -->
                        <input type="hidden" name="method" :value="selectedMethod">

                        <!-- Withdrawal Form -->
                        <div x-show="selectedMethod" x-transition class="mt-8 p-6 bg-accent rounded-xl">
                            <h3 class="text-xl font-bold mb-4">Para Çekme Detayları</h3>
                            
                            <!-- Amount -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium mb-2">Çekim Tutarı</label>
                                <div class="relative">
                                    <input type="number" 
                                           name="amount"
                                           placeholder="0"
                                           :max="maxBalance"
                                           min="75"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white text-xl font-bold text-center focus:outline-none input-focus transition-all">
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gold font-bold">₺</div>
                                </div>
                                
                                <!-- Quick amounts -->
                                <div class="grid grid-cols-4 gap-2 mt-3">
                                    <button type="button" onclick="document.querySelector('input[name=amount]').value = 250" class="bg-secondary border border-gray-600 rounded-lg py-2 text-sm hover:border-gold transition-colors">₺250</button>
                                    <button type="button" onclick="document.querySelector('input[name=amount]').value = 500" class="bg-secondary border border-gray-600 rounded-lg py-2 text-sm hover:border-gold transition-colors">₺500</button>
                                    <button type="button" onclick="document.querySelector('input[name=amount]').value = 750" class="bg-secondary border border-gray-600 rounded-lg py-2 text-sm hover:border-gold transition-colors">₺750</button>
                                    <button type="button" @click="document.querySelector('input[name=amount]').value = maxBalance" class="bg-gold text-black border border-gold rounded-lg py-2 text-sm font-bold hover:bg-yellow-500 transition-colors">Tümü</button>
                                </div>
                                
                                <div class="mt-2 text-xs text-gray-400">
                                    Mevcut bakiye: <span class="text-gold">₺{{ number_format($userWallet->balance ?? 0, 2) }}</span>
                                </div>
                            </div>

                            <!-- Bank Transfer Form -->
                            <div x-show="selectedMethod === 'bank-transfer'" x-transition class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Banka</label>
                                    <select name="bank_name" class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                        <option value="">Banka Seçiniz</option>
                                        <option value="is_bankasi">Türkiye İş Bankası</option>
                                        <option value="garanti_bbva">Garanti BBVA</option>
                                        <option value="yapi_kredi">Yapı Kredi</option>
                                        <option value="akbank">Akbank</option>
                                        <option value="ziraat">Ziraat Bankası</option>
                                        <option value="halkbank">Halkbank</option>
                                        <option value="vakifbank">Vakıfbank</option>
                                        <option value="qnb_finansbank">QNB Finansbank</option>
                                        <option value="other">Diğer</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Hesap Sahibi Adı</label>
                                        <input type="text" 
                                               name="account_holder"
                                               value="{{ auth()->user()->name }}"
                                               placeholder="Ad Soyad"
                                               class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">TC Kimlik No</label>
                                        <input type="text" 
                                               name="identity_number"
                                               placeholder="12345678901"
                                               maxlength="11"
                                               class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">IBAN</label>
                                    <input type="text" 
                                           name="iban"
                                           placeholder="TR12 3456 7890 1234 5678 9012 34"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                </div>
                            </div>

                            <!-- Crypto Form -->
                            <div x-show="selectedMethod === 'crypto'" x-transition class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Kripto Para</label>
                                    <select name="crypto_type" class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                        <option value="btc">Bitcoin (BTC)</option>
                                        <option value="eth">Ethereum (ETH)</option>
                                        <option value="usdt">Tether (USDT)</option>
                                        <option value="bnb">Binance Coin (BNB)</option>
                                        <option value="ltc">Litecoin (LTC)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Cüzdan Adresi</label>
                                    <input type="text" 
                                           name="wallet_address"
                                           placeholder="Kripto para cüzdan adresinizi girin"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                </div>
                                <div class="bg-yellow-600 text-black p-3 rounded-lg text-sm">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Dikkat:</strong> Cüzdan adresini kontrol edin. Yanlış adres para kaybına neden olabilir.
                                </div>
                            </div>

                            <!-- E-Wallet Form -->
                            <div x-show="selectedMethod === 'ewallet'" x-transition class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">E-Cüzdan Türü</label>
                                    <select name="ewallet_type" class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                        <option value="paypal">PayPal</option>
                                        <option value="skrill">Skrill</option>
                                        <option value="neteller">Neteller</option>
                                        <option value="ecopayz">ecoPayz</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">E-posta/Hesap ID</label>
                                    <input type="text" 
                                           name="ewallet_account"
                                           placeholder="E-cüzdan hesap bilginizi girin"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-6 p-4 bg-secondary rounded-lg">
                                <h4 class="font-bold mb-2 flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Önemli Bilgiler
                                </h4>
                                <ul class="text-sm space-y-1 text-gray-300">
                                    <li>• Para çekme işlemi 1-24 saat içinde gerçekleşir</li>
                                    <li>• Kimlik doğrulaması gerekli olabilir</li>
                                    <li>• Günlük çekim limiti: ₺10.000</li>
                                    <li>• Aylık çekim limiti: ₺100.000</li>
                                </ul>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gold text-black py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors mt-6">
                                <i class="fas fa-money-bill-wave mr-2"></i>Para Çekme Talebini Gönder
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Balance Info -->
            <div class="bg-secondary rounded-xl p-6 border border-accent">
                <h3 class="text-xl font-bold mb-4">Bakiye Bilgileri</h3>
                @php
                    $userWallet = \App\Models\Wallet::where('user_id', auth()->id())->first();
                @endphp
                <div class="space-y-3">
                    <div class="text-center p-4 bg-accent rounded-lg">
                        <div class="text-3xl font-bold text-gold">₺{{ number_format($userWallet->balance ?? 0, 2) }}</div>
                        <div class="text-sm text-gray-400">Ana Bakiye</div>
                    </div>
                    
                    @if($userWallet && $userWallet->bonus_balance > 0)
                        <div class="text-center p-4 bg-blue-900 bg-opacity-30 rounded-lg">
                            <div class="text-2xl font-bold text-blue-400">₺{{ number_format($userWallet->bonus_balance, 2) }}</div>
                            <div class="text-sm text-gray-400">Bonus Bakiye</div>
                        </div>
                    @endif
                    
                    <div class="text-center p-4 bg-green-900 bg-opacity-30 rounded-lg">
                        <div class="text-2xl font-bold text-green-400">₺{{ number_format($userWallet->balance ?? 0, 2) }}</div>
                        <div class="text-sm text-gray-400">Çekilebilir Bakiye</div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-400 text-center">
                    <i class="fas fa-info-circle mr-1"></i>Sadece ana bakiye çekilebilir. Bonus bakiye çevrim şartını tamamlayınca ana bakiyeye eklenir.
                </div>
            </div>

            <!-- Withdrawal History -->
            <div class="bg-secondary rounded-xl p-6 border border-accent">
                <h3 class="text-xl font-bold mb-4">Para Çekme Geçmişi</h3>
                <div class="space-y-3">
                    @forelse(auth()->user()->withdrawals()->latest()->take(3)->get() as $withdrawal)
                        <div class="flex justify-between items-center pb-2 border-b border-accent">
                            <div>
                                <div class="font-medium">₺{{ number_format($withdrawal->amount, 2) }}</div>
                                <div class="text-xs text-gray-400">{{ ucfirst($withdrawal->method) }}</div>
                                <div class="text-xs text-gray-400">{{ $withdrawal->created_at->format('d.m.Y') }}</div>
                            </div>
                            <div class="text-xs px-2 py-1 rounded 
                                @if($withdrawal->status === 'completed') text-green-500 bg-green-500/20
                                @elseif($withdrawal->status === 'processing') text-yellow-500 bg-yellow-500/20
                                @elseif($withdrawal->status === 'failed') text-red-500 bg-red-500/20
                                @else text-blue-500 bg-blue-500/20 @endif">
                                {{ ucfirst($withdrawal->status) }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">Henüz çekim yapılmamış</p>
                    @endforelse
                </div>
                <a href="#" class="block text-center text-gold text-sm hover:text-yellow-500 mt-4">
                    Tümünü Gör <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Limits & Fees -->
            <div class="bg-secondary rounded-xl p-6 border border-accent">
                <h3 class="text-xl font-bold mb-4">Limitler & Ücretler</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span>Günlük Limit:</span>
                        <span>₺10.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Aylık Limit:</span>
                        <span>₺100.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Min. Çekim:</span>
                        <span>₺75</span>
                    </div>
                    <div class="flex justify-between">
                        <span>İşlem Ücreti:</span>
                        <span>%0-3</span>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="bg-secondary rounded-xl p-6 border border-accent text-center">
                <div class="text-gold text-3xl mb-3">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="font-bold mb-2">Yardım mı gerekiyor?</h3>
                <p class="text-sm text-gray-400 mb-4">7/24 destek hizmetimiz</p>
                <button class="bg-gold text-black px-4 py-2 rounded-lg font-medium hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-comments mr-2"></i>Canlı Destek
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
