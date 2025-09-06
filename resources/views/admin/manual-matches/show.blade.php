@extends('layouts.admin')

@section('page-title', 'Maç Detayları')
@section('page-description', $manualMatch->home_team . ' vs ' . $manualMatch->away_team)

@section('content')
<div class="container-fluid">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.manual-matches.index') }}" class="text-gray-400 hover:text-white mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">Maç Detayları</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Maç Bilgileri -->
        <div class="bg-secondary rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gold mb-4">Maç Bilgileri</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-400">Ev Sahibi:</span>
                    <span class="text-white font-bold">{{ $manualMatch->home_team }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Deplasman:</span>
                    <span class="text-white font-bold">{{ $manualMatch->away_team }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Lig:</span>
                    <span class="text-white">{{ $manualMatch->league ?? 'Belirtilmemiş' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Maç Saati:</span>
                    <span class="text-white">{{ $manualMatch->match_time ?? '00:00' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Skor:</span>
                    <span class="text-white text-2xl font-bold">
                        {{ $manualMatch->home_score }} - {{ $manualMatch->away_score }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Durum:</span>
                    <span class="text-white">
                        @if($manualMatch->is_live)
                            <span class="text-red-500">
                                <i class="fas fa-circle animate-pulse mr-1"></i>CANLI ({{ $manualMatch->getCurrentMinuteAttribute() }}')
                            </span>
                        @elseif($manualMatch->status == 'finished')
                            <span class="text-gray-500">Bitti</span>
                        @else
                            <span class="text-green-500">Yakında</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Gol Listesi -->
        <div class="bg-secondary rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gold mb-4">Goller</h2>
            
            @if($manualMatch->goals->count() > 0)
                <div class="space-y-2">
                    @foreach($manualMatch->goals->sortBy('minute') as $goal)
                        <div class="bg-primary rounded p-3 flex items-center justify-between">
                            <div>
                                <span class="text-white font-bold">
                                    @if($goal->team == 'home')
                                        {{ $manualMatch->home_team }}
                                    @else
                                        {{ $manualMatch->away_team }}
                                    @endif
                                </span>
                                @if($goal->scorer)
                                    <span class="text-gray-400 text-sm ml-2">{{ $goal->scorer }}</span>
                                @endif
                                @if($goal->is_penalty)
                                    <span class="text-yellow-400 text-xs ml-2">(P)</span>
                                @endif
                                @if($goal->is_own_goal)
                                    <span class="text-red-400 text-xs ml-2">(KK)</span>
                                @endif
                            </div>
                            <span class="text-yellow-400 font-bold">{{ $goal->minute }}'</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">Henüz gol atılmamış</p>
            @endif
        </div>
    </div>

    <!-- Bahis Oranları -->
    <div class="bg-secondary rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-bold text-gold mb-4">Mevcut Oranlar</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($manualMatch->odds ?? [] as $marketKey => $market)
                <div class="bg-primary rounded p-4">
                    <h3 class="text-white font-bold mb-2">{{ ucfirst(str_replace('_', ' ', $marketKey)) }}</h3>
                    <div class="space-y-1">
                        @foreach($market as $selection => $odd)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">{{ ucfirst($selection) }}:</span>
                                <span class="text-yellow-400 font-bold">{{ $odd }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- İşlemler -->
    <div class="flex justify-end space-x-4 mt-6">
        <a href="{{ route('admin.manual-matches.edit', $manualMatch) }}" 
           class="bg-gold text-black px-6 py-2 rounded hover:bg-yellow-600 transition-colors">
            <i class="fas fa-edit mr-2"></i>Düzenle
        </a>
        @if($manualMatch->is_live)
            <button onclick="finishMatch({{ $manualMatch->id }})" 
                    class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 transition-colors">
                <i class="fas fa-flag-checkered mr-2"></i>Maçı Bitir
            </button>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
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
