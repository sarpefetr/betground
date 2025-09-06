@extends('layouts.app')

@section('title', 'Canlı Bahis - BetGround')

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
    .match-live {
        animation: pulse 2s infinite;
    }
    .odds-button {
        transition: all 0.2s ease;
    }
    .odds-button:hover {
        background: rgba(255, 215, 0, 0.1);
        border-color: #ffd700;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-futbol mr-3"></i>Canlı Bahis
            </h1>
            <p class="text-xl text-gray-300">Maçları canlı izleyip en iyi oranlarla bahis yapın</p>
        </div>
    </section>

    <!-- Sports Categories -->
    <section class="mb-8" x-data="{ selectedSport: 'futbol' }">
        <div class="flex flex-wrap gap-4 justify-center mb-8">
            <button @click="selectedSport = 'futbol'" 
                    :class="selectedSport === 'futbol' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-futbol mr-2"></i>Futbol
            </button>
            <button @click="selectedSport = 'basketbol'" 
                    :class="selectedSport === 'basketbol' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-basketball-ball mr-2"></i>Basketbol
            </button>
            <button @click="selectedSport = 'tenis'" 
                    :class="selectedSport === 'tenis' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-table-tennis-paddle-ball mr-2"></i>Tenis
            </button>
            <button @click="selectedSport = 'voleybol'" 
                    :class="selectedSport === 'voleybol' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-volleyball-ball mr-2"></i>Voleybol
            </button>
            <button @click="selectedSport = 'hentbol'" 
                    :class="selectedSport === 'hentbol' ? 'bg-gold text-black' : 'bg-secondary text-white hover:bg-accent'"
                    class="px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-hand-fist mr-2"></i>Hentbol
            </button>
        </div>

        <!-- Live Matches -->
        <div class="space-y-4" id="matches-container">
            @foreach($liveMatches as $match)
            <div class="bg-secondary rounded-xl p-6 card-hover" data-match-id="{{ $match['id'] }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        @if($match['is_live'])
                        <span class="bg-red-600 text-white px-2 py-1 rounded text-xs match-live mr-3">
                            <i class="fas fa-circle text-red-400 animate-pulse mr-1"></i>CANLI
                        </span>
                        @else
                        <span class="bg-green-600 text-white px-2 py-1 rounded text-xs mr-3">
                            <i class="fas fa-clock mr-1"></i>{{ date('H:i', strtotime($match['commence_time'])) }}
                        </span>
                        @endif
                        <div>
                            <h3 class="font-bold text-lg">{{ $match['sport_title'] }}</h3>
                            @if($match['is_live'])
                            <p class="text-sm text-gray-400">{{ $match['minute'] }}' dakika</p>
                            @else
                            <p class="text-sm text-gray-400">{{ date('d.m.Y H:i', strtotime($match['commence_time'])) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($match['is_live'])
                        <div class="text-gold font-bold text-xl">{{ $match['score']['home'] }} - {{ $match['score']['away'] }}</div>
                        <button onclick="refreshOdds('{{ $match['id'] }}')" class="text-xs text-gray-400 hover:text-white mt-1">
                            <i class="fas fa-refresh mr-1"></i>Oranları Yenile
                        </button>
                        @else
                        <div class="text-gray-400 text-lg">vs</div>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Teams -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                            <div class="flex items-center">
                                <img src="https://via.placeholder.com/32x32" alt="{{ $match['home_team'] }}" class="w-8 h-8 mr-3 rounded-full">
                                <span class="font-medium">{{ $match['home_team'] }}</span>
                            </div>
                            @if($match['is_live'])
                                <span class="text-gold font-bold">{{ $match['score']['home'] }}</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                            <div class="flex items-center">
                                <img src="https://via.placeholder.com/32x32" alt="{{ $match['away_team'] }}" class="w-8 h-8 mr-3 rounded-full">
                                <span class="font-medium">{{ $match['away_team'] }}</span>
                            </div>
                            @if($match['is_live'])
                                <span class="text-gold font-bold">{{ $match['score']['away'] }}</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- 1X2 Betting -->
                    <div>
                        <h4 class="text-center font-medium mb-4">1X2</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <button class="odds-button bg-accent border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all" 
                                @guest 
                                    onclick="window.location='{{ route('login') }}'" 
                                @else 
                                    onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'home', {{ $match['odds']['home'] }}, '{{ $match['home_team'] }} Kazanır')" 
                                @endguest
                                data-odds="{{ $match['odds']['home'] }}"
                                data-market="1x2" 
                                data-outcome="home">
                                <div class="text-sm text-gray-400">1</div>
                                <div class="font-bold text-gold">{{ number_format($match['odds']['home'], 2) }}</div>
                            </button>
                            <button class="odds-button bg-accent border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all" 
                                @guest 
                                    onclick="window.location='{{ route('login') }}'" 
                                @else 
                                    onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'draw', {{ $match['odds']['draw'] }}, 'Berabere')" 
                                @endguest
                                data-odds="{{ $match['odds']['draw'] }}"
                                data-market="1x2" 
                                data-outcome="draw">
                                <div class="text-sm text-gray-400">X</div>
                                <div class="font-bold text-gold">{{ number_format($match['odds']['draw'], 2) }}</div>
                            </button>
                            <button class="odds-button bg-accent border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all" 
                                @guest 
                                    onclick="window.location='{{ route('login') }}'" 
                                @else 
                                    onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'away', {{ $match['odds']['away'] }}, '{{ $match['away_team'] }} Kazanır')" 
                                @endguest
                                data-odds="{{ $match['odds']['away'] }}"
                                data-market="1x2" 
                                data-outcome="away">
                                <div class="text-sm text-gray-400">2</div>
                                <div class="font-bold text-gold">{{ number_format($match['odds']['away'], 2) }}</div>
                            </button>
                        </div>
                    </div>

                    <!-- Additional Markets -->
                    <div>
                        <h4 class="text-center font-medium mb-4">Daha Fazla</h4>
                        <div class="space-y-2">
                            <div class="grid grid-cols-2 gap-1">
                                <button class="odds-button bg-accent border border-gray-600 p-2 rounded-lg text-center hover:border-gold transition-all text-xs" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', 'totals', 'over_2_5', {{ $match['additional_markets']['over_under_2_5']['over'] }}, 'Üst 2.5 Gol')" 
                                    @endguest>
                                    <div class="text-xs text-gray-400">Üst 2.5</div>
                                    <div class="font-bold text-gold">{{ $match['additional_markets']['over_under_2_5']['over'] }}</div>
                                </button>
                                <button class="odds-button bg-accent border border-gray-600 p-2 rounded-lg text-center hover:border-gold transition-all text-xs" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', 'totals', 'under_2_5', {{ $match['additional_markets']['over_under_2_5']['under'] }}, 'Alt 2.5 Gol')" 
                                    @endguest>
                                    <div class="text-xs text-gray-400">Alt 2.5</div>
                                    <div class="font-bold text-gold">{{ $match['additional_markets']['over_under_2_5']['under'] }}</div>
                                </button>
                            </div>
                            <div class="grid grid-cols-3 gap-1">
                                <button class="odds-button bg-accent border border-gray-600 p-2 rounded-lg text-center hover:border-gold transition-all text-xs" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', 'double_chance', '1x', {{ $match['additional_markets']['double_chance']['1X'] }}, '1X')" 
                                    @endguest>
                                    <div class="text-xs text-gray-400">1X</div>
                                    <div class="font-bold text-gold">{{ $match['additional_markets']['double_chance']['1X'] }}</div>
                                </button>
                                <button class="odds-button bg-accent border border-gray-600 p-2 rounded-lg text-center hover:border-gold transition-all text-xs" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', 'double_chance', '12', {{ $match['additional_markets']['double_chance']['12'] }}, '12')" 
                                    @endguest>
                                    <div class="text-xs text-gray-400">12</div>
                                    <div class="font-bold text-gold">{{ $match['additional_markets']['double_chance']['12'] }}</div>
                                </button>
                                <button class="odds-button bg-accent border border-gray-600 p-2 rounded-lg text-center hover:border-gold transition-all text-xs" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', 'double_chance', 'x2', {{ $match['additional_markets']['double_chance']['X2'] }}, 'X2')" 
                                    @endguest>
                                    <div class="text-xs text-gray-400">X2</div>
                                    <div class="font-bold text-gold">{{ $match['additional_markets']['double_chance']['X2'] }}</div>
                                </button>
                            </div>
                            <button class="bg-gold text-black p-2 rounded-lg font-medium text-center w-full hover:bg-yellow-500 transition-colors" onclick="showMoreMarkets('{{ $match['id'] }}')">
                                +{{ rand(30, 60) }} Bahis Seçeneği
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Upcoming Matches -->
            @if($upcomingMatches && count($upcomingMatches) > 0)
            <div class="mt-8">
                <h3 class="text-2xl font-bold mb-4">Yaklaşan Maçlar</h3>
                @foreach($upcomingMatches as $match)
                @if(!$match['is_live'])
                <div class="bg-secondary rounded-xl p-6 card-hover mb-4" data-match-id="{{ $match['id'] }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs mr-3">
                                <i class="fas fa-clock mr-1"></i>{{ date('H:i', strtotime($match['commence_time'])) }}
                            </span>
                            <div>
                                <h3 class="font-bold text-lg">{{ $match['sport_title'] }}</h3>
                                <p class="text-sm text-gray-400">{{ date('d.m.Y H:i', strtotime($match['commence_time'])) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-gray-400 text-lg">vs</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Teams -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/32x32" alt="{{ $match['home_team'] }}" class="w-8 h-8 mr-3 rounded-full">
                                    <span class="font-medium">{{ $match['home_team'] }}</span>
                                </div>
                                <span class="text-gray-500">-</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/32x32" alt="{{ $match['away_team'] }}" class="w-8 h-8 mr-3 rounded-full">
                                    <span class="font-medium">{{ $match['away_team'] }}</span>
                                </div>
                                <span class="text-gray-500">-</span>
                            </div>
                        </div>

                        <!-- 1X2 Betting -->
                        <div>
                            <h4 class="text-center font-medium mb-4">1X2</h4>
                            <div class="grid grid-cols-3 gap-2">
                                <button class="odds-button bg-accent border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'home', {{ $match['odds']['home'] }}, '{{ $match['home_team'] }} Kazanır')" 
                                    @endguest>
                                    <div class="text-sm text-gray-400">1</div>
                                    <div class="font-bold text-gold">{{ number_format($match['odds']['home'], 2) }}</div>
                                </button>
                                <button class="odds-button bg-accent border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'draw', {{ $match['odds']['draw'] }}, 'Berabere')" 
                                    @endguest>
                                    <div class="text-sm text-gray-400">X</div>
                                    <div class="font-bold text-gold">{{ number_format($match['odds']['draw'], 2) }}</div>
                                </button>
                                <button class="odds-button bg-accent border border-gray-600 p-3 rounded-lg text-center hover:border-gold transition-all" 
                                    @guest 
                                        onclick="window.location='{{ route('login') }}'" 
                                    @else 
                                        onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'away', {{ $match['odds']['away'] }}, '{{ $match['away_team'] }} Kazanır')" 
                                    @endguest>
                                    <div class="text-sm text-gray-400">2</div>
                                    <div class="font-bold text-gold">{{ number_format($match['odds']['away'], 2) }}</div>
                                </button>
                            </div>
                        </div>

                        <!-- Additional Markets -->
                        <div>
                            <h4 class="text-center font-medium mb-4">Daha Fazla</h4>
                            <div class="space-y-2">
                                <button class="bg-gold text-black p-2 rounded-lg font-medium text-center w-full hover:bg-yellow-500 transition-colors" onclick="showMoreMarkets('{{ $match['id'] }}')">
                                    +{{ rand(35, 55) }} Bahis Seçeneği
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @endif

            <!-- No Matches Message -->
            @if((!$liveMatches || count($liveMatches) == 0) && (!$upcomingMatches || count($upcomingMatches) == 0))
            <div class="bg-secondary rounded-xl p-12 text-center">
                <div class="text-gray-400 text-4xl mb-4">
                    <i class="fas fa-futbol"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Şu Anda Canlı Maç Yok</h3>
                <p class="text-gray-400">Maçlar başladığında burada görünecek.</p>
                <button onclick="location.reload()" class="mt-4 bg-gold text-black px-6 py-2 rounded-lg font-medium hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Yenile
                </button>
            </div>
            @endif

        </div>
    </section>

    <!-- Bet Slip (Floating) -->
    <div x-data="betSlip()" class="fixed bottom-4 right-4 z-50">
        <!-- Bet Slip Button -->
        <button @click="open = !open" 
                class="bg-gold text-black p-4 rounded-full shadow-lg hover:bg-yellow-500 transition-colors">
            <i class="fas fa-ticket-alt text-xl"></i>
            <span x-show="bets.length > 0" x-text="bets.length" 
                  class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">
            </span>
        </button>

        <!-- Bet Slip Panel -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform translate-y-1"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-1"
             class="absolute bottom-16 right-0 w-96 bg-secondary rounded-xl shadow-2xl border border-accent max-h-96 overflow-y-auto">
            
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg">Bahis Kuponu</h3>
                    <div class="flex items-center space-x-2">
                        <span x-show="bets.length > 0" class="text-sm text-gray-400" x-text="`${bets.length} seçim`"></span>
                        <button @click="open = false" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div x-show="bets.length === 0" class="text-center py-8 text-gray-400">
                    <i class="fas fa-ticket-alt text-4xl mb-4"></i>
                    <p>Henüz bahis seçmediniz</p>
                    <p class="text-sm mt-2">Oranlar üzerine tıklayarak bahis ekleyin</p>
                    @guest
                        <a href="{{ route('login') }}" class="text-gold hover:text-yellow-500 mt-3 block">
                            Giriş yapın ve bahis yapmaya başlayın
                        </a>
                    @endguest
                </div>

                <!-- Bet List -->
                <div x-show="bets.length > 0" class="space-y-3">
                    <template x-for="(bet, index) in bets" :key="bet.id">
                        <div class="bg-accent p-3 rounded-lg border border-gray-600">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <div class="text-sm font-medium" x-text="bet.description"></div>
                                    <div class="text-xs text-gray-400" x-text="`${bet.matchId} - ${bet.market}`"></div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="text-gold font-bold" x-text="bet.odds"></div>
                                    <button @click="removeBet(index)" class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="number" 
                                       x-model="bet.stake" 
                                       @input="updateBet(index, $event.target.value)"
                                       placeholder="Miktar"
                                       min="1"
                                       class="flex-1 bg-primary text-white px-2 py-1 rounded text-sm border border-gray-600 focus:border-gold focus:outline-none"
                                       step="0.01">
                                <div class="text-xs text-gray-400">₺</div>
                            </div>
                            <div x-show="bet.stake > 0" class="text-xs text-gold mt-1">
                                Kazanç: ₺<span x-text="(bet.stake * bet.odds).toFixed(2)"></span>
                            </div>
                        </div>
                    </template>

                    <!-- Betting Summary -->
                    <div class="bg-primary p-3 rounded-lg border border-gold">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium">Toplam Bahis:</span>
                            <span class="font-bold">₺<span x-text="totalStake.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-medium">Potansiyel Kazanç:</span>
                            <span class="font-bold text-gold">₺<span x-text="totalPayout.toFixed(2)"></span></span>
                        </div>
                        @auth
                        <div class="text-xs text-gray-400 mb-3">
                            Bakiye: ₺{{ auth()->user()->wallet ? number_format(auth()->user()->wallet->balance, 2) : '0.00' }}
                        </div>
                        @endauth
                        
                        <button @click="placeBets()" 
                                :disabled="!canPlaceBets()"
                                :class="canPlaceBets() ? 'bg-gold text-black hover:bg-yellow-500' : 'bg-gray-600 text-gray-400 cursor-not-allowed'"
                                class="w-full py-2 rounded-lg font-bold transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Bahis Yap
                        </button>
                        
                        <button @click="clearBets()" class="w-full mt-2 text-red-400 text-sm hover:text-red-300">
                            <i class="fas fa-trash mr-2"></i>Tümünü Temizle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <section class="mt-12">
        <h2 class="text-3xl font-bold mb-8 text-center">Canlı İstatistikler</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-secondary p-6 rounded-xl text-center">
                <div class="text-gold text-3xl mb-2">
                    <i class="fas fa-futbol"></i>
                </div>
                <div class="text-2xl font-bold mb-1">{{ rand(20, 30) }}</div>
                <div class="text-sm text-gray-400">Canlı Maç</div>
            </div>
            
            <div class="bg-secondary p-6 rounded-xl text-center">
                <div class="text-gold text-3xl mb-2">
                    <i class="fas fa-users"></i>
                </div>
                <div class="text-2xl font-bold mb-1">{{ number_format(rand(5000, 12000)) }}</div>
                <div class="text-sm text-gray-400">Aktif Bahisçi</div>
            </div>
            
            <div class="bg-secondary p-6 rounded-xl text-center">
                <div class="text-gold text-3xl mb-2">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="text-2xl font-bold mb-1">%98.5</div>
                <div class="text-sm text-gray-400">Ödeme Oranı</div>
            </div>
            
            <div class="bg-secondary p-6 rounded-xl text-center">
                <div class="text-gold text-3xl mb-2">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-2xl font-bold mb-1">{{ number_format(rand(160, 220) / 100, 2) }}</div>
                <div class="text-sm text-gray-400">Ortalama Oran</div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Global betting variables
window.betSlipComponent = null;

// Alpine.js Bet Slip Component
function betSlip() {
    return {
        open: false,
        bets: [],

        init() {
            window.betSlipComponent = this;
            // Load saved bets from localStorage
            const savedBets = localStorage.getItem('betSlip');
            if (savedBets) {
                this.bets = JSON.parse(savedBets);
            }
        },

        get totalStake() {
            return this.bets.reduce((sum, bet) => sum + (parseFloat(bet.stake) || 0), 0);
        },

        get totalPayout() {
            return this.bets.reduce((sum, bet) => {
                const stake = parseFloat(bet.stake) || 0;
                return sum + (stake * bet.odds);
            }, 0);
        },

        addBet(matchId, market, outcome, odds, description) {
            // Check if bet already exists
            const existingIndex = this.bets.findIndex(bet => 
                bet.matchId === matchId && bet.market === market && bet.outcome === outcome
            );

            if (existingIndex >= 0) {
                // Update existing bet odds if changed
                this.bets[existingIndex].odds = odds;
                this.bets[existingIndex].description = description;
            } else {
                // Add new bet
                const newBet = {
                    id: Date.now() + Math.random(),
                    matchId: matchId,
                    market: market,
                    outcome: outcome,
                    odds: parseFloat(odds),
                    description: description,
                    stake: 10 // Default stake
                };
                this.bets.push(newBet);
                this.open = true; // Open bet slip when new bet added
            }

            this.saveBets();
            this.highlightSelection(matchId, market, outcome);
        },

        removeBet(index) {
            if (index >= 0 && index < this.bets.length) {
                const bet = this.bets[index];
                this.removeHighlight(bet.matchId, bet.market, bet.outcome);
                this.bets.splice(index, 1);
                this.saveBets();
            }
        },

        updateBet(index, stake) {
            if (index >= 0 && index < this.bets.length) {
                this.bets[index].stake = parseFloat(stake) || 0;
                this.saveBets();
            }
        },

        clearBets() {
            // Remove all highlights
            this.bets.forEach(bet => {
                this.removeHighlight(bet.matchId, bet.market, bet.outcome);
            });
            this.bets = [];
            this.saveBets();
        },

        canPlaceBets() {
            if (this.bets.length === 0) return false;
            if (this.totalStake <= 0) return false;
            
            @auth
            const userBalance = {{ auth()->user()->wallet ? auth()->user()->wallet->balance : 0 }};
            return this.totalStake <= userBalance;
            @else
            return false;
            @endauth
        },

        async placeBets() {
            if (!this.canPlaceBets()) {
                alert('Bahis yapmak için yeterli bakiyeniz yok veya geçersiz bahis tutarı!');
                return;
            }

            try {
                // Here would be the API call to place bets
                const response = await fetch('/api/sports/place-bet', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        bets: this.bets.map(bet => ({
                            match_id: bet.matchId,
                            market: bet.market,
                            outcome: bet.outcome,
                            odds: bet.odds,
                            stake: bet.stake,
                            description: bet.description
                        }))
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    alert('Bahisleriniz başarıyla alındı! İyi şanslar!');
                    this.clearBets();
                    this.open = false;
                    
                    // Reload page to refresh balance
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert(result.message || 'Bahis alınırken hata oluştu!');
                }
            } catch (error) {
                console.error('Bet placement error:', error);
                alert('Bahis sistemi şu anda çalışmıyor. Lütfen daha sonra tekrar deneyin.');
            }
        },

        saveBets() {
            localStorage.setItem('betSlip', JSON.stringify(this.bets));
        },

        highlightSelection(matchId, market, outcome) {
            const selector = `[data-match-id="${matchId}"] [data-market="${market}"][data-outcome="${outcome}"]`;
            const element = document.querySelector(selector);
            if (element) {
                element.classList.add('border-gold', 'bg-gold', 'text-black');
            }
        },

        removeHighlight(matchId, market, outcome) {
            const selector = `[data-match-id="${matchId}"] [data-market="${market}"][data-outcome="${outcome}"]`;
            const element = document.querySelector(selector);
            if (element) {
                element.classList.remove('border-gold', 'bg-gold', 'text-black');
            }
        }
    };
}

// Global function to add bets (called from bet buttons)
function addToBetSlip(matchId, market, outcome, odds, description) {
    if (window.betSlipComponent) {
        window.betSlipComponent.addBet(matchId, market, outcome, odds, description);
    }
}

// Refresh odds for a specific match
async function refreshOdds(matchId) {
    try {
        const response = await fetch(`/api/sports/match/${matchId}/refresh-odds`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // Update the odds on the page
            location.reload(); // Simple reload for now
        } else {
            console.error('Failed to refresh odds:', result.error);
        }
    } catch (error) {
        console.error('Error refreshing odds:', error);
    }
}

// Show more markets (placeholder)
function showMoreMarkets(matchId) {
    alert('Daha fazla bahis seçeneği yakında eklenecek!');
}

// Auto-refresh matches every 30 seconds
setInterval(function() {
    // Only refresh if no bets in slip to avoid interrupting user
    if (!window.betSlipComponent || window.betSlipComponent.bets.length === 0) {
        // Silent refresh of match data
        fetch('/api/sports/live-matches')
            .then(response => response.json())
            .then(data => {
                // Update match data without full page reload
                console.log('Matches updated:', data.matches.length);
            })
            .catch(error => console.error('Auto-refresh error:', error));
    }
}, 30000);

// Add meta tag for CSRF token if not present
if (!document.querySelector('meta[name="csrf-token"]')) {
    const csrfMeta = document.createElement('meta');
    csrfMeta.setAttribute('name', 'csrf-token');
    csrfMeta.setAttribute('content', '{{ csrf_token() }}');
    document.head.appendChild(csrfMeta);
}
</script>
@endpush



