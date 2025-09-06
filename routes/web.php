<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Ana sayfa
Route::get('/', function () {
    return view('home.index');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    // Giriş
    Route::get('/giris', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/giris', [LoginController::class, 'login']);
    
    // Kayıt
    Route::get('/kayit', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/kayit', [RegisterController::class, 'register']);
});

// Çıkış
Route::post('/cikis', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Para İşlemleri
    Route::get('/para-yatir', function () {
        return view('wallet.deposit');
    })->name('deposit');
    
    Route::get('/para-cek', function () {
        return view('wallet.withdraw');
    })->name('withdraw');

    // Bonus İşlemleri
    Route::post('/bonuses/{bonus}/claim', [\App\Http\Controllers\BonusClaimController::class, 'claim'])->name('bonuses.claim');
    Route::get('/my-bonuses', [\App\Http\Controllers\BonusClaimController::class, 'myBonuses'])->name('bonuses.my-bonuses');

    // Dynamic Payment Pages
    Route::get('/payment/{paymentMethod:slug}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payment.method');
    Route::post('/payment/{paymentMethod:slug}/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');

    // Sports Betting API Routes
    Route::prefix('api/sports')->name('api.sports.')->group(function () {
        Route::get('live-matches', [\App\Http\Controllers\SportsBettingController::class, 'getLiveMatches'])->name('live-matches');
        Route::get('upcoming-matches', [\App\Http\Controllers\SportsBettingController::class, 'getUpcomingMatches'])->name('upcoming-matches');
        Route::get('match/{matchId}', [\App\Http\Controllers\SportsBettingController::class, 'getMatchDetails'])->name('match-details');
        Route::get('match/{matchId}/markets', [\App\Http\Controllers\SportsBettingController::class, 'getMatchAllMarkets'])->name('match-markets');
        Route::post('match/{matchId}/refresh-odds', [\App\Http\Controllers\SportsBettingController::class, 'refreshOdds'])->name('refresh-odds');
    });
    
    // Bet Slip Routes
    Route::prefix('api/betslip')->name('api.betslip.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BetSlipController::class, 'getBetSlip'])->name('get');
        Route::post('/add', [\App\Http\Controllers\BetSlipController::class, 'addToBetSlip'])->name('add');
        Route::delete('/remove/{id}', [\App\Http\Controllers\BetSlipController::class, 'removeFromBetSlip'])->name('remove');
        Route::delete('/clear', [\App\Http\Controllers\BetSlipController::class, 'clearBetSlip'])->name('clear');
        Route::post('/place', [\App\Http\Controllers\BetSlipController::class, 'placeBet'])->name('place');
    });
    
    // Manuel Maç API Routes (Public)
    Route::get('/api/manual-matches/{manualMatch}/markets', [\App\Http\Controllers\ManualMatchController::class, 'getMarkets'])->name('api.manual-matches.markets');
});

// Oyun Sayfaları (Giriş gerektirmez ama giriş yapılmışsa kullanıcı bilgisi gösterilir)
Route::get('/canli-casino', function () {
    return view('games.live-casino');
})->name('live-casino');

Route::get('/slot', function () {
    return view('games.slots');
})->name('slots');

Route::get('/canli-bahis', [\App\Http\Controllers\SportsBettingController::class, 'index'])->name('sports-betting');

Route::get('/oyunlar', function () {
    return view('games.index');
})->name('games');

Route::get('/e-sporlar', function () {
    return view('games.esports');
})->name('esports');

Route::get('/sanal-sporlar', function () {
    return view('games.virtual-sports');
})->name('virtual-sports');

Route::get('/promosyonlar', function () {
    return view('promotions.index');
})->name('promotions');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Wallet Management
    Route::get('users/{user}/wallet', [\App\Http\Controllers\Admin\WalletController::class, 'show'])->name('wallets.show');
    Route::post('users/{user}/wallet/add-balance', [\App\Http\Controllers\Admin\WalletController::class, 'addBalance'])->name('wallets.add-balance');
    Route::post('users/{user}/wallet/remove-balance', [\App\Http\Controllers\Admin\WalletController::class, 'removeBalance'])->name('wallets.remove-balance');
    Route::post('users/{user}/wallet/set-balance', [\App\Http\Controllers\Admin\WalletController::class, 'setBalance'])->name('wallets.set-balance');
    
    // Deposit Approvals
    Route::get('deposits', [\App\Http\Controllers\Admin\DepositController::class, 'index'])->name('deposits.index');
    Route::get('deposits/{deposit}', [\App\Http\Controllers\Admin\DepositController::class, 'show'])->name('deposits.show');
    Route::post('deposits/{deposit}/approve', [\App\Http\Controllers\Admin\DepositController::class, 'approve'])->name('deposits.approve');
    Route::post('deposits/{deposit}/reject', [\App\Http\Controllers\Admin\DepositController::class, 'reject'])->name('deposits.reject');
    
    // Game Management
    Route::resource('games', \App\Http\Controllers\Admin\GameController::class);
    Route::post('games/{game}/toggle-status', [\App\Http\Controllers\Admin\GameController::class, 'toggleStatus'])->name('games.toggle-status');
    Route::post('games/{game}/toggle-featured', [\App\Http\Controllers\Admin\GameController::class, 'toggleFeatured'])->name('games.toggle-featured');
    
    // E-Sports Management
    Route::resource('esports', \App\Http\Controllers\Admin\EsportsController::class);
    Route::post('esports/{esport}/toggle-status', [\App\Http\Controllers\Admin\EsportsController::class, 'toggleStatus'])->name('esports.toggle-status');
    Route::post('esports/{esport}/toggle-featured', [\App\Http\Controllers\Admin\EsportsController::class, 'toggleFeatured'])->name('esports.toggle-featured');
    
    // Bonus Management
    Route::resource('bonuses', \App\Http\Controllers\Admin\BonusController::class);
    Route::post('bonuses/{bonus}/toggle-status', [\App\Http\Controllers\Admin\BonusController::class, 'toggleStatus'])->name('bonuses.toggle-status');
    Route::post('bonuses/{bonus}/toggle-featured', [\App\Http\Controllers\Admin\BonusController::class, 'toggleFeatured'])->name('bonuses.toggle-featured');
    
    // Bonus Claims Management
    Route::get('bonus-claims', [\App\Http\Controllers\Admin\BonusClaimController::class, 'index'])->name('bonus-claims.index');
    Route::get('bonus-claims/{claim}', [\App\Http\Controllers\Admin\BonusClaimController::class, 'show'])->name('bonus-claims.show');
    Route::post('bonus-claims/{claim}/approve', [\App\Http\Controllers\Admin\BonusClaimController::class, 'approve'])->name('bonus-claims.approve');
    Route::post('bonus-claims/{claim}/reject', [\App\Http\Controllers\Admin\BonusClaimController::class, 'reject'])->name('bonus-claims.reject');
    Route::post('bonus-claims/bulk', [\App\Http\Controllers\Admin\BonusClaimController::class, 'bulkAction'])->name('bonus-claims.bulk');
    
    // Payment Methods Management
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class);
    Route::post('payment-methods/{payment_method}/toggle-status', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
    Route::post('payment-methods/{payment_method}/toggle-featured', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleFeatured'])->name('payment-methods.toggle-featured');
    
    // Manuel Maçlar
    Route::resource('manual-matches', \App\Http\Controllers\Admin\ManualMatchController::class);
    Route::post('manual-matches/{manualMatch}/toggle-live', [\App\Http\Controllers\Admin\ManualMatchController::class, 'toggleLive'])->name('manual-matches.toggle-live');
    Route::post('manual-matches/{manualMatch}/update-score', [\App\Http\Controllers\Admin\ManualMatchController::class, 'updateScore'])->name('manual-matches.update-score');
    Route::post('manual-matches/{manualMatch}/update-odds', [\App\Http\Controllers\Admin\ManualMatchController::class, 'updateOdds'])->name('manual-matches.update-odds');
    Route::post('manual-matches/{manualMatch}/add-goal', [\App\Http\Controllers\Admin\ManualMatchController::class, 'addGoal'])->name('manual-matches.add-goal');
    Route::post('manual-matches/{manualMatch}/finish', [\App\Http\Controllers\Admin\ManualMatchController::class, 'finishMatch'])->name('manual-matches.finish');
});