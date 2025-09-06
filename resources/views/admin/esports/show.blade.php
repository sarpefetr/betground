@extends('layouts.admin')

@section('title', $esport->name . ' - E-Spor Maç Detayı')
@section('page-title', 'E-Spor Maç Detayı')
@section('page-description', $esport->name . ' maçının detay bilgileri')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.esports.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>E-Spor Listesi
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.esports.edit', $esport) }}" class="bg-gold text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                <i class="fas fa-edit mr-2"></i>Düzenle
            </a>
        </div>
    </div>

    <!-- Match Overview -->
    <div class="bg-secondary rounded-xl p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Match Image -->
            <div>
                <h3 class="text-xl font-bold mb-4">Maç/Turnuva Görseli</h3>
                <div class="w-full h-64 bg-accent rounded-lg overflow-hidden">
                    @if($esport->thumbnail)
                        <img src="{{ $esport->thumbnail_url }}" alt="{{ $esport->name }}" class="w-full h-full object-cover object-center">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-6xl">
                            @if($esport->type === 'Counter-Strike 2') 🔫
                            @elseif($esport->type === 'League of Legends') 🏆
                            @elseif($esport->type === 'Dota 2') ⚔️
                            @elseif($esport->type === 'Valorant') 🎯
                            @else 🎮 @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Match Info -->
            <div>
                <h3 class="text-xl font-bold mb-4">Maç Bilgileri</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Maç Adı:</span>
                        <span class="font-medium">{{ $esport->name }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Oyun:</span>
                        <span class="px-2 py-1 bg-purple-600 text-white rounded text-sm">{{ $esport->type }}</span>
                    </div>
                    
                    @if($esport->provider)
                        <div class="flex justify-between">
                            <span class="text-gray-400">Organizatör:</span>
                            <span class="font-medium">{{ $esport->provider }}</span>
                        </div>
                    @endif
                    
                    @if(isset($esportsData['tournament_name']))
                        <div class="flex justify-between">
                            <span class="text-gray-400">Turnuva:</span>
                            <span class="font-medium">{{ $esportsData['tournament_name'] }}</span>
                        </div>
                    @endif
                    
                    @if(isset($esportsData['match_date']))
                        <div class="flex justify-between">
                            <span class="text-gray-400">Maç Tarihi:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($esportsData['match_date'])->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif
                    
                    @if(isset($esportsData['description']))
                        <div class="pt-3 border-t border-accent">
                            <span class="text-gray-400 block mb-2">Açıklama:</span>
                            <p class="text-sm">{{ $esportsData['description'] }}</p>
                        </div>
                    @endif
                    
                    <!-- Debug Info -->
                    <div class="pt-3 border-t border-accent">
                        <span class="text-gray-400 block mb-2">Debug Bilgileri:</span>
                        <div class="text-xs space-y-1">
                            @if(isset($esportsData['team1_logo']))
                                <div>Team 1 Logo URL: <span class="text-gold">{{ asset($esportsData['team1_logo']) }}</span></div>
                            @endif
                            @if(isset($esportsData['team2_logo']))
                                <div>Team 2 Logo URL: <span class="text-gold">{{ asset($esportsData['team2_logo']) }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div>
                <h3 class="text-xl font-bold mb-4">Durum Bilgileri</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Aktif:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $esport->is_active ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                            {{ $esport->is_active ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Canlı:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $esport->is_live ? 'bg-red-500 bg-opacity-20 text-red-400' : 'bg-gray-500 bg-opacity-20 text-gray-400' }}">
                            {{ $esport->is_live ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Öne Çıkan:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $esport->is_featured ? 'bg-gold bg-opacity-20 text-gold' : 'bg-gray-500 bg-opacity-20 text-gray-400' }}">
                            {{ $esport->is_featured ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Oluşturulma:</span>
                        <span class="font-medium text-sm">{{ $esport->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Son Güncelleme:</span>
                        <span class="font-medium text-sm">{{ $esport->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teams Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Team 1 -->
        <div class="bg-secondary rounded-xl p-6">
            <h3 class="text-xl font-bold mb-4 text-blue-400">
                <i class="fas fa-users mr-2"></i>{{ $esportsData['team1_name'] ?? '1. Takım' }}
            </h3>
            <div class="flex items-center space-x-4">
                @if(isset($esportsData['team1_logo']) && $esportsData['team1_logo'])
                    <div class="w-16 h-16 bg-accent rounded-lg overflow-hidden border-2 border-blue-500">
                        <img src="{{ asset($esportsData['team1_logo']) }}" 
                             alt="{{ $esportsData['team1_name'] ?? 'Team 1' }}" 
                             class="w-full h-full object-cover object-center"
                             onError="this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-full bg-blue-600 flex items-center justify-center text-2xl text-white font-bold\'>{{ substr($esportsData['team1_name'] ?? 'T1', 0, 2) }}</div>';">
                    </div>
                @else
                    <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center text-2xl text-white font-bold border-2 border-blue-500">
                        {{ substr($esportsData['team1_name'] ?? 'T1', 0, 2) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-bold text-lg">{{ $esportsData['team1_name'] ?? '1. Takım' }}</h4>
                    <p class="text-gray-400 text-sm">{{ $esport->type }} Takımı</p>
                    @if(isset($esportsData['team1_logo']) && $esportsData['team1_logo'])
                        <p class="text-xs text-gray-500">Logo: {{ basename($esportsData['team1_logo']) }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Team 2 -->
        <div class="bg-secondary rounded-xl p-6">
            <h3 class="text-xl font-bold mb-4 text-red-400">
                <i class="fas fa-users mr-2"></i>{{ $esportsData['team2_name'] ?? '2. Takım' }}
            </h3>
            <div class="flex items-center space-x-4">
                @if(isset($esportsData['team2_logo']) && $esportsData['team2_logo'])
                    <div class="w-16 h-16 bg-accent rounded-lg overflow-hidden border-2 border-red-500">
                        <img src="{{ asset($esportsData['team2_logo']) }}" 
                             alt="{{ $esportsData['team2_name'] ?? 'Team 2' }}" 
                             class="w-full h-full object-cover object-center"
                             onError="this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-full bg-red-600 flex items-center justify-center text-2xl text-white font-bold\'>{{ substr($esportsData['team2_name'] ?? 'T2', 0, 2) }}</div>';">
                    </div>
                @else
                    <div class="w-16 h-16 bg-red-600 rounded-lg flex items-center justify-center text-2xl text-white font-bold border-2 border-red-500">
                        {{ substr($esportsData['team2_name'] ?? 'T2', 0, 2) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-bold text-lg">{{ $esportsData['team2_name'] ?? '2. Takım' }}</h4>
                    <p class="text-gray-400 text-sm">{{ $esport->type }} Takımı</p>
                    @if(isset($esportsData['team2_logo']) && $esportsData['team2_logo'])
                        <p class="text-xs text-gray-500">Logo: {{ basename($esportsData['team2_logo']) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Match Statistics -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-xl font-bold mb-6">Maç İstatistikleri</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-gold">{{ $esport->bets->count() }}</div>
                <div class="text-sm text-gray-400">Toplam Bahis</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-green-400">₺{{ number_format($esport->bets->sum('amount'), 2) }}</div>
                <div class="text-sm text-gray-400">Toplam Tutar</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-400">{{ $esport->bets->where('status', 'pending')->count() }}</div>
                <div class="text-sm text-gray-400">Bekleyen Bahis</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-400">{{ $esport->bets->whereIn('status', ['won', 'lost'])->count() }}</div>
                <div class="text-sm text-gray-400">Sonuçlanan</div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    @if(auth()->user()->isSuperAdmin())
        <div class="bg-red-900 bg-opacity-20 border border-red-600 rounded-xl p-6">
            <h2 class="text-xl font-bold mb-4 text-red-400">
                <i class="fas fa-exclamation-triangle mr-2"></i>Tehlikeli İşlemler
            </h2>
            <p class="text-gray-300 mb-4">Bu işlemler geri alınamaz. Lütfen dikkatli olun.</p>
            
            <form method="POST" action="{{ route('admin.esports.destroy', $esport) }}" 
                  onsubmit="return confirm('Bu e-spor maçını silmek istediğinizden emin misiniz? Tüm bahis geçmişi de silinecektir!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>E-Spor Maçını Sil
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
