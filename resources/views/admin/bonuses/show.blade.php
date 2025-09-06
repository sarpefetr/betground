@extends('layouts.admin')

@section('title', $bonus->name . ' - Bonus Detayı')
@section('page-title', 'Bonus Detayı')
@section('page-description', $bonus->name . ' bonusunun detay bilgileri')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bonuses.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Bonus Listesi
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.bonuses.edit', $bonus) }}" class="bg-gold text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                <i class="fas fa-edit mr-2"></i>Düzenle
            </a>
            <form method="POST" action="{{ route('admin.bonuses.toggle-status', $bonus) }}" class="inline">
                @csrf
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas {{ $bonus->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                    {{ $bonus->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Bonus Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Bonus Image and Basic Info -->
        <div class="lg:col-span-2 bg-secondary rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bonus Image -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Bonus Görseli</h3>
                    <div class="w-full h-64 bg-accent rounded-lg overflow-hidden">
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
                    </div>
                </div>

                <!-- Bonus Details -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Bonus Bilgileri</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Adı:</span>
                            <span class="font-medium">{{ $bonus->name }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Slug:</span>
                            <span class="font-mono text-sm bg-accent px-2 py-1 rounded">{{ $bonus->slug }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Türü:</span>
                            <span class="px-2 py-1 bg-gold text-black rounded text-sm font-bold">{{ $bonus->bonus_type_display }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Miktarı:</span>
                            <span class="text-gold font-bold text-lg">{{ $bonus->formatted_amount }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Min. Yatırım:</span>
                            <span class="font-medium">₺{{ number_format($bonus->min_deposit, 2) }}</span>
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
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Kullanıcı Limiti:</span>
                            <span class="font-medium">{{ $bonus->user_limit }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6 pt-6 border-t border-accent">
                <h4 class="font-bold text-lg mb-3">Bonus Açıklaması</h4>
                <p class="text-gray-300 leading-relaxed">{{ $bonus->description }}</p>
            </div>

            <!-- Terms and Conditions -->
            @if($bonus->terms_conditions)
                <div class="mt-6 pt-6 border-t border-accent">
                    <h4 class="font-bold text-lg mb-3">Şartlar ve Koşullar</h4>
                    <div class="bg-accent p-4 rounded-lg">
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $bonus->terms_conditions }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Status and Restrictions -->
        <div class="space-y-6">
            <!-- Status Info -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Durum Bilgileri</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Aktif:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $bonus->is_active ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                            {{ $bonus->is_active ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Öne Çıkan:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $bonus->is_featured ? 'bg-gold bg-opacity-20 text-gold' : 'bg-gray-500 bg-opacity-20 text-gray-400' }}">
                            {{ $bonus->is_featured ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Geçerli:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $bonus->isValid() ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                            {{ $bonus->isValid() ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    @if($bonus->valid_from)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Başlangıç:</span>
                            <span class="font-medium text-sm">{{ $bonus->valid_from->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif

                    @if($bonus->valid_until)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Bitiş:</span>
                            <span class="font-medium text-sm {{ $bonus->valid_until->isPast() ? 'text-red-400' : 'text-white' }}">
                                {{ $bonus->valid_until->format('d.m.Y H:i') }}
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Oluşturulma:</span>
                        <span class="font-medium text-sm">{{ $bonus->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Son Güncelleme:</span>
                        <span class="font-medium text-sm">{{ $bonus->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Restrictions -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Kısıtlamalar</h3>
                <div class="space-y-3">
                    @if($bonus->countries)
                        <div>
                            <span class="text-gray-400 block mb-2">Geçerli Ülkeler:</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($bonus->countries as $country)
                                    <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs">{{ $country }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($bonus->currencies)
                        <div>
                            <span class="text-gray-400 block mb-2">Geçerli Para Birimleri:</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($bonus->currencies as $currency)
                                    <span class="px-2 py-1 bg-green-600 text-white rounded text-xs">{{ $currency }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($bonus->usage_limit)
                        <div class="flex justify-between">
                            <span class="text-gray-400">Toplam Limit:</span>
                            <span class="font-bold">{{ number_format($bonus->usage_limit) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Hızlı İşlemler</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.bonuses.toggle-featured', $bonus) }}">
                        @csrf
                        <button type="submit" class="w-full bg-gold text-black py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                            <i class="fas fa-star mr-2"></i>
                            {{ $bonus->is_featured ? 'Öne Çıkandan Kaldır' : 'Öne Çıkar' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.bonuses.toggle-status', $bonus) }}">
                        @csrf
                        <button type="submit" class="w-full {{ $bonus->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 rounded-lg transition-colors">
                            <i class="fas {{ $bonus->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                            {{ $bonus->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                        </button>
                    </form>
                </div>
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
            
            <form method="POST" action="{{ route('admin.bonuses.destroy', $bonus) }}" 
                  onsubmit="return confirm('Bu bonusu silmek istediğinizden emin misiniz?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Bonusu Sil
                </button>
            </form>
        </div>
    @endif
</div>
@endsection



