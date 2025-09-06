@extends('layouts.app')

@section('title', 'Canlı Bahis - BetGround')

@push('styles')
<style>
    /* Dark Theme */
    body { background: #0f0f0f; }
    
    /* Match Table Styles */
    .match-table {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }
    
    .match-header {
        background: #222;
        padding: 8px 12px;
        border-bottom: 1px solid #333;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .live-badge {
        background: #dc2626;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        animation: pulse 2s infinite;
    }
    
    .league-name {
        color: #888;
        font-size: 12px;
        margin-left: 10px;
    }
    
    .match-content {
        display: flex;
        height: 100%;
    }
    
    .teams-section {
        width: 200px;
        padding: 12px;
        border-right: 1px solid #333;
        background: #161616;
    }
    
    .team {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        color: #fff;
        font-size: 13px;
    }
    
    .team-name {
        flex: 1;
    }
    
    .score {
        font-weight: bold;
        font-size: 16px;
        color: #ffd700;
        margin-left: 10px;
    }
    
    .markets-section {
        flex: 1;
        display: flex;
    }
    
    .market-group {
        flex: 1;
        border-right: 1px solid #333;
        display: flex;
        flex-direction: column;
    }
    
    .market-group:last-child {
        border-right: none;
    }
    
    .market-header {
        background: #1f1f1f;
        padding: 6px 8px;
        text-align: center;
        font-size: 11px;
        color: #888;
        font-weight: 600;
        border-bottom: 1px solid #333;
        min-height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .market-odds {
        display: flex;
        flex: 1;
    }
    
    .odds-cell {
        flex: 1;
        padding: 8px 4px;
        text-align: center;
        border-right: 1px solid #2a2a2a;
        cursor: pointer;
        transition: all 0.2s;
        background: #1a1a1a;
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .odds-cell:last-child {
        border-right: none;
    }
    
    .odds-cell:hover {
        background: #ffd700;
        color: #000;
    }
    
    .odds-cell.selected {
        background: #ffd700;
        color: #000;
    }
    
    .odds-label {
        font-size: 10px;
        color: #888;
        margin-bottom: 4px;
    }
    
    .odds-cell:hover .odds-label,
    .odds-cell.selected .odds-label {
        color: #000;
    }
    
    .odds-value {
        font-size: 14px;
        font-weight: bold;
        color: #ffd700;
    }
    
    .odds-cell:hover .odds-value,
    .odds-cell.selected .odds-value {
        color: #000;
    }
    
    /* More bets button */
    .more-bets {
        background: #2a2a2a;
        border-top: 1px solid #333;
        padding: 8px;
        text-align: center;
    }
    
    .more-bets button {
        background: #ffd700;
        color: #000;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .more-bets button:hover {
        background: #ffed4a;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    /* Bet Slip */
    .bet-slip-container {
        position: fixed;
        right: -400px;
        top: 0;
        width: 400px;
        height: 100vh;
        background: #1a1a1a;
        border-left: 1px solid #333;
        transition: right 0.3s ease;
        z-index: 1001;
        display: flex;
        flex-direction: column;
    }
    
    .bet-slip-container.active {
        right: 0;
    }
    
    .bet-slip-header {
        background: #222;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #333;
    }
    
    .bet-slip-items {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }
    
    .bet-slip-item {
        background: #222;
        border: 1px solid #333;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 10px;
        position: relative;
    }
    
    .bet-slip-item .remove-bet {
        position: absolute;
        top: 5px;
        right: 5px;
        color: #dc2626;
        cursor: pointer;
    }
    
    .bet-slip-footer {
        background: #222;
        padding: 15px;
        border-top: 1px solid #333;
    }
    
    .bet-amount-input {
        width: 100%;
        background: #333;
        border: 1px solid #444;
        color: white;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 4px;
    }
    
    .place-bet-btn {
        width: 100%;
        background: #ffd700;
        color: #000;
        padding: 12px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .place-bet-btn:hover {
        background: #ffed4a;
    }
    
    .place-bet-btn:disabled {
        background: #666;
        cursor: not-allowed;
    }
    
    .bet-slip-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #ffd700;
        color: #000;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4);
        z-index: 1000;
    }
    
    .bet-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc2626;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }
    
    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1002;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
    }
    
    .modal-content {
        background-color: #1a1a1a;
        margin: 5% auto;
        padding: 0;
        border: 1px solid #333;
        width: 80%;
        max-width: 800px;
        max-height: 80vh;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .modal-header {
        background: #222;
        padding: 15px 20px;
        border-bottom: 1px solid #333;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 20px;
        max-height: calc(80vh - 60px);
        overflow-y: auto;
    }
    
    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover {
        color: #fff;
    }
    
    .market-section {
        margin-bottom: 20px;
    }
    
    .market-title {
        background: #2a2a2a;
        padding: 10px 15px;
        margin-bottom: 10px;
        border-radius: 4px;
        font-weight: bold;
        color: #ffd700;
    }
    
    .market-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .market-option {
        background: #333;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #444;
        flex: 0 0 calc(33.333% - 10px);
        text-align: center;
    }
    
    .market-option:hover {
        background: #ffd700;
        color: #000;
        border-color: #ffd700;
    }
    
    .market-option.selected {
        background: #ffd700;
        color: #000;
        border-color: #ffd700;
    }
    
    /* Manuel maç oranları için */
    .odd-box {
        background: #1a1a1a;
        border: 1px solid #333;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 60px;
    }
    
    .odd-box:hover {
        background: #ffd700;
        color: #000;
        border-color: #ffd700;
    }
    
    .odd-box.selected {
        background: #ffd700;
        color: #000;
        border-color: #ffd700;
    }
    
    .odd-box .odd-name {
        font-size: 11px;
        color: #888;
        margin-bottom: 2px;
    }
    
    .odd-box:hover .odd-name,
    .odd-box.selected .odd-name {
        color: #000;
    }
    
    .odd-box .odd-value {
        font-size: 14px;
        font-weight: bold;
        color: #ffd700;
    }
    
    .odd-box:hover .odd-value,
    .odd-box.selected .odd-value {
        color: #000;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-white">
            <i class="fas fa-futbol mr-2"></i>Canlı Bahis
        </h1>
    </div>

    <!-- Matches Container -->
    <div class="matches-container">
        <!-- Canlı Maçlar (Manuel) -->
        @if($manualMatches && count($manualMatches) > 0)
            @php
                $liveManualMatches = $manualMatches->where('is_live', true);
                $upcomingManualMatches = $manualMatches->where('is_live', false);
            @endphp
            
            @if(count($liveManualMatches) > 0)
                <h2 class="text-xl font-bold text-yellow-400 mb-4">
                    <i class="fas fa-circle text-red-500 animate-pulse mr-2"></i>Canlı Maçlar ({{ count($liveManualMatches) }})
                </h2>
                
                @foreach($liveManualMatches as $match)
                <div class="match-table" data-match-id="manual-{{ $match->id }}">
                    <!-- Match Header -->
                    <div class="match-header">
                        <div class="flex items-center">
                            <span class="live-badge">
                                <i class="fas fa-circle text-xs mr-1"></i>CANLI
                            </span>
                            <span class="text-white text-sm ml-2">{{ $match->getCurrentMinuteAttribute() }}'</span>
                            <span class="league-name">{{ $match->league ?? 'Özel Maç' }}</span>
                        </div>
                        <button class="text-gray-400 hover:text-white text-xs" onclick="refreshManualMatch({{ $match->id }})">
                            <i class="fas fa-refresh mr-1"></i>Yenile
                        </button>
                    </div>

                    <!-- Match Content -->
                    <div class="match-content">
                        <!-- Teams Section -->
                        <div class="teams-section">
                            <div class="team">
                                <span class="team-name">{{ $match->home_team }}</span>
                                <span class="score">{{ $match->home_score }}</span>
                            </div>
                            <div class="team">
                                <span class="team-name">{{ $match->away_team }}</span>
                                <span class="score">{{ $match->away_score }}</span>
                            </div>
                        </div>

                        <!-- Markets Section -->
                        <div class="markets-section">
                            @include('games.partials.manual-match-odds', ['match' => $match])
                        </div>
                    </div>

                    <!-- More Bets -->
                    <div class="more-bets">
                        <button onclick="showManualMatchMarkets({{ $match->id }}, '{{ $match->home_team }} vs {{ $match->away_team }}')">
                            +25 Bahis Seçeneği
                        </button>
                    </div>
                </div>
                @endforeach
            @endif
            
            @if(count($upcomingManualMatches) > 0)
                <h2 class="text-xl font-bold text-gray-300 mb-4 {{ count($liveManualMatches) > 0 ? 'mt-8' : '' }}">
                    <i class="fas fa-clock mr-2"></i>Yaklaşan Maçlar ({{ count($upcomingManualMatches) }})
                </h2>
                
                @foreach($upcomingManualMatches as $match)
                <div class="match-table" data-match-id="manual-{{ $match->id }}">
                    <!-- Match Header -->
                    <div class="match-header">
                        <div class="flex items-center">
                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">
                                Yakında
                            </span>
                            <span class="league-name">{{ $match->league ?? 'Özel Maç' }}</span>
                        </div>
                        <button class="text-gray-400 hover:text-white text-xs" onclick="refreshManualMatch({{ $match->id }})">
                            <i class="fas fa-refresh mr-1"></i>Yenile
                        </button>
                    </div>

                    <!-- Match Content -->
                    <div class="match-content">
                        <!-- Teams Section -->
                        <div class="teams-section">
                            <div class="team">
                                <span class="team-name">{{ $match->home_team }}</span>
                            </div>
                            <div class="team">
                                <span class="team-name">{{ $match->away_team }}</span>
                            </div>
                        </div>

                        <!-- Markets Section -->
                        <div class="markets-section">
                            @include('games.partials.manual-match-odds', ['match' => $match])
                        </div>
                    </div>

                    <!-- More Bets -->
                    <div class="more-bets">
                        <button onclick="showManualMatchMarkets({{ $match->id }}, '{{ $match->home_team }} vs {{ $match->away_team }}')">
                            +25 Bahis Seçeneği
                        </button>
                    </div>
                </div>
                @endforeach
            @endif
            
            @if(count($allMatches ?? []) > 0)
                <h2 class="text-xl font-bold text-gray-300 mb-4 mt-8">
                    <i class="fas fa-globe mr-2"></i>Diğer Maçlar
                </h2>
            @endif
        @endif
        
        @php
            $allMatches = array_merge($liveMatches ?? [], $upcomingMatches ?? []);
        @endphp
        
        @if(count($allMatches) > 0)
            <!-- Canlı Maçlar -->
            @if($liveMatches && count($liveMatches) > 0)
                <h2 class="text-xl font-bold text-yellow-400 mb-4">
                    <i class="fas fa-circle text-red-500 animate-pulse mr-2"></i>Canlı Maçlar ({{ count($liveMatches) }})
                </h2>
            @endif
            
            <!-- Yaklaşan Maçlar -->
            @if($upcomingMatches && count($upcomingMatches) > 0 && count($liveMatches) == 0)
                <h2 class="text-xl font-bold text-gray-300 mb-4">
                    <i class="fas fa-clock mr-2"></i>Yaklaşan Maçlar ({{ count($upcomingMatches) }})
                </h2>
            @endif
            
            @foreach($allMatches as $match)
            <div class="match-table" data-match-id="{{ $match['id'] }}">
                <!-- Match Header -->
                <div class="match-header">
                    <div class="flex items-center">
                        @if($match['is_live'])
                            <span class="live-badge">
                                <i class="fas fa-circle text-xs mr-1"></i>CANLI
                            </span>
                            <span class="text-white text-sm ml-2">{{ $match['minute'] }}'</span>
                        @else
                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">
                                @if(!empty($match['commence_time']))
                                    {{ \Carbon\Carbon::parse($match['commence_time'])->format('d.m H:i') }}
                                @else
                                    Yakında
                                @endif
                            </span>
                        @endif
                        <span class="league-name">{{ $match['sport_title'] }}</span>
                    </div>
                    <button class="text-gray-400 hover:text-white text-xs">
                        <i class="fas fa-refresh mr-1"></i>Yenile
                    </button>
                </div>

                <!-- Match Content -->
                <div class="match-content">
                    <!-- Teams Section -->
                    <div class="teams-section">
                        <div class="team">
                            <span class="team-name">{{ $match['home_team'] }}</span>
                            @if($match['is_live'])
                                <span class="score">{{ $match['score']['home'] }}</span>
                            @endif
                        </div>
                        <div class="team">
                            <span class="team-name">{{ $match['away_team'] }}</span>
                            @if($match['is_live'])
                                <span class="score">{{ $match['score']['away'] }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Markets Section -->
                    <div class="markets-section">
                        <!-- 1X2 Market -->
                        <div class="market-group">
                            <div class="market-header">1X2</div>
                            <div class="market-odds">
                                <div class="odds-cell" onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'home', {{ $match['odds']['home'] }}, '{{ $match['home_team'] }}')"
                                     data-market="1x2" data-outcome="home">
                                    <div class="odds-label">1</div>
                                    <div class="odds-value">{{ number_format($match['odds']['home'], 2) }}</div>
                                </div>
                                <div class="odds-cell" onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'draw', {{ $match['odds']['draw'] }}, 'Berabere')"
                                     data-market="1x2" data-outcome="draw">
                                    <div class="odds-label">X</div>
                                    <div class="odds-value">{{ number_format($match['odds']['draw'], 2) }}</div>
                                </div>
                                <div class="odds-cell" onclick="addToBetSlip('{{ $match['id'] }}', '1x2', 'away', {{ $match['odds']['away'] }}, '{{ $match['away_team'] }}')"
                                     data-market="1x2" data-outcome="away">
                                    <div class="odds-label">2</div>
                                    <div class="odds-value">{{ number_format($match['odds']['away'], 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Double Chance -->
                        <div class="market-group">
                            <div class="market-header">Çifte Şans</div>
                            <div class="market-odds">
                                <div class="odds-cell">
                                    <div class="odds-label">1X</div>
                                    <div class="odds-value">{{ $match['additional_markets']['double_chance']['1X'] }}</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">12</div>
                                    <div class="odds-value">{{ $match['additional_markets']['double_chance']['12'] }}</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">X2</div>
                                    <div class="odds-value">{{ $match['additional_markets']['double_chance']['X2'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Over/Under 2.5 -->
                        <div class="market-group">
                            <div class="market-header">Alt/Üst 2.5</div>
                            <div class="market-odds">
                                <div class="odds-cell">
                                    <div class="odds-label">Üst</div>
                                    <div class="odds-value">{{ $match['additional_markets']['over_under_2_5']['over'] }}</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">Alt</div>
                                    <div class="odds-value">{{ $match['additional_markets']['over_under_2_5']['under'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Both Teams Score -->
                        <div class="market-group">
                            <div class="market-header">Karşılıklı Gol</div>
                            <div class="market-odds">
                                <div class="odds-cell">
                                    <div class="odds-label">Var</div>
                                    <div class="odds-value">{{ $match['additional_markets']['both_teams_score']['yes'] }}</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">Yok</div>
                                    <div class="odds-value">{{ $match['additional_markets']['both_teams_score']['no'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional markets placeholder -->
                        <div class="market-group">
                            <div class="market-header">Handikap</div>
                            <div class="market-odds">
                                <div class="odds-cell">
                                    <div class="odds-label">1 (+1)</div>
                                    <div class="odds-value">1.85</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">2 (+1)</div>
                                    <div class="odds-value">1.95</div>
                                </div>
                            </div>
                        </div>

                        <div class="market-group">
                            <div class="market-header">İlk Yarı</div>
                            <div class="market-odds">
                                <div class="odds-cell">
                                    <div class="odds-label">1</div>
                                    <div class="odds-value">2.40</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">X</div>
                                    <div class="odds-value">2.10</div>
                                </div>
                                <div class="odds-cell">
                                    <div class="odds-label">2</div>
                                    <div class="odds-value">3.80</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More Bets -->
                <div class="more-bets">
                    <button onclick="showAllMarkets('{{ $match['id'] }}', '{{ $match['home_team'] }} vs {{ $match['away_team'] }}')">
                        +25 Bahis Seçeneği
                    </button>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-futbol text-5xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Şu anda müsait maç bulunmuyor</h3>
                <p>API'den veri çekilemiyor. Lütfen daha sonra tekrar deneyin.</p>
            </div>
        @endif
    </div>

    <!-- Bet Slip Toggle -->
    <div class="bet-slip-toggle" onclick="toggleBetSlip()">
        <i class="fas fa-ticket-alt text-2xl"></i>
        <span class="bet-count">0</span>
    </div>
    
    <!-- Bet Slip Container -->
    <div class="bet-slip-container" id="betSlipContainer">
        <div class="bet-slip-header">
            <h3 class="text-white font-bold">Bahis Kuponu</h3>
            <span class="close" onclick="toggleBetSlip()">&times;</span>
        </div>
        <div class="bet-slip-items" id="betSlipItems">
            <!-- Kupon içeriği buraya gelecek -->
        </div>
        <div class="bet-slip-footer">
            <div class="mb-2 text-gray-300 text-sm">
                <span>Toplam Oran: </span>
                <span id="totalOdds" class="font-bold text-yellow-400">0.00</span>
            </div>
            <input type="number" 
                   class="bet-amount-input" 
                   id="betAmount" 
                   placeholder="Bahis tutarı" 
                   min="1" 
                   max="10000"
                   oninput="calculatePotentialWin()">
            <div class="mb-3 text-gray-300 text-sm">
                <span>Kazanç: </span>
                <span id="potentialWin" class="font-bold text-green-400">0.00 TL</span>
            </div>
            <button class="place-bet-btn" onclick="placeBet()" id="placeBetBtn">
                Bahis Yap
            </button>
        </div>
    </div>
    
    <!-- All Markets Modal -->
    <div id="allMarketsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalMatchTitle" class="text-white font-bold text-lg"></h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Market içeriği buraya gelecek -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global değişkenler
let betSlip = [];
let currentMatchMarkets = {};

// Sayfa yüklendiğinde kupon verilerini yükle - sadece sports-betting sayfasında
document.addEventListener('DOMContentLoaded', function() {
    // Sadece sports-betting sayfasında çalışsın
    if (window.location.pathname.includes('canli-bahis') || window.location.pathname.includes('sports-betting')) {
        loadBetSlip();
    }
});

// Kupon toggle
function toggleBetSlip() {
    const container = document.getElementById('betSlipContainer');
    container.classList.toggle('active');
}

// Kupona bahis ekle
function addToBetSlip(matchId, market, outcome, odds, description) {
    const eventName = document.querySelector(`[data-match-id="${matchId}"] .team-name`).parentElement.parentElement.innerHTML;
    const homeTeam = document.querySelector(`[data-match-id="${matchId}"] .team-name`).textContent;
    const awayTeam = document.querySelectorAll(`[data-match-id="${matchId}"] .team-name`)[1].textContent;
    
    // API'ye gönder
    fetch('/api/betslip/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            match_id: matchId,
            event_name: `${homeTeam} vs ${awayTeam}`,
            market_type: market,
            selection: outcome,
            selection_name: description,
            odds: parseFloat(odds)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateBetSlipUI(data.betSlip);
            // Toggle selected class
            const selector = `[data-match-id="${matchId}"] [data-market="${market}"][data-outcome="${outcome}"]`;
            const element = document.querySelector(selector);
            if (element) {
                element.classList.toggle('selected');
            }
        }
    });
}

// Kupondan bahis çıkar
function removeFromBetSlip(betId) {
    fetch(`/api/betslip/remove/${betId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateBetSlipUI(data.betSlip);
        }
    });
}

// Kuponu temizle
function clearBetSlip() {
    if (!confirm('Kuponu temizlemek istediğinize emin misiniz?')) return;
    
    fetch('/api/betslip/clear', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateBetSlipUI({items: [], totalOdds: 0, count: 0});
        }
    });
}

// Kupon verilerini yükle
function loadBetSlip() {
    fetch('/api/betslip/')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateBetSlipUI(data.betSlip);
        }
    });
}

// Kupon UI'ını güncelle
function updateBetSlipUI(betSlipData) {
    const container = document.getElementById('betSlipItems');
    const betCount = document.querySelector('.bet-count');
    const totalOdds = document.getElementById('totalOdds');
    
    container.innerHTML = '';
    betCount.textContent = betSlipData.count || 0;
    totalOdds.textContent = betSlipData.totalOdds || '0.00';
    
    if (betSlipData.items && betSlipData.items.length > 0) {
        betSlipData.items.forEach(item => {
            container.innerHTML += `
                <div class="bet-slip-item">
                    <span class="remove-bet" onclick="removeFromBetSlip(${item.id})">
                        <i class="fas fa-times"></i>
                    </span>
                    <div class="text-white font-bold">${item.event_name}</div>
                    <div class="text-gray-400 text-sm">${item.market_type}</div>
                    <div class="flex justify-between mt-2">
                        <span class="text-gray-300">${item.selection_name}</span>
                        <span class="text-yellow-400 font-bold">${item.odds}</span>
                    </div>
                </div>
            `;
        });
    } else {
        container.innerHTML = '<div class="text-center text-gray-400 py-4">Kuponunuz boş</div>';
    }
    
    calculatePotentialWin();
}

// Potansiyel kazancı hesapla
function calculatePotentialWin() {
    const amount = parseFloat(document.getElementById('betAmount').value) || 0;
    const odds = parseFloat(document.getElementById('totalOdds').textContent) || 0;
    const potentialWin = amount * odds;
    
    document.getElementById('potentialWin').textContent = potentialWin.toFixed(2) + ' TL';
}

// Bahis yap
function placeBet() {
    const amount = parseFloat(document.getElementById('betAmount').value);
    
    if (!amount || amount < 1) {
        alert('Lütfen geçerli bir bahis tutarı girin');
        return;
    }
    
    @auth
    const placeBetBtn = document.getElementById('placeBetBtn');
    placeBetBtn.disabled = true;
    placeBetBtn.textContent = 'İşleniyor...';
    
    fetch('/api/betslip/place', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Bahisiniz başarıyla alındı!');
            updateBetSlipUI({items: [], totalOdds: 0, count: 0});
            document.getElementById('betAmount').value = '';
            calculatePotentialWin();
            // Bakiyeyi güncelle
            if (data.newBalance !== undefined) {
                const balanceElements = document.querySelectorAll('.user-balance');
                balanceElements.forEach(el => {
                    el.textContent = data.newBalance.toFixed(2) + ' TL';
                });
            }
        } else {
            alert(data.message || 'Bahis alınırken bir hata oluştu');
        }
    })
    .catch(error => {
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    })
    .finally(() => {
        placeBetBtn.disabled = false;
        placeBetBtn.textContent = 'Bahis Yap';
    });
    @else
    alert('Bahis yapmak için giriş yapmalısınız!');
    window.location.href = '{{ route('login') }}';
    @endauth
}

// Tüm marketleri göster
function showAllMarkets(matchId, matchTitle) {
    const modal = document.getElementById('allMarketsModal');
    const modalTitle = document.getElementById('modalMatchTitle');
    const modalBody = document.getElementById('modalBody');
    
    modalTitle.textContent = matchTitle;
    modalBody.innerHTML = '<div class="text-center text-gray-400"><i class="fas fa-spinner fa-spin text-3xl"></i><br>Yükleniyor...</div>';
    modal.style.display = 'block';
    
    // API'den marketleri çek
    fetch(`/api/sports/match/${matchId}/markets`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.markets) {
            let html = '';
            
            for (const [marketKey, market] of Object.entries(data.markets)) {
                html += `
                    <div class="market-section">
                        <div class="market-title">${market.name}</div>
                        <div class="market-options">
                `;
                
                market.selections.forEach(selection => {
                    html += `
                        <div class="market-option" 
                             onclick="addToBetSlipFromModal('${matchId}', '${marketKey}', '${selection.selection}', ${selection.odds}, '${selection.name}', '${matchTitle}')">
                            <div class="text-sm">${selection.name}</div>
                            <div class="font-bold text-yellow-400">${selection.odds}</div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            }
            
            modalBody.innerHTML = html || '<div class="text-center text-gray-400">Market bulunamadı</div>';
        } else {
            modalBody.innerHTML = '<div class="text-center text-gray-400">Market yüklenemedi</div>';
        }
    })
    .catch(error => {
        modalBody.innerHTML = '<div class="text-center text-gray-400">Bir hata oluştu</div>';
    });
}

// Modal'dan kupona ekle
function addToBetSlipFromModal(matchId, market, outcome, odds, selectionName, eventName) {
    fetch('/api/betslip/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            match_id: matchId,
            event_name: eventName,
            market_type: market,
            selection: outcome,
            selection_name: selectionName,
            odds: parseFloat(odds)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateBetSlipUI(data.betSlip);
            // Modal'daki seçimi işaretle
            event.target.closest('.market-option').classList.toggle('selected');
        }
    });
}

// Modal'ı kapat
function closeModal() {
    document.getElementById('allMarketsModal').style.display = 'none';
}

// Modal dışına tıklandığında kapat
window.onclick = function(event) {
    const modal = document.getElementById('allMarketsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Manuel maç fonksiyonları
function addManualMatchToBetSlip(matchId, market, outcome, odds, description) {
    console.log('Manuel maç kupona ekleniyor:', {matchId, market, outcome, odds, description});
    
    const match = document.querySelector(`[data-match-id="${matchId}"]`);
    if (!match) {
        console.error('Maç bulunamadı:', matchId);
        return;
    }
    
    const teamNames = match.querySelectorAll('.team-name');
    if (teamNames.length < 2) {
        console.error('Takım isimleri bulunamadı');
        return;
    }
    
    const homeTeam = teamNames[0].textContent.trim();
    const awayTeam = teamNames[1].textContent.trim();
    
    // API'ye gönder
    fetch('/api/betslip/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            match_id: matchId,
            event_name: `${homeTeam} vs ${awayTeam}`,
            market_type: market,
            selection: outcome,
            selection_name: description,
            odds: parseFloat(odds)
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Kupon yanıtı:', data);
        if (data.success) {
            updateBetSlipUI(data.betSlip);
            // Toggle selected class
            const selector = `[data-match-id="${matchId}"] [data-market="${market}"][data-outcome="${outcome}"]`;
            const element = document.querySelector(selector);
            if (element) {
                element.classList.toggle('selected');
            }
        } else {
            console.error('Kupon hatası:', data);
        }
    })
    .catch(error => {
        console.error('Fetch hatası:', error);
    });
}

function refreshManualMatch(matchId) {
    location.reload(); // Şimdilik sayfayı yenile
}

function showManualMatchMarkets(matchId, matchTitle) {
    const modal = document.getElementById('allMarketsModal');
    const modalTitle = document.getElementById('modalMatchTitle');
    const modalBody = document.getElementById('modalBody');
    
    modalTitle.textContent = matchTitle;
    modalBody.innerHTML = '<div class="text-center text-gray-400"><i class="fas fa-spinner fa-spin text-3xl"></i><br>Yükleniyor...</div>';
    modal.style.display = 'block';
    
    // Manuel maç marketlerini göster
    fetch(`/api/manual-matches/${matchId}/markets`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.markets) {
            let html = '';
            
            for (const [marketKey, market] of Object.entries(data.markets)) {
                html += `
                    <div class="market-section">
                        <div class="market-title">${market.name}</div>
                        <div class="market-options">
                `;
                
                market.selections.forEach(selection => {
                    html += `
                        <div class="market-option" 
                             onclick="addManualMatchToBetSlip('manual-${matchId}', '${marketKey}', '${selection.selection}', ${selection.odds}, '${selection.name}')">
                            <div class="text-sm">${selection.name}</div>
                            <div class="font-bold text-yellow-400">${selection.odds}</div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            }
            
            modalBody.innerHTML = html || '<div class="text-center text-gray-400">Market bulunamadı</div>';
        } else {
            modalBody.innerHTML = '<div class="text-center text-gray-400">Market yüklenemedi</div>';
        }
    })
    .catch(error => {
        modalBody.innerHTML = '<div class="text-center text-gray-400">Bir hata oluştu</div>';
    });
}

// Her 30 saniyede bir manuel maçların dakikalarını güncelle
setInterval(() => {
    // Manuel maçları kontrol et
    const manualMatches = document.querySelectorAll('[data-match-id^="manual-"]');
    if (manualMatches.length > 0) {
        location.reload();
    }
}, 30000);
</script>
@endpush
