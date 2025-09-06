@extends('layouts.app')

@section('title', 'Canlı Bahis - BetGround')

@push('styles')
<style>
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    }
    .match-card {
        background: #2a2a2a;
        border: 1px solid #404040;
        border-radius: 8px;
        margin-bottom: 16px;
    }
    .odds-table {
        background: #1f1f1f;
        border-radius: 8px;
    }
    .odds-cell {
        background: #2a2a2a;
        border: 1px solid #404040;
        border-radius: 4px;
        padding: 8px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .odds-cell:hover {
        background: #ffd700;
        color: #000;
        border-color: #ffd700;
        transform: translateY(-1px);
    }
    .odds-cell.selected {
        background: #ffd700;
        color: #000;
        border-color: #ffd700;
    }
    .match-live {
        background: #dc2626;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        animation: pulse 2s infinite;
    }
    .match-upcoming {
        background: #059669;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
    }
    .league-badge {
        background: #374151;
        color: #d1d5db;
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
    }
    .team-logo {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .score-display {
        font-size: 24px;
        font-weight: bold;
        color: #ffd700;
    }
    .odds-value {
        font-weight: bold;
        font-size: 14px;
        color: #ffd700;
    }
    .odds-label {
        font-size: 11px;
        color: #9ca3af;
        margin-bottom: 2px;
    }
    .table-header {
        background: #1f2937;
        color: #d1d5db;
        font-weight: 600;
        font-size: 12px;
        padding: 8px 12px;
        text-align: center;
        border-bottom: 2px solid #374151;
    }
    .refresh-btn {
        background: #374151;
        color: #d1d5db;
        border: 1px solid #4b5563;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .refresh-btn:hover {
        background: #4b5563;
        color: #ffd700;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
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
            <p class="text-xl text-gray-300">Canlı maçlarda gerçek zamanlı bahis yapın</p>
        </div>
    </section>

    <!-- Live Matches -->
    <section>
        <div class="space-y-6">
            @foreach($liveMatches as $match)
            <div class="match-card" data-match-id="{{ $match['id'] }}">
                <!-- Match Header -->
                <div class="p-4 border-b border-gray-600">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            @if($match['is_live'])
                                <span class="match-live">
                                    <i class="fas fa-circle text-xs"></i> CANLI
                                </span>
                                <span class="text-gray-400 text-sm">{{ $match['minute'] }}'</span>
                            @else
                                <span class="match-upcoming">
                                    <i class="fas fa-clock text-xs"></i> {{ date('H:i', strtotime($match['commence_time'])) }}
                                </span>
                            @endif
                            <span class="league-badge">{{ $match['sport_title'] }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="refresh-btn" onclick="refreshMatch('{{ $match['id'] }}')">
                                <i class="fas fa-refresh text-xs"></i> Yenile
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Teams and Score -->
                <div class="p-4 border-b border-gray-600">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <!-- Home Team -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/24x24" alt="{{ $match['home_team'] }}" class="team-logo">
                                    <span class="font-medium text-white">{{ $match['home_team'] }}</span>
                                </div>
                                @if($match['is_live'])
                                    <span class="score-display">{{ $match['score']['home'] }}</span>
                                @endif
                            </div>
                            <!-- Away Team -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/24x24" alt="{{ $match['away_team'] }}" class="team-logo">
                                    <span class="font-medium text-white">{{ $match['away_team'] }}</span>
                                </div>
                                @if($match['is_live'])
                                    <span class="score-display">{{ $match['score']['away'] }}</span>
                                @else
                                    <span class="text-gray-500 text-lg">vs</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Odds Table -->
                <div class="odds-table p-4">
                    <div class="grid grid-cols-12 gap-2">
                        <!-- Table Headers -->
                        <div class="col-span-2 table-header">1X2</div>
                        <div class="col-span-2 table-header">Handikap</div>
                        <div class="col-span-2 table-header">Alt/Üst 2.5</div>
                        <div class="col-span-2 table-header">Çifte Şans</div>
                        <div class="col-span-2 table-header">İ.Y. Gol</div>
                        <div class="col-span-2 table-header">Doğru Skor</div>

                        <!-- 1X2 Odds -->
                        <div class="col-span-2">
                            <div class="grid grid-cols-3 gap-1">
                                <div class="odds-cell" 
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'home', {{ $match['odds']['home'] }}, '{{ $match['home_team'] }} Kazanır')" @endguest
                                     data-market="1x2" data-outcome="home">
                                    <div class="odds-label">1</div>
                                    <div class="odds-value">{{ number_format($match['odds']['home'], 2) }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'draw', {{ $match['odds']['draw'] }}, 'Berabere')" @endguest
                                     data-market="1x2" data-outcome="draw">
                                    <div class="odds-label">X</div>
                                    <div class="odds-value">{{ number_format($match['odds']['draw'], 2) }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'away', {{ $match['odds']['away'] }}, '{{ $match['away_team'] }} Kazanır')" @endguest
                                     data-market="1x2" data-outcome="away">
                                    <div class="odds-label">2</div>
                                    <div class="odds-value">{{ number_format($match['odds']['away'], 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Handicap -->
                        <div class="col-span-2">
                            <div class="grid grid-cols-2 gap-1">
                                @if(isset($match['additional_markets']['handicap']))
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'handicap', 'home_plus_1', {{ $match['additional_markets']['handicap']['home_plus_1'] }}, '{{ $match['home_team'] }} +1')" @endguest>
                                    <div class="odds-label">1(+1)</div>
                                    <div class="odds-value">{{ $match['additional_markets']['handicap']['home_plus_1'] }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'handicap', 'away_plus_1', {{ $match['additional_markets']['handicap']['away_plus_1'] }}, '{{ $match['away_team'] }} +1')" @endguest>
                                    <div class="odds-label">2(+1)</div>
                                    <div class="odds-value">{{ $match['additional_markets']['handicap']['away_plus_1'] }}</div>
                                </div>
                                @else
                                <div class="odds-cell">
                                    <div class="odds-label">1(+1)</div>
                                    <div class="odds-value">1.85</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">2(+1)</div>
                                    <div class="odds-value">1.95</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Over/Under 2.5 -->
                        <div class="col-span-2">
                            <div class="grid grid-cols-2 gap-1">
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'totals', 'over_2_5', {{ $match['additional_markets']['over_under_2_5']['over'] }}, 'Üst 2.5')" @endguest>
                                    <div class="odds-label">Üst</div>
                                    <div class="odds-value">{{ $match['additional_markets']['over_under_2_5']['over'] }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'totals', 'under_2_5', {{ $match['additional_markets']['over_under_2_5']['under'] }}, 'Alt 2.5')" @endguest>
                                    <div class="odds-label">Alt</div>
                                    <div class="odds-value">{{ $match['additional_markets']['over_under_2_5']['under'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Double Chance -->
                        <div class="col-span-2">
                            <div class="grid grid-cols-3 gap-1">
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'double_chance', '1x', {{ $match['additional_markets']['double_chance']['1X'] }}, '1X')" @endguest>
                                    <div class="odds-label">1X</div>
                                    <div class="odds-value">{{ $match['additional_markets']['double_chance']['1X'] }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'double_chance', '12', {{ $match['additional_markets']['double_chance']['12'] }}, '12')" @endguest>
                                    <div class="odds-label">12</div>
                                    <div class="odds-value">{{ $match['additional_markets']['double_chance']['12'] }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'double_chance', 'x2', {{ $match['additional_markets']['double_chance']['X2'] }}, 'X2')" @endguest>
                                    <div class="odds-label">X2</div>
                                    <div class="odds-value">{{ $match['additional_markets']['double_chance']['X2'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Both Teams to Score -->
                        <div class="col-span-2">
                            <div class="grid grid-cols-2 gap-1">
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'both_teams_score', 'yes', {{ $match['additional_markets']['both_teams_score']['yes'] }}, 'İY Gol Var')" @endguest>
                                    <div class="odds-label">Var</div>
                                    <div class="odds-value">{{ $match['additional_markets']['both_teams_score']['yes'] }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'both_teams_score', 'no', {{ $match['additional_markets']['both_teams_score']['no'] }}, 'İY Gol Yok')" @endguest>
                                    <div class="odds-label">Yok</div>
                                    <div class="odds-value">{{ $match['additional_markets']['both_teams_score']['no'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Correct Score -->
                        <div class="col-span-2">
                            <div class="grid grid-cols-2 gap-1">
                                @if(isset($match['additional_markets']['correct_score']))
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'correct_score', '2_1', {{ $match['additional_markets']['correct_score']['2_1'] }}, '2-1')" @endguest>
                                    <div class="odds-label">2-1</div>
                                    <div class="odds-value">{{ $match['additional_markets']['correct_score']['2_1'] }}</div>
                                </div>
                                <div class="odds-cell"
                                     @guest onclick="window.location='{{ route('login') }}'" @else onclick="addToBetSlip('{{ $match['id'] }}', 'correct_score', '1_2', {{ $match['additional_markets']['correct_score']['1_2'] }}, '1-2')" @endguest>
                                    <div class="odds-label">1-2</div>
                                    <div class="odds-value">{{ $match['additional_markets']['correct_score']['1_2'] }}</div>
                                </div>
                                @else
                                <div class="odds-cell">
                                    <div class="odds-label">2-1</div>
                                    <div class="odds-value">9.50</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">1-2</div>
                                    <div class="odds-value">18.00</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- More Options Row -->
                        <div class="col-span-12 mt-2">
                            <div class="flex justify-center">
                                <button class="bg-gold text-black px-6 py-2 rounded-lg font-medium hover:bg-yellow-500 transition-colors" 
                                        onclick="showMoreMarkets('{{ $match['id'] }}')">
                                    <i class="fas fa-plus mr-2"></i>+{{ rand(15, 45) }} Daha Fazla Bahis Seçeneği
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Matches Message -->
        @if(!$liveMatches || count($liveMatches) == 0)
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
                <div class="text-2xl font-bold mb-1">{{ count($liveMatches) }}</div>
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
                alert('Bahis sistemi yakında aktif edilecek! Demo amaçlı gösterim.');
                this.clearBets();
                this.open = false;
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
                element.classList.add('selected');
            }
        },

        removeHighlight(matchId, market, outcome) {
            const selector = `[data-match-id="${matchId}"] [data-market="${market}"][data-outcome="${outcome}"]`;
            const element = document.querySelector(selector);
            if (element) {
                element.classList.remove('selected');
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

// Refresh match data
function refreshMatch(matchId) {
    console.log('Refreshing match:', matchId);
    // location.reload(); // Simple refresh for now
}

// Show more markets
function showMoreMarkets(matchId) {
    alert('Daha fazla bahis seçeneği yakında eklenecek!');
}

// Auto-refresh every 30 seconds
setInterval(function() {
    if (!window.betSlipComponent || window.betSlipComponent.bets.length === 0) {
        fetch('/api/sports/live-matches')
            .then(response => response.json())
            .then(data => {
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
