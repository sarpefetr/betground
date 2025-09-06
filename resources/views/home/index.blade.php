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
        <h2 class="text-3xl font-bold mb-8 text-center">
            <i class="fas fa-circle text-red-500 animate-pulse mr-2"></i>Canlı Maçlar
        </h2>
        <div id="liveMatchesContainer" class="bg-secondary rounded-xl p-6">
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-gold text-3xl"></i>
                <p class="mt-4 text-gray-400">Canlı maçlar yükleniyor...</p>
            </div>
        </div>
    </section>

    <!-- Live Casino Section -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">
            <i class="fas fa-video text-gold mr-2"></i>Canlı Casino
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @php
                $liveCasinoGames = [
                    ['name' => 'Lightning Roulette', 'provider' => 'Evolution', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Lightning+Roulette'],
                    ['name' => 'Crazy Time', 'provider' => 'Evolution', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Crazy+Time'],
                    ['name' => 'Blackjack VIP', 'provider' => 'Pragmatic', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Blackjack+VIP'],
                    ['name' => 'Speed Baccarat', 'provider' => 'Evolution', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Speed+Baccarat'],
                    ['name' => 'Dream Catcher', 'provider' => 'Evolution', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Dream+Catcher'],
                    ['name' => 'Turkish Roulette', 'provider' => 'Ezugi', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Turkish+Roulette'],
                ];
            @endphp
            
            @foreach($liveCasinoGames as $game)
                <div class="bg-secondary rounded-lg overflow-hidden card-hover cursor-pointer group" onclick="window.location.href='{{ route('live-casino') }}'">
                    <div class="relative">
                        <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}" class="w-full h-32 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="bg-gold text-black px-4 py-2 rounded font-bold">
                                <i class="fas fa-play mr-2"></i>Oyna
                            </button>
                        </div>
                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-circle text-xs mr-1"></i>CANLI
                        </span>
                    </div>
                    <div class="p-3">
                        <h4 class="font-medium text-sm truncate">{{ $game['name'] }}</h4>
                        <p class="text-xs text-gray-400">{{ $game['provider'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('live-casino') }}" class="text-gold hover:text-yellow-500 font-medium">
                <i class="fas fa-arrow-right mr-2"></i>Tüm Canlı Casino Oyunları
            </a>
        </div>
    </section>

    <!-- Slot Games Section -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">
            <i class="fas fa-gamepad text-gold mr-2"></i>Popüler Slotlar
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @php
                $slotGames = [
                    ['name' => 'Gates of Olympus', 'provider' => 'Pragmatic Play', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Gates+of+Olympus'],
                    ['name' => 'Sweet Bonanza', 'provider' => 'Pragmatic Play', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Sweet+Bonanza'],
                    ['name' => 'Book of Dead', 'provider' => 'Play\'n GO', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Book+of+Dead'],
                    ['name' => 'Wanted Dead or a Wild', 'provider' => 'Hacksaw', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Wanted+Dead'],
                    ['name' => 'Big Bass Bonanza', 'provider' => 'Pragmatic Play', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Big+Bass'],
                    ['name' => 'Starlight Princess', 'provider' => 'Pragmatic Play', 'img' => 'https://via.placeholder.com/200x150/1a1a1a/FFD700?text=Starlight+Princess'],
                ];
            @endphp
            
            @foreach($slotGames as $game)
                <div class="bg-secondary rounded-lg overflow-hidden card-hover cursor-pointer group" onclick="window.location.href='{{ route('slots') }}'">
                    <div class="relative">
                        <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}" class="w-full h-32 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="bg-gold text-black px-4 py-2 rounded font-bold">
                                <i class="fas fa-play mr-2"></i>Oyna
                            </button>
                        </div>
                    </div>
                    <div class="p-3">
                        <h4 class="font-medium text-sm truncate">{{ $game['name'] }}</h4>
                        <p class="text-xs text-gray-400">{{ $game['provider'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('slots') }}" class="text-gold hover:text-yellow-500 font-medium">
                <i class="fas fa-arrow-right mr-2"></i>Tüm Slot Oyunları
            </a>
        </div>
    </section>

    <!-- E-Sports Section -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">
            <i class="fas fa-desktop text-gold mr-2"></i>E-Spor Maçları
        </h2>
        <div class="bg-secondary rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $esportsMatches = [
                        [
                            'game' => 'CS:GO',
                            'team1' => 'Natus Vincere',
                            'team2' => 'FaZe Clan',
                            'time' => '16:00',
                            'odds' => ['team1' => 1.85, 'team2' => 2.10],
                            'tournament' => 'ESL Pro League'
                        ],
                        [
                            'game' => 'League of Legends',
                            'team1' => 'T1',
                            'team2' => 'Gen.G',
                            'time' => '14:30',
                            'odds' => ['team1' => 1.95, 'team2' => 1.95],
                            'tournament' => 'LCK Spring'
                        ],
                        [
                            'game' => 'Dota 2',
                            'team1' => 'Team Spirit',
                            'team2' => 'OG',
                            'time' => '18:00',
                            'odds' => ['team1' => 2.20, 'team2' => 1.70],
                            'tournament' => 'DPC WEU'
                        ],
                        [
                            'game' => 'Valorant',
                            'team1' => 'Fnatic',
                            'team2' => 'Team Liquid',
                            'time' => '20:00',
                            'odds' => ['team1' => 1.75, 'team2' => 2.15],
                            'tournament' => 'VCT EMEA'
                        ]
                    ];
                @endphp
                
                @foreach($esportsMatches as $match)
                    <div class="bg-accent rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="bg-gold text-black text-xs px-2 py-1 rounded font-medium">{{ $match['game'] }}</span>
                                <p class="text-xs text-gray-400 mt-1">{{ $match['tournament'] }}</p>
                            </div>
                            <span class="text-sm text-gray-400">{{ $match['time'] }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm">{{ $match['team1'] }}</span>
                                <button onclick="alert('E-Spor bahisleri yakında!')" class="bg-primary hover:bg-gold hover:text-black transition-all px-3 py-1 rounded text-sm font-bold">
                                    {{ number_format($match['odds']['team1'], 2) }}
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm">{{ $match['team2'] }}</span>
                                <button onclick="alert('E-Spor bahisleri yakında!')" class="bg-primary hover:bg-gold hover:text-black transition-all px-3 py-1 rounded text-sm font-bold">
                                    {{ number_format($match['odds']['team2'], 2) }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('esports') }}" class="text-gold hover:text-yellow-500 font-medium">
                    <i class="fas fa-arrow-right mr-2"></i>Tüm E-Spor Maçları
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
// Sayfa yüklendiğinde hata kontrolü ve maçları yükle
document.addEventListener('DOMContentLoaded', function() {
    // Eğer body'de JSON varsa temizle
    const bodyText = document.body.innerText;
    if (bodyText.includes('{"success":true') && bodyText.includes('"betSlip"')) {
        console.error('JSON data leaked to page body, reloading...');
        document.body.innerHTML = '';
        window.location.reload();
        return;
    }
    
    // Canlı maçları yükle
    loadLiveMatchesForHome();
    
    // Her 30 saniyede bir güncelle
    setInterval(loadLiveMatchesForHome, 30000);
});

// Canlı maçları yükle
function loadLiveMatchesForHome() {
    // Manuel maçları çek
    fetch('/api/manual-matches/live')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('liveMatchesContainer');
            
            if (data.matches && data.matches.length > 0) {
                // Sadece ilk 4 maçı göster
                const matches = data.matches.slice(0, 4);
                let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                
                matches.forEach(match => {
                    const minute = match.minute || match.time || '0';
                    const homeScore = match.home_score || match.homeScore || '0';
                    const awayScore = match.away_score || match.awayScore || '0';
                    
                    html += `
                        <div class="bg-accent rounded-lg p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <div class="text-sm text-gray-400 mb-1">${match.league || ''}</div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium mb-1">${match.home_team || match.homeTeam}</div>
                                            <div class="font-medium">${match.away_team || match.awayTeam}</div>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="text-2xl font-bold text-gold">${homeScore} - ${awayScore}</div>
                                            <div class="text-sm text-gray-400">${minute}'</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <button onclick="addToSlipFromHome('${match.id}', '${match.home_team || match.homeTeam} vs ${match.away_team || match.awayTeam}', 'match_result', 'home', '1', ${match.odds?.home || match.bestOdds?.home || 2.00})" 
                                        class="odd-box-home bg-primary hover:bg-gold hover:text-black transition-all p-2 rounded text-center">
                                    <div class="text-xs text-gray-400">1</div>
                                    <div class="font-bold">${(match.odds?.home || match.bestOdds?.home || 2.00).toFixed(2)}</div>
                                </button>
                                <button onclick="addToSlipFromHome('${match.id}', '${match.home_team || match.homeTeam} vs ${match.away_team || match.awayTeam}', 'match_result', 'draw', 'X', ${match.odds?.draw || match.bestOdds?.draw || 3.20})" 
                                        class="odd-box-home bg-primary hover:bg-gold hover:text-black transition-all p-2 rounded text-center">
                                    <div class="text-xs text-gray-400">X</div>
                                    <div class="font-bold">${(match.odds?.draw || match.bestOdds?.draw || 3.20).toFixed(2)}</div>
                                </button>
                                <button onclick="addToSlipFromHome('${match.id}', '${match.home_team || match.homeTeam} vs ${match.away_team || match.awayTeam}', 'match_result', 'away', '2', ${match.odds?.away || match.bestOdds?.away || 3.50})" 
                                        class="odd-box-home bg-primary hover:bg-gold hover:text-black transition-all p-2 rounded text-center">
                                    <div class="text-xs text-gray-400">2</div>
                                    <div class="font-bold">${(match.odds?.away || match.bestOdds?.away || 3.50).toFixed(2)}</div>
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                html += `
                    <div class="text-center mt-6">
                        <a href="/canli-bahis" class="text-gold hover:text-yellow-500 font-medium">
                            <i class="fas fa-arrow-right mr-2"></i>Tüm Canlı Maçları Gör
                        </a>
                    </div>
                `;
                
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-gold text-3xl mb-4"></i>
                        <p class="text-gray-400">Şu anda canlı maç bulunmuyor.</p>
                        <a href="/canli-bahis" class="text-gold hover:text-yellow-500 font-medium mt-4 inline-block">
                            <i class="fas fa-calendar mr-2"></i>Yaklaşan Maçları Gör
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading matches:', error);
            document.getElementById('liveMatchesContainer').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
                    <p class="text-gray-400">Maçlar yüklenirken bir hata oluştu.</p>
                    <button onclick="loadLiveMatchesForHome()" class="text-gold hover:text-yellow-500 font-medium mt-4">
                        <i class="fas fa-redo mr-2"></i>Tekrar Dene
                    </button>
                </div>
            `;
        });
}

// Ana sayfadan kupona ekle
function addToSlipFromHome(matchId, eventName, marketType, selection, selectionName, odds) {
    // Önce kullanıcı giriş yapmış mı kontrol et
    @guest
        alert('Bahis yapmak için lütfen giriş yapın!');
        window.location.href = '/giris';
        return;
    @endguest
    
    // Kupon ekleme isteği
    fetch('/api/betslip/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            match_id: matchId,
            event_name: eventName,
            market_type: marketType,
            selection: selection,
            selection_name: selectionName,
            odds: odds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Buton görünümünü güncelle
            updateButtonState(matchId, marketType, selection);
            
            // Başarı bildirimi
            showNotification('Bahis kupona eklendi!', 'success');
        } else {
            showNotification(data.message || 'Bir hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Error adding to bet slip:', error);
        showNotification('Bir hata oluştu', 'error');
    });
}

// Buton durumunu güncelle
function updateButtonState(matchId, marketType, selection) {
    const buttons = document.querySelectorAll('.odd-box-home');
    buttons.forEach(btn => {
        const onclick = btn.getAttribute('onclick');
        if (onclick && onclick.includes(matchId) && onclick.includes(marketType)) {
            if (onclick.includes(`'${selection}'`)) {
                btn.classList.add('bg-gold', 'text-black');
                btn.classList.remove('bg-primary', 'text-white');
            } else {
                btn.classList.remove('bg-gold', 'text-black');
                btn.classList.add('bg-primary', 'text-white');
            }
        }
    });
}

// Bildirim göster
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    } text-white`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
