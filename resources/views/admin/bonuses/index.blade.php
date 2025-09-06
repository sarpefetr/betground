@extends('layouts.admin')

@section('title', 'Bonus Yönetimi - Admin Panel')
@section('page-title', 'Bonus Yönetimi')
@section('page-description', 'Promosyonları ve bonusları yönetin')

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
                           placeholder="Bonus adı veya açıklama ile ara..."
                           class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                </div>
                
                <select name="bonus_type" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Bonus Türleri</option>
                    @foreach($bonusTypes as $type)
                        <option value="{{ $type }}" {{ request('bonus_type') === $type ? 'selected' : '' }}>
                            @if($type === 'welcome') 🎉 Hoş Geldin
                            @elseif($type === 'daily') 📅 Günlük
                            @elseif($type === 'weekly') 🎉 Haftalık
                            @elseif($type === 'cashback') 💰 Cashback
                            @elseif($type === 'referral') 🤝 Referans
                            @elseif($type === 'vip') 👑 VIP
                            @elseif($type === 'tournament') 🏆 Turnuva
                            @elseif($type === 'special') 🎁 Özel
                            @else {{ ucfirst($type) }} @endif
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

            <!-- Add New Bonus Button -->
            <a href="{{ route('admin.bonuses.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Yeni Bonus
            </a>
        </div>
    </div>

    <!-- Bonuses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bonuses as $bonus)
            <div class="bg-secondary rounded-xl overflow-hidden card-hover">
                <!-- Bonus Image -->
                <div class="relative h-48 bg-gradient-to-br 
                    @if($bonus->bonus_type === 'welcome') from-gold to-yellow-600
                    @elseif($bonus->bonus_type === 'daily') from-green-600 to-emerald-600
                    @elseif($bonus->bonus_type === 'weekly') from-purple-600 to-pink-600
                    @elseif($bonus->bonus_type === 'cashback') from-orange-600 to-red-600
                    @elseif($bonus->bonus_type === 'referral') from-blue-600 to-cyan-600
                    @elseif($bonus->bonus_type === 'vip') from-yellow-600 to-orange-600
                    @elseif($bonus->bonus_type === 'tournament') from-purple-600 to-indigo-600
                    @else from-gray-600 to-slate-600 @endif">
                    
                    @if($bonus->image)
                        <img src="{{ $bonus->image_url }}" alt="{{ $bonus->name }}" class="w-full h-full object-cover object-center">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-6xl">
                            @if($bonus->bonus_type === 'welcome') 🎉
                            @elseif($bonus->bonus_type === 'daily') 📅
                            @elseif($bonus->bonus_type === 'weekly') 🎊
                            @elseif($bonus->bonus_type === 'cashback') 💰
                            @elseif($bonus->bonus_type === 'referral') 🤝
                            @elseif($bonus->bonus_type === 'vip') 👑
                            @elseif($bonus->bonus_type === 'tournament') 🏆
                            @else 🎁 @endif
                        </div>
                    @endif

                    <!-- Status Badges -->
                    <div class="absolute top-2 left-2 flex gap-2">
                        @if($bonus->is_featured)
                            <span class="bg-gold text-black px-2 py-1 rounded text-xs font-bold">
                                ⭐ ÖNE ÇIKAN
                            </span>
                        @endif
                        
                        @if(!$bonus->is_active)
                            <span class="bg-gray-600 text-white px-2 py-1 rounded text-xs">
                                PASİF
                            </span>
                        @elseif(!$bonus->isValid())
                            <span class="bg-red-600 text-white px-2 py-1 rounded text-xs">
                                SÜRESİ DOLMUŞ
                            </span>
                        @endif
                    </div>

                    <!-- Bonus Amount -->
                    <div class="absolute top-2 right-2">
                        <div class="bg-black bg-opacity-75 text-white px-3 py-2 rounded-lg text-center">
                            <div class="text-2xl font-bold text-gold">{{ $bonus->formatted_amount }}</div>
                            <div class="text-xs">{{ $bonus->amount_type === 'percentage' ? 'BONUS' : 'BONUS' }}</div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="absolute bottom-2 right-2 flex gap-1">
                        <form method="POST" action="{{ route('admin.bonuses.toggle-status', $bonus) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-black bg-opacity-50 text-white p-1 rounded hover:bg-opacity-75" title="{{ $bonus->is_active ? 'Pasif Yap' : 'Aktif Yap' }}">
                                <i class="fas {{ $bonus->is_active ? 'fa-eye-slash' : 'fa-eye' }} text-xs"></i>
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.bonuses.toggle-featured', $bonus) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-black bg-opacity-50 text-white p-1 rounded hover:bg-opacity-75" title="{{ $bonus->is_featured ? 'Öne Çıkandan Kaldır' : 'Öne Çıkar' }}">
                                <i class="fas fa-star text-xs {{ $bonus->is_featured ? 'text-gold' : 'text-gray-400' }}"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Bonus Info -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-lg truncate">{{ $bonus->name }}</h3>
                        <span class="px-2 py-1 bg-accent rounded text-xs">{{ $bonus->bonus_type_display }}</span>
                    </div>
                    
                    <p class="text-sm text-gray-400 mb-3 line-clamp-2">{{ Str::limit($bonus->description, 80) }}</p>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Min. Yatırım:</span>
                            <span class="text-gold font-bold">₺{{ number_format($bonus->min_deposit, 2) }}</span>
                        </div>
                        
                        @if($bonus->max_bonus)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Maks. Bonus:</span>
                                <span class="text-green-400 font-bold">₺{{ number_format($bonus->max_bonus, 2) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Çevrim Şartı:</span>
                            <span class="text-blue-400 font-bold">{{ $bonus->wagering_requirement }}x</span>
                        </div>

                        @if($bonus->valid_until)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Geçerlilik:</span>
                                <span class="text-sm {{ $bonus->valid_until->isPast() ? 'text-red-400' : 'text-white' }}">
                                    {{ $bonus->valid_until->format('d.m.Y') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('admin.bonuses.show', $bonus) }}" class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-center text-sm hover:bg-blue-700">
                            <i class="fas fa-eye mr-1"></i>Detay
                        </a>
                        <a href="{{ route('admin.bonuses.edit', $bonus) }}" class="flex-1 bg-gold text-black py-2 px-3 rounded text-center text-sm hover:bg-yellow-500">
                            <i class="fas fa-edit mr-1"></i>Düzenle
                        </a>
                        <form method="POST" action="{{ route('admin.bonuses.destroy', $bonus) }}" class="inline" onsubmit="return confirm('Bu bonusu silmek istediğinizden emin misiniz?')">
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
                    <i class="fas fa-gift"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Henüz bonus eklenmemiş</h3>
                <p class="text-gray-400 mb-6">İlk bonusunuzu ekleyerek başlayın</p>
                <a href="{{ route('admin.bonuses.create') }}" class="bg-gold text-black px-6 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>İlk Bonusu Ekle
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bonuses->hasPages())
        <div class="bg-secondary rounded-xl p-6">
            {{ $bonuses->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($bonusTypes as $type)
            @php
                $count = \App\Models\Bonus::where('bonus_type', $type)->count();
                $activeCount = \App\Models\Bonus::where('bonus_type', $type)->where('is_active', true)->count();
            @endphp
            <div class="bg-secondary rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-gold">{{ $count }}</div>
                <div class="text-sm text-gray-400">
                    @if($type === 'welcome') Hoş Geldin
                    @elseif($type === 'daily') Günlük
                    @elseif($type === 'weekly') Haftalık
                    @elseif($type === 'cashback') Cashback
                    @elseif($type === 'referral') Referans
                    @elseif($type === 'vip') VIP
                    @elseif($type === 'tournament') Turnuva
                    @elseif($type === 'special') Özel
                    @else {{ ucfirst($type) }} @endif
                </div>
                <div class="text-xs text-green-400">{{ $activeCount }} aktif</div>
            </div>
        @endforeach
    </div>
</div>
@endsection



