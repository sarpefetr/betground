@extends('layouts.admin')

@section('page-title', 'Maç Düzenle')
@section('page-description', $manualMatch->home_team . ' vs ' . $manualMatch->away_team)

@push('styles')
<style>
    .odds-section {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .odds-group {
        margin-bottom: 20px;
    }
    
    .odds-title {
        color: #ffd700;
        font-weight: bold;
        margin-bottom: 10px;
        font-size: 16px;
    }
    
    .odds-inputs {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
    }
    
    .odd-input-group {
        display: flex;
        flex-direction: column;
    }
    
    .odd-input-group label {
        font-size: 12px;
        color: #999;
        margin-bottom: 4px;
    }
    
    .odd-input {
        background: #2a2a2a;
        border: 1px solid #444;
        color: white;
        padding: 8px;
        border-radius: 4px;
        width: 100%;
    }
    
    .live-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        background: #dc2626;
        border-radius: 50%;
        animation: pulse 2s infinite;
        margin-right: 5px;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.manual-matches.index') }}" class="text-gray-400 hover:text-white mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-white">Maç Düzenle</h1>
            @if($manualMatch->is_live)
                <span class="ml-4 flex items-center text-red-500">
                    <span class="live-indicator"></span> CANLI
                </span>
            @endif
        </div>
        
        @if($manualMatch->is_live)
            <div class="text-yellow-400 font-bold text-lg">
                {{ $manualMatch->getCurrentMinuteAttribute() }}. Dakika
            </div>
        @endif
    </div>

    <form action="{{ route('admin.manual-matches.update', $manualMatch) }}" method="POST" class="max-w-4xl">
        @csrf
        @method('PUT')
        
        <!-- Temel Bilgiler -->
        <div class="bg-secondary rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gold mb-4">Maç Bilgileri</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Ev Sahibi Takım</label>
                    <input type="text" 
                           name="home_team" 
                           class="w-full bg-primary text-white rounded px-4 py-2 focus:ring-2 focus:ring-gold"
                           value="{{ $manualMatch->home_team }}"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Deplasman Takımı</label>
                    <input type="text" 
                           name="away_team" 
                           class="w-full bg-primary text-white rounded px-4 py-2 focus:ring-2 focus:ring-gold"
                           value="{{ $manualMatch->away_team }}"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Lig (Opsiyonel)</label>
                    <input type="text" 
                           name="league" 
                           class="w-full bg-primary text-white rounded px-4 py-2 focus:ring-2 focus:ring-gold"
                           value="{{ $manualMatch->league }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Maç Saati</label>
                    <input type="time" 
                           name="match_time" 
                           class="w-full bg-primary text-white rounded px-4 py-2 focus:ring-2 focus:ring-gold"
                           value="{{ $manualMatch->match_time }}"
                           required>
                    <small class="text-gray-500">Maçın başlama saati (Türkiye saati)</small>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Skor - Ev Sahibi</label>
                    <input type="number" 
                           name="home_score" 
                           class="w-full bg-primary text-white rounded px-4 py-2 focus:ring-2 focus:ring-gold"
                           value="{{ $manualMatch->home_score }}"
                           min="0"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Skor - Deplasman</label>
                    <input type="number" 
                           name="away_score" 
                           class="w-full bg-primary text-white rounded px-4 py-2 focus:ring-2 focus:ring-gold"
                           value="{{ $manualMatch->away_score }}"
                           min="0"
                           required>
                </div>
                
                <div class="col-span-2">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" 
                               name="is_live" 
                               value="1"
                               {{ $manualMatch->is_live ? 'checked' : '' }}
                               class="w-5 h-5 text-gold bg-primary rounded focus:ring-gold">
                        <span class="text-white">Maç Canlı</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Bahis Oranları -->
        <div class="bg-secondary rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gold mb-4">Bahis Oranları</h2>
            
            @php
                $odds = $manualMatch->odds ?? \App\Models\ManualMatch::getDefaultOdds();
            @endphp
            
            <!-- Maç Sonucu -->
            <div class="odds-section">
                <div class="odds-title">Maç Sonucu</div>
                <div class="odds-inputs">
                    <div class="odd-input-group">
                        <label>Ev Sahibi (1)</label>
                        <input type="number" 
                               name="odds[match_result][home]" 
                               class="odd-input"
                               value="{{ $odds['match_result']['home'] ?? 2.00 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Beraberlik (X)</label>
                        <input type="number" 
                               name="odds[match_result][draw]" 
                               class="odd-input"
                               value="{{ $odds['match_result']['draw'] ?? 3.20 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Deplasman (2)</label>
                        <input type="number" 
                               name="odds[match_result][away]" 
                               class="odd-input"
                               value="{{ $odds['match_result']['away'] ?? 3.50 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                </div>
            </div>

            <!-- Alt/Üst 2.5 -->
            <div class="odds-section">
                <div class="odds-title">Alt/Üst 2.5 Gol</div>
                <div class="odds-inputs">
                    <div class="odd-input-group">
                        <label>Üst 2.5</label>
                        <input type="number" 
                               name="odds[over_under_2_5][over]" 
                               class="odd-input"
                               value="{{ $odds['over_under_2_5']['over'] ?? 1.85 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Alt 2.5</label>
                        <input type="number" 
                               name="odds[over_under_2_5][under]" 
                               class="odd-input"
                               value="{{ $odds['over_under_2_5']['under'] ?? 1.95 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                </div>
            </div>

            <!-- Karşılıklı Gol -->
            <div class="odds-section">
                <div class="odds-title">Karşılıklı Gol</div>
                <div class="odds-inputs">
                    <div class="odd-input-group">
                        <label>Var</label>
                        <input type="number" 
                               name="odds[both_teams_score][yes]" 
                               class="odd-input"
                               value="{{ $odds['both_teams_score']['yes'] ?? 1.75 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Yok</label>
                        <input type="number" 
                               name="odds[both_teams_score][no]" 
                               class="odd-input"
                               value="{{ $odds['both_teams_score']['no'] ?? 2.05 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                </div>
            </div>

            <!-- Çifte Şans -->
            <div class="odds-section">
                <div class="odds-title">Çifte Şans</div>
                <div class="odds-inputs">
                    <div class="odd-input-group">
                        <label>1X (Ev Sahibi veya Beraberlik)</label>
                        <input type="number" 
                               name="odds[double_chance][1X]" 
                               class="odd-input"
                               value="{{ $odds['double_chance']['1X'] ?? 1.30 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>12 (Ev Sahibi veya Deplasman)</label>
                        <input type="number" 
                               name="odds[double_chance][12]" 
                               class="odd-input"
                               value="{{ $odds['double_chance']['12'] ?? 1.25 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>X2 (Beraberlik veya Deplasman)</label>
                        <input type="number" 
                               name="odds[double_chance][X2]" 
                               class="odd-input"
                               value="{{ $odds['double_chance']['X2'] ?? 1.45 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                </div>
            </div>

            <!-- İlk Yarı Sonucu -->
            <div class="odds-section">
                <div class="odds-title">İlk Yarı Sonucu</div>
                <div class="odds-inputs">
                    <div class="odd-input-group">
                        <label>Ev Sahibi</label>
                        <input type="number" 
                               name="odds[first_half_result][home]" 
                               class="odd-input"
                               value="{{ $odds['first_half_result']['home'] ?? 2.80 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Beraberlik</label>
                        <input type="number" 
                               name="odds[first_half_result][draw]" 
                               class="odd-input"
                               value="{{ $odds['first_half_result']['draw'] ?? 2.20 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Deplasman</label>
                        <input type="number" 
                               name="odds[first_half_result][away]" 
                               class="odd-input"
                               value="{{ $odds['first_half_result']['away'] ?? 4.00 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                </div>
            </div>

            <!-- Toplam Gol -->
            <div class="odds-section">
                <div class="odds-title">Toplam Gol</div>
                <div class="odds-inputs">
                    <div class="odd-input-group">
                        <label>0-1 Gol</label>
                        <input type="number" 
                               name="odds[total_goals][0-1]" 
                               class="odd-input"
                               value="{{ $odds['total_goals']['0-1'] ?? 3.50 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>2-3 Gol</label>
                        <input type="number" 
                               name="odds[total_goals][2-3]" 
                               class="odd-input"
                               value="{{ $odds['total_goals']['2-3'] ?? 2.10 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>4+ Gol</label>
                        <input type="number" 
                               name="odds[total_goals][4+]" 
                               class="odd-input"
                               value="{{ $odds['total_goals']['4+'] ?? 3.00 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                </div>
            </div>

            <!-- Handikap -->
            <div class="odds-section">
                <div class="odds-title">Handikap</div>
                <div class="odds-inputs">
                    <div class="odd-input-group">
                        <label>Ev Sahibi -1</label>
                        <input type="number" 
                               name="odds[handicap][home_-1]" 
                               class="odd-input"
                               value="{{ $odds['handicap']['home_-1'] ?? 3.00 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Ev Sahibi +1</label>
                        <input type="number" 
                               name="odds[handicap][home_+1]" 
                               class="odd-input"
                               value="{{ $odds['handicap']['home_+1'] ?? 1.40 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Deplasman -1</label>
                        <input type="number" 
                               name="odds[handicap][away_-1]" 
                               class="odd-input"
                               value="{{ $odds['handicap']['away_-1'] ?? 4.50 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                    <div class="odd-input-group">
                        <label>Deplasman +1</label>
                        <input type="number" 
                               name="odds[handicap][away_+1]" 
                               class="odd-input"
                               value="{{ $odds['handicap']['away_+1'] ?? 1.20 }}"
                               step="0.01"
                               min="1.01">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.manual-matches.index') }}" 
               class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition-colors">
                İptal
            </a>
            <button type="submit" 
                    class="bg-gold text-black px-6 py-2 rounded hover:bg-yellow-600 transition-colors">
                <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// AJAX ile oran güncelleme
document.querySelectorAll('.odd-input').forEach(input => {
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const matchId = {{ $manualMatch->id }};
        
        timeout = setTimeout(() => {
            const formData = new FormData(document.querySelector('form'));
            const odds = {};
            
            // Form verilerini odds objesine dönüştür
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('odds[')) {
                    const matches = key.match(/odds\[([^\]]+)\]\[([^\]]+)\]/);
                    if (matches) {
                        if (!odds[matches[1]]) odds[matches[1]] = {};
                        odds[matches[1]][matches[2]] = value;
                    }
                }
            }
            
            // AJAX ile güncelle
            fetch(`/admin/manual-matches/${matchId}/update-odds`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ odds: odds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarılı gösterimi
                    input.style.borderColor = '#10b981';
                    setTimeout(() => {
                        input.style.borderColor = '#444';
                    }, 1000);
                }
            });
        }, 500);
    });
});
</script>
@endpush
