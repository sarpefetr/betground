@extends('layouts.admin')

@section('page-title', 'Canlı Maçlar')
@section('page-description', 'Canlı maç yönetimi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Canlı Maçlar</h1>
        <a href="{{ route('admin.manual-matches.create') }}" class="bg-gold text-black px-4 py-2 rounded hover:bg-yellow-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Yeni Maç Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Matches Table -->
    <div class="bg-secondary rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-primary">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Maç</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Lig</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Skor</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Dakika</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Durum</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-accent">
                @forelse($matches as $match)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-white">
                            {{ $match->home_team }} vs {{ $match->away_team }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-400">{{ $match->league ?? 'Belirtilmemiş' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <input type="number" 
                                   class="w-12 bg-primary text-white text-center rounded px-2 py-1 text-sm score-input"
                                   data-match-id="{{ $match->id }}"
                                   data-team="home"
                                   value="{{ $match->home_score }}"
                                   min="0">
                            <span class="text-white">-</span>
                            <input type="number" 
                                   class="w-12 bg-primary text-white text-center rounded px-2 py-1 text-sm score-input"
                                   data-match-id="{{ $match->id }}"
                                   data-team="away"
                                   value="{{ $match->away_score }}"
                                   min="0">
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-sm text-gray-400">{{ $match->match_time ?? '00:00' }}</span>
                        @if($match->is_live)
                            <span class="text-sm font-bold text-yellow-400 ml-2">
                                {{ $match->getCurrentMinuteAttribute() }}'
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($match->is_live)
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs toggle-live-btn"
                                    data-match-id="{{ $match->id }}">
                                <i class="fas fa-circle animate-pulse mr-1"></i>CANLI
                            </button>
                        @else
                            <button class="bg-gray-600 text-white px-3 py-1 rounded text-xs toggle-live-btn"
                                    data-match-id="{{ $match->id }}">
                                <i class="fas fa-play mr-1"></i>Başlat
                            </button>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($match->is_live)
                            <button onclick="showGoalModal({{ $match->id }}, '{{ $match->home_team }}', '{{ $match->away_team }}')" 
                                    class="text-green-500 hover:text-green-700 mr-2" title="Gol Ekle">
                                <i class="fas fa-futbol"></i>
                            </button>
                            <button onclick="finishMatch({{ $match->id }})" 
                                    class="text-orange-500 hover:text-orange-700 mr-2" title="Maçı Bitir">
                                <i class="fas fa-flag-checkered"></i>
                            </button>
                        @endif
                        <a href="{{ route('admin.manual-matches.show', $match) }}" 
                           class="text-blue-500 hover:text-blue-700 mr-2" title="Detaylar">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.manual-matches.edit', $match) }}" 
                           class="text-gold hover:text-yellow-600 mr-2" title="Düzenle">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.manual-matches.destroy', $match) }}" 
                              method="POST" 
                              class="inline-block"
                              onsubmit="return confirm('Bu maçı silmek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Sil">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                        Henüz maç eklenmemiş
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $matches->links() }}
    </div>
    
    <!-- Gol Ekleme Modal -->
    <div id="goalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-secondary rounded-lg shadow-xl w-full max-w-md">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Gol Ekle</h3>
                    
                    <form id="goalForm">
                        <input type="hidden" id="goalMatchId">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Takım</label>
                            <select id="goalTeam" class="w-full bg-primary text-white rounded px-4 py-2" required>
                                <option value="">Seçin</option>
                                <option value="home" id="homeOption"></option>
                                <option value="away" id="awayOption"></option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Gol Atan (Opsiyonel)</label>
                            <input type="text" id="goalScorer" class="w-full bg-primary text-white rounded px-4 py-2" placeholder="Oyuncu adı">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Dakika</label>
                            <input type="number" id="goalMinute" class="w-full bg-primary text-white rounded px-4 py-2" min="0" max="120">
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="goalPenalty" class="mr-2">
                                <span class="text-white">Penaltı</span>
                            </label>
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="goalOwnGoal" class="mr-2">
                                <span class="text-white">Kendi Kalesine</span>
                            </label>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeGoalModal()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                İptal
                            </button>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Gol Ekle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Canlı durumu değiştir
document.querySelectorAll('.toggle-live-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const matchId = this.dataset.matchId;
        
        fetch(`/admin/manual-matches/${matchId}/toggle-live`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});

// Skor güncelle
document.querySelectorAll('.score-input').forEach(input => {
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const matchId = this.dataset.matchId;
        const team = this.dataset.team;
        const value = this.value;
        
        timeout = setTimeout(() => {
            const homeScore = document.querySelector(`input[data-match-id="${matchId}"][data-team="home"]`).value;
            const awayScore = document.querySelector(`input[data-match-id="${matchId}"][data-team="away"]`).value;
            
            fetch(`/admin/manual-matches/${matchId}/update-score`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    home_score: homeScore,
                    away_score: awayScore
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarılı
                    input.classList.add('bg-green-600');
                    setTimeout(() => {
                        input.classList.remove('bg-green-600');
                    }, 1000);
                }
            });
        }, 500);
    });
});

// Her 30 saniyede bir sayfayı yenile (dakikaları güncellemek için)
setInterval(() => {
    location.reload();
}, 30000);

// Gol modal fonksiyonları
function showGoalModal(matchId, homeTeam, awayTeam) {
    document.getElementById('goalMatchId').value = matchId;
    document.getElementById('homeOption').textContent = homeTeam;
    document.getElementById('homeOption').value = 'home';
    document.getElementById('awayOption').textContent = awayTeam;
    document.getElementById('awayOption').value = 'away';
    document.getElementById('goalModal').classList.remove('hidden');
}

function closeGoalModal() {
    document.getElementById('goalModal').classList.add('hidden');
    document.getElementById('goalForm').reset();
}

// Gol ekleme formu
document.getElementById('goalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const matchId = document.getElementById('goalMatchId').value;
    const data = {
        team: document.getElementById('goalTeam').value,
        scorer: document.getElementById('goalScorer').value,
        minute: document.getElementById('goalMinute').value,
        is_penalty: document.getElementById('goalPenalty').checked,
        is_own_goal: document.getElementById('goalOwnGoal').checked
    };
    
    fetch(`/admin/manual-matches/${matchId}/add-goal`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeGoalModal();
            location.reload();
        }
    });
});

// Maçı bitir
function finishMatch(matchId) {
    if (confirm('Bu maçı bitirmek istediğinize emin misiniz?')) {
        fetch(`/admin/manual-matches/${matchId}/finish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endpush
