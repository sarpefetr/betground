@extends('layouts.app')

@section('title', 'Promosyonlar - BetGround')

@push('styles')
<style>
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    }
    .bg-gradient-gold {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(255, 215, 0, 0.15);
    }
    .promo-card {
        position: relative;
        overflow: hidden;
    }
    .promo-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 215, 0, 0.1) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    .promo-card:hover::before {
        transform: translateX(100%);
    }
    .countdown {
        font-family: 'Courier New', monospace;
    }
    .hot-badge {
        animation: bounce 1s infinite alternate;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-12">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-gift mr-3"></i>Promosyonlar
            </h1>
            <p class="text-xl text-gray-300">Günlük bonuslar ve özel kampanyalarla kazancınızı artırın</p>
        </div>
    </section>

    <!-- Featured Promotion -->
    <section class="mb-12">
        <div class="promo-card bg-gradient-gold rounded-2xl p-8 text-black card-hover relative overflow-hidden">
            <div class="absolute top-4 right-4 hot-badge">
                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                    🔥 HOT
                </span>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="text-4xl font-bold mb-4">Hoş Geldin Bonusu</h2>
                    <p class="text-xl mb-6">İlk yatırımınıza <strong>%200 BONUS</strong> + <strong>100 Freespin</strong></p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i>Minimum yatırım: ₺50</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i>Maksimum bonus: ₺5.000</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i>Çevrim şartı: 35x</li>
                    </ul>
                    @guest
                        <a href="{{ route('register') }}" class="bg-black text-gold px-8 py-3 rounded-lg font-bold text-lg hover:bg-gray-800 transition-colors inline-block">
                            <i class="fas fa-rocket mr-2"></i>Hemen Al
                        </a>
                    @else
                        <a href="{{ route('deposit') }}" class="bg-black text-gold px-8 py-3 rounded-lg font-bold text-lg hover:bg-gray-800 transition-colors inline-block">
                            <i class="fas fa-rocket mr-2"></i>Para Yatır ve Al
                        </a>
                    @endguest
                </div>
                <div class="text-center">
                    <div class="text-6xl font-bold mb-4">₺5.000</div>
                    <div class="text-2xl">Bonus Şansı</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Active Promotions -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8">Aktif Kampanyalar</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $activeBonuses = \App\Models\Bonus::getActiveBonuses();
            @endphp

            @foreach($activeBonuses as $bonus)
                <div class="promo-card bg-secondary rounded-xl overflow-hidden card-hover relative">
                    <!-- Bonus Header with Background -->
                    <div class="relative bg-gradient-to-br 
                        @if($bonus->bonus_type === 'welcome') from-gold to-yellow-600
                        @elseif($bonus->bonus_type === 'daily') from-blue-600 to-purple-600
                        @elseif($bonus->bonus_type === 'weekly') from-green-600 to-emerald-600
                        @elseif($bonus->bonus_type === 'cashback') from-orange-600 to-red-600
                        @elseif($bonus->bonus_type === 'referral') from-teal-600 to-cyan-600
                        @elseif($bonus->bonus_type === 'vip') from-yellow-600 to-orange-600
                        @elseif($bonus->bonus_type === 'tournament') from-purple-600 to-pink-600
                        @else from-gray-600 to-slate-600 @endif p-6 text-center">
                        
                        @if($bonus->image)
                            <div class="absolute inset-0">
                                <img src="{{ $bonus->image_url }}" alt="{{ $bonus->name }}" class="w-full h-full object-cover object-center opacity-30">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            </div>
                        @endif

                        <div class="relative">
                            <div class="text-4xl mb-4">
                                @if($bonus->bonus_type === 'welcome') 🎉
                                @elseif($bonus->bonus_type === 'daily') 📅
                                @elseif($bonus->bonus_type === 'weekly') 🎊
                                @elseif($bonus->bonus_type === 'cashback') 💰
                                @elseif($bonus->bonus_type === 'referral') 🤝
                                @elseif($bonus->bonus_type === 'vip') 👑
                                @elseif($bonus->bonus_type === 'tournament') 🏆
                                @else 🎁 @endif
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-white">{{ $bonus->name }}</h3>
                            <div class="text-3xl font-bold text-gold">{{ $bonus->formatted_amount }}</div>
                        </div>

                        @if($bonus->is_featured)
                            <div class="absolute top-2 right-2">
                                <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold animate-pulse">
                                    🔥 HOT
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <p class="text-gray-300 mb-4">{{ $bonus->description }}</p>
                        <ul class="text-sm space-y-1 mb-4 text-gray-400">
                            <li>• Min. yatırım: ₺{{ number_format($bonus->min_deposit, 2) }}</li>
                            @if($bonus->max_bonus)
                                <li>• Maks. bonus: ₺{{ number_format($bonus->max_bonus, 2) }}</li>
                            @endif
                            <li>• Çevrim şartı: {{ $bonus->wagering_requirement }}x</li>
                            @if($bonus->valid_until)
                                <li>• Geçerlilik: {{ $bonus->valid_until->format('d.m.Y') }}'e kadar</li>
                            @endif
                        </ul>
                        
                        @if($bonus->valid_until && $bonus->valid_until->isFuture())
                            <div class="countdown text-center text-gold font-bold mb-4" x-data="{ 
                                targetDate: new Date('{{ $bonus->valid_until->toISOString() }}'),
                                now: new Date(),
                                timeLeft: ''
                            }" x-init="
                                function updateCountdown() {
                                    const diff = targetDate - new Date();
                                    if (diff > 0) {
                                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                        timeLeft = days > 0 ? days + ' gün ' + hours + 'sa' : hours + 'sa ' + minutes + 'dk';
                                    } else {
                                        timeLeft = 'Süresi Doldu';
                                    }
                                }
                                updateCountdown();
                                setInterval(updateCountdown, 60000);
                            ">
                                Kalan Süre: <span x-text="timeLeft"></span>
                            </div>
                        @endif
                        
                        @guest
                            <a href="{{ route('login') }}" class="w-full bg-gold text-black py-2 rounded-lg font-medium hover:bg-yellow-500 transition-colors block text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Giriş Yap ve Al
                            </a>
                        @else
                            @if($bonus->hasUserClaimed(auth()->id()))
                                @php
                                    $userClaim = $bonus->claims()->where('user_id', auth()->id())->first();
                                @endphp
                                <div class="text-center">
                                    <div class="w-full py-2 rounded-lg font-medium mb-2 
                                        @if($userClaim->status === 'approved') bg-green-600 text-white
                                        @elseif($userClaim->status === 'rejected') bg-red-600 text-white
                                        @else bg-yellow-600 text-black @endif">
                                        @if($userClaim->status === 'approved') ✅ Onaylandı
                                        @elseif($userClaim->status === 'rejected') ❌ Reddedildi
                                        @else ⏳ Bekliyor @endif
                                    </div>
                                    @if($userClaim->admin_message)
                                        <p class="text-xs text-gray-400">{{ $userClaim->admin_message }}</p>
                                    @endif
                                </div>
                            @else
                                <button onclick="claimBonus({{ $bonus->id }}, '{{ $bonus->name }}', '{{ $bonus->formatted_amount }}', {{ $bonus->min_deposit }})" 
                                        class="w-full bg-gold text-black py-2 rounded-lg font-medium hover:bg-yellow-500 transition-colors">
                                    <i class="fas fa-hand-holding-heart mr-2"></i>Talep Et
                                </button>
                            @endif
                        @endguest
                    </div>
                </div>
            @endforeach

            @if($activeBonuses->isEmpty())
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 text-4xl mb-4">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Şu anda aktif bonus bulunmuyor</h3>
                    <p class="text-gray-400">Yakında yeni bonuslar eklenecek!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Promotion Terms -->
    <section class="mb-12">
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-2xl font-bold mb-6">Genel Şartlar ve Koşullar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-300">
                <div>
                    <h3 class="font-bold text-white mb-3">Bonus Kuralları</h3>
                    <ul class="space-y-2">
                        <li>• Bonuslar sadece kayıtlı üyelere verilir</li>
                        <li>• Her promosyon için çevrim şartları farklıdır</li>
                        <li>• Bonus kullanımı için minimum yatırım gereklidir</li>
                        <li>• Bonuslar belirtilen süre içinde kullanılmalıdır</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-3">Genel Koşullar</h3>
                    <ul class="space-y-2">
                        <li>• BetGround promosyon şartlarını değiştirme hakkını saklı tutar</li>
                        <li>• Suistimal durumunda hesap kapatılabilir</li>
                        <li>• Bir kişi sadece bir hesap açabilir</li>
                        <li>• Promosyonlar ülke sınırlamalarına tabidir</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section>
        <div class="bg-gradient-dark rounded-2xl p-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Özel Promosyonlardan Haberdar Olun</h2>
            <p class="text-xl text-gray-300 mb-6">E-posta listemize katılın, özel bonuslardan ilk siz haberdar olun</p>
            <form class="flex flex-col md:flex-row gap-4 max-w-md mx-auto">
                @csrf
                <input type="email" 
                       name="email" 
                       placeholder="E-posta adresiniz" 
                       @auth value="{{ auth()->user()->email }}" @endauth
                       class="flex-1 bg-secondary border border-accent text-white px-4 py-3 rounded-lg focus:outline-none focus:border-gold">
                <button type="submit" class="bg-gold text-black px-6 py-3 rounded-lg font-bold hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>Abone Ol
                </button>
            </form>
        </div>
    </section>
</div>

<!-- Bonus Claim Modal -->
@auth
<div id="bonus-claim-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-secondary rounded-xl p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4 text-gold">Bonus Talebi</h3>
        <form id="bonus-claim-form" method="POST">
            @csrf
            <div id="bonus-details" class="mb-4 text-sm text-gray-300"></div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Ek Mesaj (Opsiyonel)</label>
                <textarea name="message" 
                          rows="3"
                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                          placeholder="Bonus talebiniz hakkında ek bilgi..."></textarea>
            </div>
            
            <div class="bg-yellow-600 text-black p-3 rounded-lg text-sm mb-4">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Not:</strong> Bonus talebiniz admin onayına gönderilecek. Onay sonrası bonus bakiyenize eklenecektir.
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gold text-black py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>Talep Gönder
                </button>
                <button type="button" onclick="closeBonusModal()" class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

@guest
<!-- Login Prompt for Promotions -->
<div id="promo-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-secondary rounded-xl p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Giriş Gerekli</h3>
        <p class="text-gray-300 mb-6">Promosyonlardan faydalanmak için giriş yapmanız gerekiyor.</p>
        <div class="flex gap-4">
            <a href="{{ route('login') }}" class="bg-gold text-black px-6 py-2 rounded-lg hover:bg-yellow-500 transition-colors flex-1 text-center">
                Giriş Yap
            </a>
            <a href="{{ route('register') }}" class="border border-gold text-gold px-6 py-2 rounded-lg hover:bg-gold hover:text-black transition-colors flex-1 text-center">
                Kayıt Ol
            </a>
        </div>
    </div>
</div>
@endguest

@push('scripts')
<script>
@auth
function claimBonus(bonusId, bonusName, bonusAmount, minDeposit) {
    document.getElementById('bonus-claim-form').action = `/bonuses/${bonusId}/claim`;
    document.getElementById('bonus-details').innerHTML = `
        <strong>Bonus:</strong> ${bonusName}<br>
        <strong>Bonus Oranı:</strong> ${bonusAmount}<br>
        <strong>Minimum Yatırım:</strong> ₺${minDeposit}<br><br>
        <strong>Mevcut Bakiyeniz:</strong> ₺{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}
    `;
    document.getElementById('bonus-claim-modal').classList.remove('hidden');
    document.getElementById('bonus-claim-modal').classList.add('flex');
}

function closeBonusModal() {
    document.getElementById('bonus-claim-modal').classList.add('hidden');
    document.getElementById('bonus-claim-modal').classList.remove('flex');
}
@endauth
</script>
@endpush
@endsection
