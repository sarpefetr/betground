@extends('layouts.app')

@section('title', 'Oyunlar - BetGround')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-12">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-puzzle-piece mr-3"></i>Oyunlar
            </h1>
            <p class="text-xl text-gray-300">Tüm oyun kategorilerini keşfedin</p>
        </div>
    </section>

    <!-- Game Categories -->
    <section class="mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <a href="{{ route('live-casino') }}" class="bg-secondary p-8 rounded-xl card-hover cursor-pointer group">
                <div class="text-gold text-5xl mb-4 text-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-video"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 text-center">Canlı Casino</h3>
                <p class="text-gray-300 text-center mb-4">Gerçek krupiyeler ile casino oyunları</p>
                <div class="text-center text-gold font-bold">{{ rand(50, 100) }}+ Masa</div>
            </a>
            
            <a href="{{ route('slots') }}" class="bg-secondary p-8 rounded-xl card-hover cursor-pointer group">
                <div class="text-gold text-5xl mb-4 text-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 text-center">Slot Oyunları</h3>
                <p class="text-gray-300 text-center mb-4">Binlerce slot oyunu ve jackpot</p>
                <div class="text-center text-gold font-bold">{{ rand(3000, 5000) }}+ Oyun</div>
            </a>
            
            <a href="{{ route('sports-betting') }}" class="bg-secondary p-8 rounded-xl card-hover cursor-pointer group">
                <div class="text-gold text-5xl mb-4 text-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-futbol"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 text-center">Spor Bahisleri</h3>
                <p class="text-gray-300 text-center mb-4">Canlı maçlar ve pre-match bahisleri</p>
                <div class="text-center text-gold font-bold">{{ rand(15, 35) }} Canlı Maç</div>
            </a>
            
            <a href="{{ route('esports') }}" class="bg-secondary p-8 rounded-xl card-hover cursor-pointer group">
                <div class="text-gold text-5xl mb-4 text-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-desktop"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 text-center">E-Sporlar</h3>
                <p class="text-gray-300 text-center mb-4">CS:GO, LoL, Dota2 ve daha fazlası</p>
                <div class="text-center text-gold font-bold">{{ rand(10, 25) }} Maç</div>
            </a>
            
            <a href="{{ route('virtual-sports') }}" class="bg-secondary p-8 rounded-xl card-hover cursor-pointer group">
                <div class="text-gold text-5xl mb-4 text-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-robot"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 text-center">Sanal Sporlar</h3>
                <p class="text-gray-300 text-center mb-4">7/24 sanal maçlar ve yarışlar</p>
                <div class="text-center text-gold font-bold">Sürekli Aktif</div>
            </a>
            
            <div class="bg-gradient-dark p-8 rounded-xl text-center">
                <div class="text-gold text-5xl mb-4">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Yakında</h3>
                <p class="text-gray-300 mb-4">Yeni oyun kategorileri geliyor</p>
                <div class="text-center text-gray-400">Çok Yakında</div>
            </div>
        </div>
    </section>

    <!-- Popular Games -->
    <section>
        <h2 class="text-3xl font-bold mb-8 text-center">Popüler Oyunlar</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-secondary rounded-xl p-6 text-center card-hover">
                <div class="text-4xl mb-4">🎰</div>
                <h3 class="font-bold text-lg mb-2">Sweet Bonanza</h3>
                <p class="text-sm text-gray-400 mb-4">Pragmatic Play</p>
                <button class="bg-gold text-black px-4 py-2 rounded font-medium hover:bg-yellow-500 transition-colors" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun yakında!')" @endguest>
                    Oyna
                </button>
            </div>
            
            <div class="bg-secondary rounded-xl p-6 text-center card-hover">
                <div class="text-4xl mb-4">🃏</div>
                <h3 class="font-bold text-lg mb-2">Blackjack VIP</h3>
                <p class="text-sm text-gray-400 mb-4">Evolution Gaming</p>
                <button class="bg-gold text-black px-4 py-2 rounded font-medium hover:bg-yellow-500 transition-colors" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun yakında!')" @endguest>
                    Oyna
                </button>
            </div>
            
            <div class="bg-secondary rounded-xl p-6 text-center card-hover">
                <div class="text-4xl mb-4">⚽</div>
                <h3 class="font-bold text-lg mb-2">Futbol Bahisleri</h3>
                <p class="text-sm text-gray-400 mb-4">{{ rand(15, 30) }} Canlı Maç</p>
                <button class="bg-gold text-black px-4 py-2 rounded font-medium hover:bg-yellow-500 transition-colors" @guest onclick="window.location='{{ route('login') }}'" @else onclick="window.location='{{ route('sports-betting') }}'" @endguest>
                    Bahis Yap
                </button>
            </div>
            
            <div class="bg-secondary rounded-xl p-6 text-center card-hover">
                <div class="text-4xl mb-4">🎮</div>
                <h3 class="font-bold text-lg mb-2">CS:GO Turnuva</h3>
                <p class="text-sm text-gray-400 mb-4">Major Şampiyonası</p>
                <button class="bg-gold text-black px-4 py-2 rounded font-medium hover:bg-yellow-500 transition-colors" @guest onclick="window.location='{{ route('login') }}'" @else onclick="window.location='{{ route('esports') }}'" @endguest>
                    İzle
                </button>
            </div>
        </div>
    </section>
</div>
@endsection



