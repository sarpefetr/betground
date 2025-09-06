@extends('layouts.admin')

@section('title', 'Oyun Yönetimi - Admin Panel')
@section('page-title', 'Oyun Yönetimi')
@section('page-description', 'Casino, slot ve diğer oyunları yönetin')

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
                           placeholder="Oyun adı, provider veya slug ile ara..."
                           class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                </div>
                
                <select name="category" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Pasif</option>
                </select>
                
                <button type="submit" class="bg-gold text-black px-6 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>Ara
                </button>
            </form>

            <!-- Add New Game Button -->
            <a href="{{ route('admin.games.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Yeni Oyun
            </a>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($games as $game)
            <div class="bg-secondary rounded-xl overflow-hidden card-hover">
                <!-- Game Thumbnail -->
                <div class="relative h-48 bg-gradient-to-br 
                    @if($game->category === 'slots') from-purple-600 to-blue-600
                    @elseif($game->category === 'casino') from-green-600 to-emerald-600
                    @elseif($game->category === 'sports') from-red-600 to-pink-600
                    @elseif($game->category === 'esports') from-indigo-600 to-purple-600
                    @else from-gray-600 to-slate-600 @endif">
                    
                    @if($game->thumbnail)
                        <img src="{{ $game->thumbnail_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover object-center">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">
                            @if($game->category === 'slots') 🎰
                            @elseif($game->category === 'casino') 🃏
                            @elseif($game->category === 'sports') ⚽
                            @elseif($game->category === 'esports') 🎮
                            @else 🎯 @endif
                        </div>
                    @endif

                    <!-- Status Badges -->
                    <div class="absolute top-2 left-2 flex gap-2">
                        @if($game->is_live)
                            <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                                <i class="fas fa-circle animate-pulse mr-1"></i>CANLI
                            </span>
                        @endif
                        
                        @if($game->is_featured)
                            <span class="bg-gold text-black px-2 py-1 rounded text-xs font-bold">
                                ⭐ ÖNE ÇIKAN
                            </span>
                        @endif
                        
                        @if(!$game->is_active)
                            <span class="bg-gray-600 text-white px-2 py-1 rounded text-xs">
                                PASİF
                            </span>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="absolute top-2 right-2 flex gap-1">
                        <form method="POST" action="{{ route('admin.games.toggle-status', $game) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-black bg-opacity-50 text-white p-1 rounded hover:bg-opacity-75" title="{{ $game->is_active ? 'Pasif Yap' : 'Aktif Yap' }}">
                                <i class="fas {{ $game->is_active ? 'fa-eye-slash' : 'fa-eye' }} text-xs"></i>
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.games.toggle-featured', $game) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-black bg-opacity-50 text-white p-1 rounded hover:bg-opacity-75" title="{{ $game->is_featured ? 'Öne Çıkandan Kaldır' : 'Öne Çıkar' }}">
                                <i class="fas fa-star text-xs {{ $game->is_featured ? 'text-gold' : 'text-gray-400' }}"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Game Info -->
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2 truncate">{{ $game->name }}</h3>
                    
                    <div class="flex justify-between items-center text-sm text-gray-400 mb-3">
                        <span class="px-2 py-1 bg-accent rounded text-xs">{{ ucfirst($game->category) }}</span>
                        @if($game->provider)
                            <span>{{ $game->provider }}</span>
                        @endif
                    </div>

                    @if($game->rtp)
                        <div class="text-sm mb-2">
                            <span class="text-gray-400">RTP:</span>
                            <span class="text-gold font-bold">{{ $game->rtp }}%</span>
                        </div>
                    @endif

                    <div class="text-sm mb-4">
                        <span class="text-gray-400">Bahis Limiti:</span>
                        <span class="text-white">₺{{ number_format($game->min_bet, 2) }} - ₺{{ number_format($game->max_bet, 2) }}</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.games.show', $game) }}" class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-center text-sm hover:bg-blue-700">
                            <i class="fas fa-eye mr-1"></i>Detay
                        </a>
                        <a href="{{ route('admin.games.edit', $game) }}" class="flex-1 bg-gold text-black py-2 px-3 rounded text-center text-sm hover:bg-yellow-500">
                            <i class="fas fa-edit mr-1"></i>Düzenle
                        </a>
                        <form method="POST" action="{{ route('admin.games.destroy', $game) }}" class="inline" onsubmit="return confirm('Bu oyunu silmek istediğinizden emin misiniz?')">
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
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Henüz oyun eklenmemiş</h3>
                <p class="text-gray-400 mb-6">İlk oyununuzu ekleyerek başlayın</p>
                <a href="{{ route('admin.games.create') }}" class="bg-gold text-black px-6 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>İlk Oyunu Ekle
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
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        @foreach($categories as $category)
            @php
                $count = \App\Models\Game::where('category', $category)->count();
                $activeCount = \App\Models\Game::where('category', $category)->where('is_active', true)->count();
            @endphp
            <div class="bg-secondary rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-gold">{{ $count }}</div>
                <div class="text-sm text-gray-400">{{ ucfirst($category) }}</div>
                <div class="text-xs text-green-400">{{ $activeCount }} aktif</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
