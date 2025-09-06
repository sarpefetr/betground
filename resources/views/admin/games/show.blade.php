@extends('layouts.admin')

@section('title', $game->name . ' - Oyun Detayı')
@section('page-title', 'Oyun Detayı')
@section('page-description', $game->name . ' oyununun detay bilgileri')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.games.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Oyun Listesi
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.games.edit', $game) }}" class="bg-gold text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                <i class="fas fa-edit mr-2"></i>Düzenle
            </a>
            <form method="POST" action="{{ route('admin.games.toggle-status', $game) }}" class="inline">
                @csrf
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas {{ $game->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                    {{ $game->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Game Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Game Image and Basic Info -->
        <div class="lg:col-span-2 bg-secondary rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Game Image -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Oyun Görseli</h3>
                    <div class="w-full h-64 bg-accent rounded-lg overflow-hidden">
                        @if($game->thumbnail)
                            <img src="{{ $game->thumbnail_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover object-center">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl">
                                @if($game->category === 'slots') 🎰
                                @elseif($game->category === 'casino') 🃏
                                @elseif($game->category === 'sports') ⚽
                                @elseif($game->category === 'esports') 🎮
                                @else 🎯 @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Game Details -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Oyun Bilgileri</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Oyun Adı:</span>
                            <span class="font-medium">{{ $game->name }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Slug:</span>
                            <span class="font-mono text-sm bg-accent px-2 py-1 rounded">{{ $game->slug }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Kategori:</span>
                            <span class="px-2 py-1 bg-gold text-black rounded text-sm font-bold">{{ ucfirst($game->category) }}</span>
                        </div>
                        
                        @if($game->type)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Tür:</span>
                                <span class="font-medium">{{ $game->type }}</span>
                            </div>
                        @endif
                        
                        @if($game->provider)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Sağlayıcı:</span>
                                <span class="font-medium">{{ $game->provider }}</span>
                            </div>
                        @endif
                        
                        @if($game->rtp)
                            <div class="flex justify-between">
                                <span class="text-gray-400">RTP:</span>
                                <span class="text-gold font-bold">{{ $game->rtp }}%</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bahis Limiti:</span>
                            <span class="font-medium">₺{{ number_format($game->min_bet, 2) }} - ₺{{ number_format($game->max_bet, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Sıralama:</span>
                            <span class="font-medium">{{ $game->order_index }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status and Actions -->
        <div class="space-y-6">
            <!-- Status Info -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Durum Bilgileri</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Aktif:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $game->is_active ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                            {{ $game->is_active ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Canlı:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $game->is_live ? 'bg-red-500 bg-opacity-20 text-red-400' : 'bg-gray-500 bg-opacity-20 text-gray-400' }}">
                            {{ $game->is_live ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Öne Çıkan:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $game->is_featured ? 'bg-gold bg-opacity-20 text-gold' : 'bg-gray-500 bg-opacity-20 text-gray-400' }}">
                            {{ $game->is_featured ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Oluşturulma:</span>
                        <span class="font-medium text-sm">{{ $game->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Son Güncelleme:</span>
                        <span class="font-medium text-sm">{{ $game->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Hızlı İşlemler</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.games.toggle-featured', $game) }}">
                        @csrf
                        <button type="submit" class="w-full bg-gold text-black py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                            <i class="fas fa-star mr-2"></i>
                            {{ $game->is_featured ? 'Öne Çıkandan Kaldır' : 'Öne Çıkar' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.games.toggle-status', $game) }}">
                        @csrf
                        <button type="submit" class="w-full {{ $game->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 rounded-lg transition-colors">
                            <i class="fas {{ $game->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                            {{ $game->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">İstatistikler</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Toplam Bahis:</span>
                        <span class="font-bold text-gold">{{ $game->bets->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Toplam Tutar:</span>
                        <span class="font-bold text-green-400">₺{{ number_format($game->bets->sum('amount'), 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kazançlar:</span>
                        <span class="font-bold text-blue-400">₺{{ number_format($game->bets->where('status', 'won')->sum('result'), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bets -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-xl font-bold mb-6">Son Bahisler</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-accent">
                    <tr class="text-left">
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Kullanıcı</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tutar</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Oran</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Potansiyel Kazanç</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Durum</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-accent">
                    @forelse($game->bets as $bet)
                        <tr class="hover:bg-accent transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.users.show', $bet->user) }}" class="text-gold hover:text-yellow-500">
                                    {{ $bet->user->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 font-bold">₺{{ number_format($bet->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $bet->odds ? number_format($bet->odds, 2) : '-' }}</td>
                            <td class="px-4 py-3">₺{{ number_format($bet->potential_win, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{ $bet->status === 'won' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                                    {{ $bet->status === 'lost' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}
                                    {{ $bet->status === 'pending' ? 'bg-yellow-500 bg-opacity-20 text-yellow-400' : '' }}">
                                    {{ ucfirst($bet->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $bet->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                Henüz bu oyuna bahis yapılmamış
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Danger Zone -->
    @if(auth()->user()->isSuperAdmin())
        <div class="bg-red-900 bg-opacity-20 border border-red-600 rounded-xl p-6">
            <h2 class="text-xl font-bold mb-4 text-red-400">
                <i class="fas fa-exclamation-triangle mr-2"></i>Tehlikeli İşlemler
            </h2>
            <p class="text-gray-300 mb-4">Bu işlemler geri alınamaz. Lütfen dikkatli olun.</p>
            
            <form method="POST" action="{{ route('admin.games.destroy', $game) }}" 
                  onsubmit="return confirm('Bu oyunu silmek istediğinizden emin misiniz? Tüm bahis geçmişi de silinecektir!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Oyunu Sil
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
