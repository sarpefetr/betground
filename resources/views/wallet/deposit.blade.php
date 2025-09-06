@extends('layouts.app')

@section('title', 'Para Yatır - BetGround')

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
    .payment-method {
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .payment-method.selected {
        border-color: #ffd700;
        background: rgba(255, 215, 0, 0.1);
    }
    .deposit-card {
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
                <i class="fas fa-credit-card mr-3"></i>Para Yatır
            </h1>
            <p class="text-xl text-gray-300">Hesabınıza hızlı ve güvenli para yatırın</p>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Payment Methods -->
        <div class="lg:col-span-2">
            <div class="deposit-card rounded-2xl p-6 border border-accent">
                <h2 class="text-2xl font-bold mb-6">Ödeme Yöntemi Seçin</h2>
                
                @php
                    $paymentMethods = \App\Models\PaymentMethod::where('type', 'method')
                        ->where('is_active', true)
                        ->with('parent')
                        ->orderBy('order_index')
                        ->get();
                @endphp

                <form method="POST" action="{{ route('deposit') }}" x-data="{ selectedMethod: '{{ $paymentMethods->first()->slug ?? 'xpay' }}' }">
                    @csrf
                    <div class="space-y-4">
                        @foreach($paymentMethods->groupBy('parent.name') as $categoryName => $methods)
                            <div class="mb-6">
                                @if($categoryName && $categoryName !== '')
                                    <h4 class="text-lg font-bold mb-3 text-gold">
                                        <i class="fas fa-folder mr-2"></i>{{ $categoryName }}
                                    </h4>
                                @endif
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($methods as $method)
                                        <a href="{{ route('payment.method', $method->slug) }}" 
                                           class="payment-method bg-secondary rounded-xl overflow-hidden cursor-pointer card-hover relative block">
                                            
                                            <!-- Background Image -->
                                            @if($method->image)
                                                <div class="absolute inset-0">
                                                    <img src="{{ $method->image_url }}" alt="{{ $method->name }}" class="w-full h-full object-cover object-center">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>
                                                </div>
                                            @endif
                                            
                                            <div class="relative p-4">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="text-gold text-2xl">
                                                        @if($method->method_code === 'bank_transfer') <i class="fas fa-university"></i>
                                                        @elseif($method->method_code === 'credit_card') <i class="fas fa-credit-card"></i>
                                                        @elseif($method->method_code === 'crypto') <i class="fab fa-bitcoin"></i>
                                                        @elseif($method->method_code === 'ewallet') <i class="fas fa-wallet"></i>
                                                        @elseif($method->method_code === 'mobile') <i class="fas fa-mobile-alt"></i>
                                                        @elseif($method->method_code === 'atm') <i class="fas fa-credit-card"></i>
                                                        @else <i class="fas fa-credit-card"></i> @endif
                                                    </div>
                                                    
                                                    <div class="flex items-center space-x-1">
                                                        @if($method->processing_time)
                                                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-bold">{{ $method->processing_time }}</span>
                                                        @endif
                                                        @if($method->is_featured)
                                                            <span class="bg-gold text-black px-2 py-1 rounded text-xs font-bold">HOT</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Method Name at Bottom -->
                                            <div class="bg-black bg-opacity-50 p-3 text-center">
                                                <h3 class="font-bold text-lg text-white">{{ $method->name }}</h3>
                                                <div class="text-sm text-gray-300">{{ $method->amount_range }} | {{ $method->commission_display }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Hidden input for selected method -->
                        <input type="hidden" name="method" :value="selectedMethod">

                        <!-- Deposit Form -->
                        <div x-show="selectedMethod" x-transition class="mt-8 p-6 bg-accent rounded-xl">
                            <h3 class="text-xl font-bold mb-4">Yatırım Detayları</h3>
                            
                            <!-- Amount -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium mb-2">Yatırım Tutarı</label>
                                <div class="relative">
                                    <input type="number" 
                                           name="amount"
                                           placeholder="0"
                                           min="50"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white text-xl font-bold text-center focus:outline-none input-focus transition-all">
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gold font-bold">₺</div>
                                </div>
                                
                                <!-- Quick amounts -->
                                <div class="grid grid-cols-4 gap-2 mt-3">
                                    <button type="button" onclick="document.querySelector('input[name=amount]').value = 100" class="bg-secondary border border-gray-600 rounded-lg py-2 text-sm hover:border-gold transition-colors">₺100</button>
                                    <button type="button" onclick="document.querySelector('input[name=amount]').value = 250" class="bg-secondary border border-gray-600 rounded-lg py-2 text-sm hover:border-gold transition-colors">₺250</button>
                                    <button type="button" onclick="document.querySelector('input[name=amount]').value = 500" class="bg-secondary border border-gray-600 rounded-lg py-2 text-sm hover:border-gold transition-colors">₺500</button>
                                    <button type="button" onclick="document.querySelector('input[name=amount]').value = 1000" class="bg-secondary border border-gray-600 rounded-lg py-2 text-sm hover:border-gold transition-colors">₺1000</button>
                                </div>
                            </div>

                            <!-- Credit Card Form -->
                            <div x-show="selectedMethod === 'credit-card'" x-transition class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Kart Numarası</label>
                                    <input type="text" 
                                           name="card_number"
                                           placeholder="1234 5678 9012 3456"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Son Kullanım</label>
                                        <input type="text" 
                                               name="expiry_date"
                                               placeholder="MM/YY"
                                               class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">CVV</label>
                                        <input type="text" 
                                               name="cvv"
                                               placeholder="123"
                                               class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Kart Sahibi</label>
                                    <input type="text" 
                                           name="card_holder"
                                           placeholder="Kartın üzerindeki isim"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                </div>
                            </div>

                            <!-- Bank Transfer Info -->
                            <div x-show="selectedMethod === 'bank-transfer'" x-transition class="space-y-4">
                                <div class="bg-secondary p-4 rounded-lg">
                                    <h4 class="font-bold mb-2">Banka Bilgileri</h4>
                                    <div class="space-y-2 text-sm">
                                        <div>Banka: <span class="text-gold">Türkiye İş Bankası</span></div>
                                        <div>Şube: <span class="text-gold">Kadıköy Şubesi (1234)</span></div>
                                        <div>Hesap No: <span class="text-gold">1234567890</span></div>
                                        <div>IBAN: <span class="text-gold">TR12 0006 4000 0011 2345 6789 01</span></div>
                                        <div>Hesap Adı: <span class="text-gold">BetGround Ltd.</span></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Açıklama (Kullanıcı adınızı yazın)</label>
                                    <input type="text" 
                                           name="description"
                                           value="{{ auth()->user()->name }}"
                                           placeholder="Kullanıcı adınız"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gold text-black py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors mt-6">
                                <i class="fas fa-credit-card mr-2"></i>
                                <span x-text="selectedMethod === 'bank-transfer' ? 'Bilgileri Kaydet' : 'Para Yatır'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Balance -->
            <div class="bg-secondary rounded-xl p-6 border border-accent">
                <h3 class="text-xl font-bold mb-4">Güncel Bakiye</h3>
                @php
                    $userWallet = \App\Models\Wallet::where('user_id', auth()->id())->first();
                @endphp
                <div class="text-center space-y-3">
                    <div class="p-4 bg-accent rounded-lg">
                        <div class="text-3xl font-bold text-gold">₺{{ number_format($userWallet->balance ?? 0, 2) }}</div>
                        <div class="text-sm text-gray-400">Ana Bakiye</div>
                    </div>
                    
                    @if($userWallet && $userWallet->bonus_balance > 0)
                        <div class="p-4 bg-blue-900 bg-opacity-30 rounded-lg">
                            <div class="text-2xl font-bold text-blue-400">₺{{ number_format($userWallet->bonus_balance, 2) }}</div>
                            <div class="text-sm text-gray-400">Bonus Bakiye</div>
                        </div>
                    @endif
                    
                    <div class="p-4 bg-green-900 bg-opacity-30 rounded-lg">
                        <div class="text-2xl font-bold text-green-400">₺{{ number_format(($userWallet->balance ?? 0) + ($userWallet->bonus_balance ?? 0), 2) }}</div>
                        <div class="text-sm text-gray-400">Toplam Bakiye</div>
                    </div>
                </div>
            </div>

            <!-- Deposit Bonus -->
            <div class="bg-gradient-dark rounded-xl p-6 border border-gold">
                <div class="text-center">
                    <div class="text-gold text-3xl mb-2">🎁</div>
                    <h3 class="text-xl font-bold mb-2">Yatırım Bonusu</h3>
                    <div class="text-2xl font-bold text-gold mb-2">%100</div>
                    <p class="text-sm text-gray-300 mb-4">₺500'e kadar bonus</p>
                    <div class="bg-gold text-black px-3 py-1 rounded text-xs font-bold">
                        AKTİF
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-secondary rounded-xl p-6 border border-accent">
                <h3 class="text-xl font-bold mb-4">Son İşlemler</h3>
                <div class="space-y-3">
                    @forelse(auth()->user()->transactions()->latest()->take(3)->get() as $transaction)
                        <div class="flex justify-between items-center pb-2 border-b border-accent">
                            <div>
                                <div class="font-medium">{{ ucfirst($transaction->type) }}</div>
                                <div class="text-xs text-gray-400">{{ $transaction->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="font-bold @if($transaction->type === 'deposit') text-green-500 @else text-red-500 @endif">
                                @if($transaction->type === 'deposit')+@else-@endif₺{{ number_format($transaction->amount, 2) }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">Henüz işlem yapılmamış</p>
                    @endforelse
                </div>
                <a href="#" class="block text-center text-gold text-sm hover:text-yellow-500 mt-4">
                    Tümünü Gör <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Support -->
            <div class="bg-secondary rounded-xl p-6 border border-accent text-center">
                <div class="text-gold text-3xl mb-3">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="font-bold mb-2">Yardıma mı ihtiyacınız var?</h3>
                <p class="text-sm text-gray-400 mb-4">7/24 canlı destek hizmetimiz</p>
                <button class="bg-gold text-black px-4 py-2 rounded-lg font-medium hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-comments mr-2"></i>Canlı Destek
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
