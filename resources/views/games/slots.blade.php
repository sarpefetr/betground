@extends('layouts.app')

@section('title', 'Slot Oyunları - BetGround')

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
    .slot-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
    }
    .slot-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 215, 0, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .slot-card:hover::before {
        opacity: 1;
    }
    .jackpot-glow {
        animation: pulse 2s infinite;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-12">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-gamepad mr-3"></i>Slot Oyunları
            </h1>
            <p class="text-xl text-gray-300">Binlerce slot oyunu arasından favorinizi seçin</p>
        </div>
    </section>

    <!-- Jackpot Section -->
    <section class="mb-12">
        <div class="bg-gradient-dark rounded-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-4 jackpot-glow text-gold">🎰 MEGA JACKPOT 🎰</h2>
                <div class="text-5xl font-bold text-gold mb-4 jackpot-glow">₺{{ number_format(rand(2000000, 5000000), 0) }}</div>
                <p class="text-gray-300">Son kazanan: {{ ['Mehmet K.', 'Ayşe Y.', 'Ahmet T.', 'Fatma S.'][array_rand(['Mehmet K.', 'Ayşe Y.', 'Ahmet T.', 'Fatma S.'])] }} - ₺{{ number_format(rand(50000, 200000), 0) }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="slot-card bg-secondary p-4 rounded-xl card-hover cursor-pointer" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun entegrasyonu yakında!')" @endguest>
                    <div class="text-center">
                        <div class="text-gold text-3xl mb-2">💎</div>
                        <h3 class="font-bold mb-1">Mega Fortune</h3>
                        <div class="text-gold font-bold">₺{{ number_format(rand(500000, 1000000), 0) }}</div>
                    </div>
                </div>
                <div class="slot-card bg-secondary p-4 rounded-xl card-hover cursor-pointer" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun entegrasyonu yakında!')" @endguest>
                    <div class="text-center">
                        <div class="text-gold text-3xl mb-2">👑</div>
                        <h3 class="font-bold mb-1">Hall of Gods</h3>
                        <div class="text-gold font-bold">₺{{ number_format(rand(800000, 1500000), 0) }}</div>
                    </div>
                </div>
                <div class="slot-card bg-secondary p-4 rounded-xl card-hover cursor-pointer" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun entegrasyonu yakında!')" @endguest>
                    <div class="text-center">
                        <div class="text-gold text-3xl mb-2">⚡</div>
                        <h3 class="font-bold mb-1">Divine Fortune</h3>
                        <div class="text-gold font-bold">₺{{ number_format(rand(600000, 900000), 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="mb-8" x-data="{ selectedCategory: 'all' }">
        <div class="flex flex-wrap gap-4 justify-center">
            <button @click="selectedCategory = 'all'" 
                    :class="selectedCategory === 'all' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                Tümü
            </button>
            <button @click="selectedCategory = 'popular'" 
                    :class="selectedCategory === 'popular' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                Popüler
            </button>
            <button @click="selectedCategory = 'new'" 
                    :class="selectedCategory === 'new' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                Yeni
            </button>
            <button @click="selectedCategory = 'jackpot'" 
                    :class="selectedCategory === 'jackpot' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                Jackpot
            </button>
            <button @click="selectedCategory = 'classic'" 
                    :class="selectedCategory === 'classic' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                Klasik
            </button>
        </div>
    </section>

    <!-- Slot Games Grid -->
    <section>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @php
                $slots = \App\Models\Game::where('category', 'slots')
                    ->where('is_active', true)
                    ->orderBy('order_index')
                    ->orderBy('is_featured', 'desc')
                    ->get();
                
                // Fallback emoji/gradient mapping
                $gameStyles = [
                    'sweet-bonanza' => ['emoji' => '🍒', 'gradient' => 'from-purple-600 to-blue-600'],
                    'book-of-dead' => ['emoji' => '🎰', 'gradient' => 'from-green-600 to-emerald-600'],
                    'starburst' => ['emoji' => '💎', 'gradient' => 'from-red-600 to-pink-600'],
                    'wolf-gold' => ['emoji' => '🐺', 'gradient' => 'from-teal-600 to-cyan-600'],
                    'gates-of-olympus' => ['emoji' => '⚡', 'gradient' => 'from-indigo-600 to-purple-600'],
                    'big-bass-bonanza' => ['emoji' => '🐟', 'gradient' => 'from-blue-600 to-cyan-600'],
                ];
            @endphp

            @foreach($slots as $slot)
                @php
                    $style = $gameStyles[$slot->slug] ?? ['emoji' => '🎰', 'gradient' => 'from-gray-600 to-slate-600'];
                @endphp
                <div class="slot-card rounded-xl overflow-hidden card-hover cursor-pointer" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Oyun entegrasyonu yakında!')" @endguest>
                    <div class="relative h-32 bg-gradient-to-br {{ $style['gradient'] }} flex items-center justify-center">
                        @if($slot->thumbnail)
                            <img src="{{ $slot->thumbnail_url }}" alt="{{ $slot->name }}" class="w-full h-full object-cover object-center">
                        @else
                            <div class="text-4xl">{{ $style['emoji'] }}</div>
                        @endif
                        
                        @if($slot->is_featured)
                            <div class="absolute top-2 right-2 bg-gold text-black px-1 py-0.5 rounded text-xs font-bold">
                                HOT
                            </div>
                        @endif
                        
                        @if($slot->is_live)
                            <div class="absolute top-2 left-2 bg-red-600 text-white px-1 py-0.5 rounded text-xs font-bold">
                                LIVE
                            </div>
                        @endif
                    </div>
                    <div class="p-3 bg-secondary">
                        <h3 class="font-bold text-sm mb-1 truncate">{{ $slot->name }}</h3>
                        <div class="flex justify-between items-center text-xs text-gray-400">
                            <span>{{ $slot->provider ?? 'BetGround' }}</span>
                            <span class="text-gold">RTP: {{ $slot->rtp ? number_format($slot->rtp, 1) : '96.0' }}%</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-12">
            <button class="bg-gold text-black px-8 py-3 rounded-lg font-bold hover:bg-yellow-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>Daha Fazla Yükle
            </button>
        </div>
    </section>
</div>
@endsection
