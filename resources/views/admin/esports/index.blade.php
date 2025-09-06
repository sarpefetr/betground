@extends('layouts.admin')

@section('title', 'E-Spor Yönetimi - Admin Panel')
@section('page-title', 'E-Spor Yönetimi')
@section('page-description', 'E-spor maçları, takımları ve turnuvaları yönetin')

@section('content')
<div class="space-y-6">
    <!-- Filters and Actions -->
    <div class="bg-secondary rounded-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search and Filters -->
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Maç adı, takım veya turnuva ile ara..."
                           class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                </div>
                
                <select name="status" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Pasif</option>
                </select>
                
                <button type="submit" class="bg-gold text-black px-6 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>Ara
                </button>
            </form>

            <!-- Add New Match Button -->
            <a href="{{ route('admin.esports.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Yeni E-Spor Maçı
            </a>
        </div>
    </div>

    <!-- E-Sports Matches Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($games as $match)
            @php
                $esportsData = json_decode($match->bet_data, true) ?? [];
            @endphp
            <div class="bg-secondary rounded-xl overflow-hidden card-hover">
                <!-- Match Header -->
                <div class="relative h-48 bg-gradient-to-br from-indigo-600 to-purple-600">
                    @if($match->thumbnail)
                        <img src="{{ $match->thumbnail_url }}" alt="{{ $match->name }}" class="w-full h-full object-cover object-center">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-6xl">
                            @if($match->type === 'Counter-Strike 2') 🔫
                            @elseif($match->type === 'League of Legends') 🏆
                            @elseif($match->type === 'Dota 2') ⚔️
                            @elseif($match->type === 'Valorant') 🎯
                            @else 🎮 @endif
                        </div>
                    @endif

                    <!-- Status Badges -->
                    <div class="absolute top-2 left-2 flex gap-2">
                        @if($match->is_live)
                            <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                                <i class="fas fa-circle animate-pulse mr-1"></i>CANLI
                            </span>
                        @endif
                        
                        @if($match->is_featured)
                            <span class="bg-gold text-black px-2 py-1 rounded text-xs font-bold">
                                ⭐ ÖNE ÇIKAN
                            </span>
                        @endif
                        
                        @if(!$match->is_active)
                            <span class="bg-gray-600 text-white px-2 py-1 rounded text-xs">
                                PASİF
                            </span>
                        @endif
                    </div>

                    <!-- Game Type -->
                    <div class="absolute top-2 right-2">
                        <span class="bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                            {{ $match->type }}
                        </span>
                    </div>
                </div>

                <!-- Match Info -->
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-3 truncate">{{ $match->name }}</h3>
                    
                    <!-- Teams -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if(isset($esportsData['team1_logo']) && $esportsData['team1_logo'])
                                <img src="{{ asset($esportsData['team1_logo']) }}" 
                                     alt="{{ $esportsData['team1_name'] ?? 'Team 1' }}" 
                                     class="w-10 h-10 rounded-lg object-cover object-center border border-gray-600"
                                     onError="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg hidden items-center justify-center text-xs font-bold text-white">{{ substr($esportsData['team1_name'] ?? 'T1', 0, 2) }}</div>
                            @else
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-xs font-bold text-white">{{ substr($esportsData['team1_name'] ?? 'T1', 0, 2) }}</div>
                            @endif
                            <span class="font-bold text-blue-400">{{ $esportsData['team1_name'] ?? 'Team 1' }}</span>
                        </div>
                        
                        <div class="text-gold font-bold text-xl px-4">VS</div>
                        
                        <div class="flex items-center space-x-3">
                            <span class="font-bold text-red-400">{{ $esportsData['team2_name'] ?? 'Team 2' }}</span>
                            @if(isset($esportsData['team2_logo']) && $esportsData['team2_logo'])
                                <img src="{{ asset($esportsData['team2_logo']) }}" 
                                     alt="{{ $esportsData['team2_name'] ?? 'Team 2' }}" 
                                     class="w-10 h-10 rounded-lg object-cover object-center border border-gray-600"
                                     onError="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-10 h-10 bg-red-600 rounded-lg hidden items-center justify-center text-xs font-bold text-white">{{ substr($esportsData['team2_name'] ?? 'T2', 0, 2) }}</div>
                            @else
                                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center text-xs font-bold text-white">{{ substr($esportsData['team2_name'] ?? 'T2', 0, 2) }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Match Details -->
                    @if(isset($esportsData['tournament_name']))
                        <div class="text-sm text-gray-400 mb-2">
                            <i class="fas fa-trophy mr-1"></i>{{ $esportsData['tournament_name'] }}
                        </div>
                    @endif

                    @if(isset($esportsData['match_date']))
                        <div class="text-sm text-gray-400 mb-4">
                            <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($esportsData['match_date'])->format('d.m.Y H:i') }}
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.esports.show', $match) }}" class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-center text-sm hover:bg-blue-700">
                            <i class="fas fa-eye mr-1"></i>Detay
                        </a>
                        <a href="{{ route('admin.esports.edit', $match) }}" class="flex-1 bg-gold text-black py-2 px-3 rounded text-center text-sm hover:bg-yellow-500">
                            <i class="fas fa-edit mr-1"></i>Düzenle
                        </a>
                        <form method="POST" action="{{ route('admin.esports.destroy', $match) }}" class="inline" onsubmit="return confirm('Bu e-spor maçını silmek istediğinizden emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white py-2 px-3 rounded text-sm hover:bg-red-700">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-desktop"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Henüz e-spor maçı eklenmemiş</h3>
                <p class="text-gray-400 mb-6">İlk e-spor maçınızı ekleyerek başlayın</p>
                <a href="{{ route('admin.esports.create') }}" class="bg-gold text-black px-6 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>İlk E-Spor Maçını Ekle
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($games->hasPages())
        <div class="bg-secondary rounded-xl p-6">
            {{ $games->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($esportTypes as $type)
            @php
                $count = \App\Models\Game::where('category', 'esports')->where('type', $type)->count();
                $activeCount = \App\Models\Game::where('category', 'esports')->where('type', $type)->where('is_active', true)->count();
            @endphp
            <div class="bg-secondary rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-gold">{{ $count }}</div>
                <div class="text-sm text-gray-400">{{ $type }}</div>
                <div class="text-xs text-green-400">{{ $activeCount }} aktif</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
