@extends('layouts.app')

@section('title', 'Canlı Casino - BetGround')

@push('styles')
<style>
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(255, 215, 0, 0.1);
    }
    .game-card {
        position: relative;
        overflow: hidden;
    }
    .game-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .game-card:hover::before {
        opacity: 1;
    }
    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .game-card:hover .play-button {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-12">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-video mr-3"></i>Canlı Casino
            </h1>
            <p class="text-xl text-gray-300">Gerçek krupiyeler ile heyecan dolu casino deneyimi yaşayın</p>
        </div>
    </section>

    <!-- Live Casino Games -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8">Canlı Masalar</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @php
                $casinoGames = \App\Models\Game::where('category', 'casino')
                    ->where('is_active', true)
                    ->where('type', '!=', 'Game Show')
                    ->orderBy('order_index')
                    ->orderBy('is_featured', 'desc')
                    ->get();
                    
                $gameColors = [
                    'blackjack-vip' => 'from-green-900 to-green-700',
                    'turk-ruleti' => 'from-red-900 to-red-700',
                    'baccarat-salon' => 'from-purple-900 to-purple-700',
                    'casino-holdem' => 'from-blue-900 to-blue-700',
                ];
                
                $dealers = ['Sarah', 'Ayşe', 'Maria', 'John', 'Emily', 'Mehmet', 'Anna'];
            @endphp

            @foreach($casinoGames as $game)
                @php
                    $gradient = $gameColors[$game->slug] ?? 'from-gray-900 to-gray-700';
                    $dealer = $dealers[array_rand($dealers)];
                @endphp
                <div class="game-card bg-secondary rounded-xl overflow-hidden card-hover cursor-pointer" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun entegrasyonu yakında!')" @endguest>
                    <div class="relative h-48 bg-gradient-to-br {{ $gradient }}">
                        @if($game->thumbnail)
                            <img src="{{ $game->thumbnail_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover object-center">
                        @endif
                        
                        @if($game->is_live)
                            <div class="absolute top-4 left-4 bg-red-600 text-white px-2 py-1 rounded text-xs">
                                <i class="fas fa-circle text-red-400 animate-pulse mr-1"></i>CANLI
                            </div>
                        @endif
                        
                        @if($game->is_featured)
                            <div class="absolute top-4 right-4 bg-gold text-black px-2 py-1 rounded text-xs font-bold">
                                HOT!
                            </div>
                        @endif
                        
                        <div class="absolute bottom-4 left-4">
                            <h3 class="text-white font-bold text-lg">{{ $game->name }}</h3>
                            <p class="text-gray-200 text-sm">Masa Limiti: ₺{{ number_format($game->min_bet) }} - ₺{{ number_format($game->max_bet) }}</p>
                        </div>
                        <div class="play-button">
                            <button class="bg-gold text-black px-6 py-3 rounded-full font-bold hover:bg-yellow-500">
                                <i class="fas fa-play mr-2"></i>Oyna
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="https://via.placeholder.com/32x32" alt="Dealer" class="w-8 h-8 rounded-full mr-2">
                                <span class="text-sm">Dealer {{ $dealer }}</span>
                            </div>
                            <div class="text-gold font-bold">{{ rand(3, 15) }} Oyuncu</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Game Shows -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8">Oyun Şovları</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $gameShows = \App\Models\Game::where('category', 'casino')
                    ->where('type', 'Game Show')
                    ->where('is_active', true)
                    ->orderBy('order_index')
                    ->orderBy('is_featured', 'desc')
                    ->get();
                    
                $showColors = [
                    'crazy-time' => 'from-pink-900 to-pink-700',
                    'monopoly-live' => 'from-green-900 to-green-700',
                    'dream-catcher' => 'from-yellow-900 to-yellow-700',
                ];
            @endphp

            @foreach($gameShows as $show)
                @php
                    $gradient = $showColors[$show->slug] ?? 'from-purple-900 to-purple-700';
                @endphp
                <div class="game-card bg-secondary rounded-xl overflow-hidden card-hover cursor-pointer" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun entegrasyonu yakında!')" @endguest>
                    <div class="relative h-56 bg-gradient-to-br {{ $gradient }}">
                        @if($show->thumbnail)
                            <img src="{{ $show->thumbnail_url }}" alt="{{ $show->name }}" class="w-full h-full object-cover object-center">
                        @endif
                        
                        @if($show->is_live)
                            <div class="absolute top-4 left-4 bg-red-600 text-white px-2 py-1 rounded text-xs">
                                <i class="fas fa-circle text-red-400 animate-pulse mr-1"></i>CANLI
                            </div>
                        @endif
                        
                        @if($show->is_featured)
                            <div class="absolute top-4 right-4 bg-gold text-black px-2 py-1 rounded text-xs font-bold">
                                HOT!
                            </div>
                        @endif
                        
                        <div class="absolute bottom-4 left-4">
                            <h3 class="text-white font-bold text-xl">{{ $show->name }}</h3>
                            <p class="text-gray-200 text-sm">{{ $show->type ?? 'Oyun Şovu' }}</p>
                        </div>
                        <div class="play-button">
                            <button class="bg-gold text-black px-6 py-3 rounded-full font-bold hover:bg-yellow-500">
                                <i class="fas fa-play mr-2"></i>Oyna
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-300">Sonraki Tur: {{ rand(5, 60) }}sn</span>
                            <div class="text-gold font-bold">{{ rand(100, 400) }} Oyuncu</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Promotions Banner -->
    <section class="mb-12">
        <div class="bg-gradient-dark rounded-2xl p-8 text-center">
            <h2 class="text-3xl font-bold mb-4 text-glow">Canlı Casino Bonusu</h2>
            <p class="text-xl text-gray-300 mb-6">İlk yatırımınıza %100 bonus + 50 freespin</p>
            <a href="{{ route('promotions') }}" class="bg-gold text-black px-8 py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors">
                <i class="fas fa-gift mr-2"></i>Bonusu Al
            </a>
        </div>
    </section>

    <!-- Information Section -->
    <section>
        <div class="bg-secondary rounded-2xl p-8">
            <h2 class="text-3xl font-bold text-center mb-8">Canlı Casino Avantajları</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-accent rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-video text-gold text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">HD Görüntü Kalitesi</h3>
                    <p class="text-gray-300">Yüksek çözünürlüklü kameralar ile kristal netliğinde görüntü</p>
                </div>
                <div class="text-center">
                    <div class="bg-accent rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gold text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Profesyonel Krupiyeler</h3>
                    <p class="text-gray-300">Deneyimli ve dostane krupiyelerle gerçek casino atmosferi</p>
                </div>
                <div class="text-center">
                    <div class="bg-accent rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-gold text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">7/24 Açık</h3>
                    <p class="text-gray-300">İstediğiniz zaman oynayın, masalar her zaman açık</p>
                </div>
            </div>
        </div>
    </section>
</div>

@guest
<!-- Login Prompt Modal -->
<div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-secondary rounded-xl p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Giriş Gerekli</h3>
        <p class="text-gray-300 mb-6">Canlı casino oyunlarına katılmak için giriş yapmanız gerekiyor.</p>
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
@endsection
