@extends('layouts.app')

@section('title', 'Canlı Bahis - BetGround')

@push('styles')
<style>
    /* Mevcut stiller aynı */
    body { background: #0f0f0f; }
    
    .loading {
        text-align: center;
        padding: 50px;
        color: #ffd700;
    }
    
    .loading i {
        font-size: 48px;
        animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        <button onclick="refreshMatches()" class="mt-2 bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-600">
            <i class="fas fa-sync-alt mr-2"></i>Yenile
        </button>
    </div>

    <!-- Matches Container -->
    <div class="matches-container" id="matchesContainer">
        <div class="loading">
            <i class="fas fa-spinner"></i>
            <p class="mt-4">Maçlar yükleniyor...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sayfa yüklendiğinde maçları getir
document.addEventListener('DOMContentLoaded', function() {
    loadMatches();
    
    // Her 30 saniyede bir güncelle
    setInterval(loadMatches, 30000);
});

function loadMatches() {
    // Canlı maçları getir
    fetch('/api/sports/live-matches')
        .then(response => response.json())
        .then(data => {
            console.log('Live matches:', data);
            updateMatchesDisplay(data.matches || [], 'live');
        })
        .catch(error => console.error('Error loading live matches:', error));
    
    // Yaklaşan maçları getir
    fetch('/api/sports/upcoming-matches')
        .then(response => response.json())
        .then(data => {
            console.log('Upcoming matches:', data);
            updateMatchesDisplay(data.matches || [], 'upcoming');
        })
        .catch(error => console.error('Error loading upcoming matches:', error));
}

function refreshMatches() {
    document.getElementById('matchesContainer').innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner"></i>
            <p class="mt-4">Maçlar yenileniyor...</p>
        </div>
    `;
    loadMatches();
}

function updateMatchesDisplay(matches, type) {
    // Bu fonksiyonda maçları göster
    console.log(`${type} matches:`, matches.length);
}
</script>
@endpush
