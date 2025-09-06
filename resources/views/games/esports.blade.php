@extends('layouts.app')

@section('title', 'E-Sporlar - BetGround')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-12">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-desktop mr-3"></i>E-Sporlar
            </h1>
            <p class="text-xl text-gray-300">En popüler e-spor oyunlarına bahis yapın</p>
        </div>
    </section>

    <!-- Game Categories -->
    <section class="mb-8" x-data="{ selectedGame: 'csgo' }">
        <div class="flex flex-wrap gap-4 justify-center mb-8">
            <button @click="selectedGame = 'csgo'" 
                    :class="selectedGame === 'csgo' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-crosshairs mr-2"></i>CS:GO
            </button>
            <button @click="selectedGame = 'lol'" 
                    :class="selectedGame === 'lol' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-sword mr-2"></i>League of Legends
            </button>
            <button @click="selectedGame = 'dota2'" 
                    :class="selectedGame === 'dota2' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-shield-alt mr-2"></i>Dota 2
            </button>
            <button @click="selectedGame = 'valorant'" 
                    :class="selectedGame === 'valorant' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-bullseye mr-2"></i>Valorant
            </button>
        </div>

        <!-- Live E-Sports Matches -->
        <div class="space-y-4">
            @php
                $esportsMatches = \App\Models\Game::where('category', 'esports')
                    ->where('is_active', true)
                    ->orderBy('is_live', 'desc')
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @forelse($esportsMatches as $match)
                @php
                    $esportsData = json_decode($match->bet_data, true) ?? [];
                    $team1Name = $esportsData['team1_name'] ?? 'Team 1';
                    $team2Name = $esportsData['team2_name'] ?? 'Team 2';
                    $tournamentName = $esportsData['tournament_name'] ?? $match->provider;
                @endphp
                
                <div class="bg-secondary rounded-xl overflow-hidden card-hover relative">
                    <!-- Background Image -->
                    @if($match->thumbnail)
                        <div class="absolute inset-0">
                            <img src="{{ $match->thumbnail_url }}" alt="{{ $match->name }}" class="w-full h-full object-cover object-center opacity-20">
                            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/80 to-black/60"></div>
                        </div>
                    @endif
                    
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                @if($match->is_live)
                                    <span class="bg-red-600 text-white px-2 py-1 rounded text-xs mr-3">
                                        <i class="fas fa-circle text-red-400 animate-pulse mr-1"></i>CANLI
                                    </span>
                                @else
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs mr-3">
                                        <i class="fas fa-clock mr-1"></i>{{ isset($esportsData['match_date']) ? \Carbon\Carbon::parse($esportsData['match_date'])->format('H:i') : 'YAKINDA' }}
                                    </span>
                                @endif
                                <div>
                                    <h3 class="font-bold text-lg text-white">{{ $match->type }} - {{ $tournamentName }}</h3>
                                    <p class="text-sm text-gray-300">{{ $match->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($match->is_live)
                                    <div class="text-gold font-bold text-xl">{{ rand(0, 2) }} - {{ rand(0, 2) }}</div>
                                    <p class="text-sm text-gray-300">Map {{ rand(1, 3) }}/3</p>
                                @else
                                    <div class="text-gray-300 text-lg">vs</div>
                                    @if(isset($esportsData['match_date']))
                                        <p class="text-sm text-gray-300">{{ \Carbon\Carbon::parse($esportsData['match_date'])->format('d.m.Y') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-black bg-opacity-50 rounded-lg backdrop-blur-sm">
                                <div class="flex items-center">
                                    @if(isset($esportsData['team1_logo']) && $esportsData['team1_logo'])
                                        <img src="{{ asset($esportsData['team1_logo']) }}" 
                                             alt="{{ $team1Name }}" 
                                             class="w-10 h-10 mr-3 rounded-lg object-cover object-center border border-gray-600"
                                             onError="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-10 h-10 bg-blue-600 rounded-lg mr-3 hidden items-center justify-center text-xs font-bold text-white">{{ substr($team1Name, 0, 2) }}</div>
                                    @else
                                        <div class="w-10 h-10 bg-blue-600 rounded-lg mr-3 flex items-center justify-center text-xs font-bold text-white">{{ substr($team1Name, 0, 2) }}</div>
                                    @endif
                                    <div>
                                        <span class="font-bold text-white">{{ $team1Name }}</span>
                                        <div class="text-xs text-gray-300">{{ $match->type }}</div>
                                    </div>
                                </div>
                                <span class="text-gold font-bold text-xl">{{ $match->is_live ? rand(0, 2) : '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-black bg-opacity-50 rounded-lg backdrop-blur-sm">
                                <div class="flex items-center">
                                    @if(isset($esportsData['team2_logo']) && $esportsData['team2_logo'])
                                        <img src="{{ asset($esportsData['team2_logo']) }}" 
                                             alt="{{ $team2Name }}" 
                                             class="w-10 h-10 mr-3 rounded-lg object-cover object-center border border-gray-600"
                                             onError="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-10 h-10 bg-red-600 rounded-lg mr-3 hidden items-center justify-center text-xs font-bold text-white">{{ substr($team2Name, 0, 2) }}</div>
                                    @else
                                        <div class="w-10 h-10 bg-red-600 rounded-lg mr-3 flex items-center justify-center text-xs font-bold text-white">{{ substr($team2Name, 0, 2) }}</div>
                                    @endif
                                    <div>
                                        <span class="font-bold text-white">{{ $team2Name }}</span>
                                        <div class="text-xs text-gray-300">{{ $match->type }}</div>
                                    </div>
                                </div>
                                <span class="text-gold font-bold text-xl">{{ $match->is_live ? rand(0, 2) : '-' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-center font-medium mb-4 text-white">Kazanan</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <button class="odds-button bg-black bg-opacity-50 border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all backdrop-blur-sm" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Bahis sistemi yakında!')" @endguest>
                                    <div class="text-sm text-gray-300">{{ Str::limit($team1Name, 8) }}</div>
                                    <div class="font-bold text-gold">{{ number_format(rand(120, 300) / 100, 2) }}</div>
                                </button>
                                <button class="odds-button bg-black bg-opacity-50 border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all backdrop-blur-sm" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Bahis sistemi yakında!')" @endguest>
                                    <div class="text-sm text-gray-300">{{ Str::limit($team2Name, 8) }}</div>
                                    <div class="font-bold text-gold">{{ number_format(rand(120, 300) / 100, 2) }}</div>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-center font-medium mb-4 text-white">Daha Fazla</h4>
                            <div class="space-y-2">
                                @if($match->type === 'Counter-Strike 2')
                                    <button class="odds-button w-full bg-black bg-opacity-50 border border-gray-600 p-2 rounded-lg flex justify-between items-center hover:border-gold transition-all backdrop-blur-sm" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Bahis sistemi yakında!')" @endguest>
                                        <span class="text-sm text-gray-300">Toplam Round</span>
                                        <span class="text-gold font-bold">O{{ rand(24, 28) }}.5</span>
                                    </button>
                                @elseif($match->type === 'League of Legends')
                                    <button class="odds-button w-full bg-black bg-opacity-50 border border-gray-600 p-2 rounded-lg flex justify-between items-center hover:border-gold transition-all backdrop-blur-sm" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Bahis sistemi yakında!')" @endguest>
                                        <span class="text-sm text-gray-300">İlk Kan</span>
                                        <span class="text-gold font-bold">{{ number_format(rand(150, 250) / 100, 2) }}</span>
                                    </button>
                                @else
                                    <button class="odds-button w-full bg-black bg-opacity-50 border border-gray-600 p-2 rounded-lg flex justify-between items-center hover:border-gold transition-all backdrop-blur-sm" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Bahis sistemi yakında!')" @endguest>
                                        <span class="text-sm text-gray-300">Handicap</span>
                                        <span class="text-gold font-bold">{{ number_format(rand(160, 220) / 100, 2) }}</span>
                                    </button>
                                @endif
                                <button class="bg-gold text-black p-2 rounded-lg font-medium text-center w-full hover:bg-yellow-500 transition-colors">
                                    +{{ rand(8, 20) }} Bahis Seçeneği
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            @empty
                <div class="bg-secondary rounded-xl p-8 text-center">
                    <div class="text-gray-400 text-4xl mb-4">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Henüz e-spor maçı bulunmuyor</h3>
                    <p class="text-gray-400">Yakında heyecan verici e-spor maçları eklenecek!</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
