@extends('layouts.app')

@section('title', 'Supernovabet - En İyi Bahis Deneyimi')

@push('styles')
<style>
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    }
    .text-glow {
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(255, 215, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <section class="mb-12">
        <div class="bg-gradient-dark rounded-2xl p-8 text-center">
            <h1 class="text-5xl font-bold mb-4 text-glow">Supernovabet'e Hoş Geldiniz</h1>
            <p class="text-xl text-gray-300 mb-8">En iyi bahis deneyimi için doğru adrestesiniz</p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}" class="bg-gold text-black px-8 py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>Hemen Kayıt Ol
                    </a>
                    <a href="{{ route('live-casino') }}" class="border-2 border-gold text-gold px-8 py-3 rounded-lg font-bold text-lg hover:bg-gold hover:text-black transition-colors">
                        <i class="fas fa-play mr-2"></i>Canlı Casino
                    </a>
                @else
                    <a href="{{ route('deposit') }}" class="bg-gold text-black px-8 py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>Para Yatır
                    </a>
                    <a href="{{ route('live-casino') }}" class="border-2 border-gold text-gold px-8 py-3 rounded-lg font-bold text-lg hover:bg-gold hover:text-black transition-colors">
                        <i class="fas fa-play mr-2"></i>Canlı Casino
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Quick Access Cards -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">Popüler Bölümler</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('live-casino') }}" class="bg-secondary p-6 rounded-xl card-hover cursor-pointer">
                <div class="text-gold text-4xl mb-4 text-center">
                    <i class="fas fa-video"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-center">Canlı Casino</h3>
                <p class="text-gray-300 text-center">Gerçek krupiyeler ile oynayın</p>
            </a>
            
            <a href="{{ route('sports-betting') }}" class="bg-secondary p-6 rounded-xl card-hover cursor-pointer">
                <div class="text-gold text-4xl mb-4 text-center">
                    <i class="fas fa-futbol"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-center">Canlı Bahis</h3>
                <p class="text-gray-300 text-center">Maçları canlı izleyip bahis yapın</p>
            </a>
            
            <a href="{{ route('slots') }}" class="bg-secondary p-6 rounded-xl card-hover cursor-pointer">
                <div class="text-gold text-4xl mb-4 text-center">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-center">Slot Oyunları</h3>
                <p class="text-gray-300 text-center">Binlerce slot oyunu</p>
            </a>
            
            <a href="{{ route('promotions') }}" class="bg-secondary p-6 rounded-xl card-hover cursor-pointer">
                <div class="text-gold text-4xl mb-4 text-center">
                    <i class="fas fa-gift"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-center">Promosyonlar</h3>
                <p class="text-gray-300 text-center">Günlük bonuslar ve kampanyalar</p>
            </a>
        </div>
    </section>

    <!-- Live Matches Section -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">Canlı Maçlar</h2>
        <div class="bg-secondary rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-accent p-4 rounded-lg flex justify-between items-center">
                    <div>
                        <div class="flex items-center mb-2">
                            <img src="https://via.placeholder.com/24x24" alt="Team 1" class="mr-2 rounded">
                            <span>Manchester United</span>
                        </div>
                        <div class="flex items-center">
                            <img src="https://via.placeholder.com/24x24" alt="Team 2" class="mr-2 rounded">
                            <span>Liverpool</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-gold font-bold text-lg">2-1</div>
                        <div class="text-sm text-gray-400">67'</div>
                    </div>
                    <button class="bg-gold text-black px-4 py-2 rounded ml-4 hover:bg-yellow-500">
                        Bahis Yap
                    </button>
                </div>
                
                <div class="bg-accent p-4 rounded-lg flex justify-between items-center">
                    <div>
                        <div class="flex items-center mb-2">
                            <img src="https://via.placeholder.com/24x24" alt="Team 1" class="mr-2 rounded">
                            <span>Barcelona</span>
                        </div>
                        <div class="flex items-center">
                            <img src="https://via.placeholder.com/24x24" alt="Team 2" class="mr-2 rounded">
                            <span>Real Madrid</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-gold font-bold text-lg">1-0</div>
                        <div class="text-sm text-gray-400">45'</div>
                    </div>
                    <button class="bg-gold text-black px-4 py-2 rounded ml-4 hover:bg-yellow-500">
                        Bahis Yap
                    </button>
                </div>
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('sports-betting') }}" class="text-gold hover:text-yellow-500 font-medium">
                    <i class="fas fa-arrow-right mr-2"></i>Tüm Canlı Maçları Gör
                </a>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="mb-12">
        <div class="bg-gradient-dark rounded-2xl p-8">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                @auth
                    @php
                        $userWallet = \App\Models\Wallet::where('user_id', auth()->id())->first();
                    @endphp
                    <div>
                        <div class="text-4xl font-bold text-gold">₺{{ number_format($userWallet->balance ?? 0, 2) }}</div>
                        <div class="text-gray-300 mt-2">Ana Bakiye</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-blue-400">₺{{ number_format($userWallet->bonus_balance ?? 0, 2) }}</div>
                        <div class="text-gray-300 mt-2">Bonus Bakiye</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-green-400">₺{{ number_format(($userWallet->balance ?? 0) + ($userWallet->bonus_balance ?? 0), 2) }}</div>
                        <div class="text-gray-300 mt-2">Toplam Bakiye</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-purple-400">{{ auth()->user()->bonusClaims()->where('status', 'approved')->count() }}</div>
                        <div class="text-gray-300 mt-2">Aldığınız Bonus</div>
                    </div>
                @else
                    <div>
                        <div class="text-4xl font-bold text-gold">{{ number_format(rand(1000, 5000)) }}+</div>
                        <div class="text-gray-300 mt-2">Aktif Kullanıcı</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gold">{{ number_format(rand(100000, 500000)) }}</div>
                        <div class="text-gray-300 mt-2">Günlük Bahis</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gold">%98.5</div>
                        <div class="text-gray-300 mt-2">Ödeme Oranı</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gold">24/7</div>
                        <div class="text-gray-300 mt-2">Canlı Destek</div>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section>
        <h2 class="text-3xl font-bold mb-8 text-center">Neden Supernovabet?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-secondary rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-gold text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Güvenli Ödeme</h3>
                <p class="text-gray-300">SSL şifreleme ve güvenli ödeme sistemleri ile bilgileriniz güvende</p>
            </div>
            <div class="text-center">
                <div class="bg-secondary rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bolt text-gold text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Hızlı İşlemler</h3>
                <p class="text-gray-300">Anında para yatırma ve hızlı para çekme işlemleri</p>
            </div>
            <div class="text-center">
                <div class="bg-secondary rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-gold text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">7/24 Destek</h3>
                <p class="text-gray-300">Her zaman yanınızdayız, 7/24 canlı destek hizmeti</p>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Sayfa yüklendiğinde hata kontrolü
document.addEventListener('DOMContentLoaded', function() {
    // Eğer body'de JSON varsa temizle
    const bodyText = document.body.innerText;
    if (bodyText.includes('{"success":true') && bodyText.includes('"betSlip"')) {
        console.error('JSON data leaked to page body, reloading...');
        // Sayfayı temizle ve yeniden yükle
        document.body.innerHTML = '';
        window.location.reload();
    }
});
</script>
@endpush
