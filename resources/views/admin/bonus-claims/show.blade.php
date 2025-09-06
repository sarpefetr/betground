@extends('layouts.admin')

@section('title', 'Bonus Talebi Detayı - ' . $claim->user->name)
@section('page-title', 'Bonus Talebi Detayı')
@section('page-description', $claim->user->name . ' kullanıcısının bonus talebi')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bonus-claims.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Bonus Talepleri Listesi
        </a>
        <div class="text-sm text-gray-400">
            Talep ID: {{ $claim->id }} | {{ $claim->created_at->format('d.m.Y H:i') }}
        </div>
    </div>

    <!-- Claim Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Claim Details -->
        <div class="lg:col-span-2 bg-secondary rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Kullanıcı Bilgileri</h3>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="bg-gold rounded-full p-3">
                            <i class="fas fa-user text-black text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">{{ $claim->user->name }}</h4>
                            <p class="text-gray-400">{{ $claim->user->email }}</p>
                            <p class="text-xs text-gray-500">ID: {{ $claim->user->id }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Hesap Durumu:</span>
                            <span class="px-2 py-1 rounded text-xs {{ $claim->user->status === 'active' ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                                {{ ucfirst($claim->user->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Mevcut Bakiye:</span>
                            <span class="text-gold font-bold">₺{{ number_format($claim->user->wallet->balance ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Bakiye:</span>
                            <span class="text-blue-400 font-bold">₺{{ number_format($claim->user->wallet->bonus_balance ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Üye Tarihi:</span>
                            <span>{{ $claim->user->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bonus Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Bonus Bilgileri</h3>
                    <div class="space-y-3">
                        @if($claim->bonus->image)
                            <div class="w-full h-32 bg-accent rounded-lg overflow-hidden mb-4">
                                <img src="{{ $claim->bonus->image_url }}" alt="{{ $claim->bonus->name }}" class="w-full h-full object-cover object-center">
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus:</span>
                            <span class="font-medium">{{ $claim->bonus->name }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tür:</span>
                            <span class="px-2 py-1 bg-gold text-black rounded text-xs font-bold">{{ $claim->bonus->bonus_type_display }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Oranı:</span>
                            <span class="text-gold font-bold">{{ $claim->bonus->formatted_amount }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Min. Yatırım:</span>
                            <span class="font-medium">₺{{ number_format($claim->bonus->min_deposit, 2) }}</span>
                        </div>
                        
                        @if($claim->bonus->max_bonus)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Maks. Bonus:</span>
                                <span class="text-green-400 font-bold">₺{{ number_format($claim->bonus->max_bonus, 2) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Çevrim Şartı:</span>
                            <span class="text-blue-400 font-bold">{{ $claim->bonus->wagering_requirement }}x</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Claim Details -->
            <div class="mt-6 pt-6 border-t border-accent">
                <h4 class="font-bold text-lg mb-3">Talep Detayları</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Talep Tutarı:</span>
                            <span class="text-gold font-bold">₺{{ number_format($claim->claimed_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Talep Tarihi:</span>
                            <span>{{ $claim->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        @if($claim->awarded_amount)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Verilen Tutar:</span>
                                <span class="text-green-400 font-bold">₺{{ number_format($claim->awarded_amount, 2) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Durum:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $claim->status_color }}">
                                {{ $claim->status_display }}
                            </span>
                        </div>
                        @if($claim->processed_at)
                            <div class="flex justify-between">
                                <span class="text-gray-400">İşlem Tarihi:</span>
                                <span>{{ $claim->processed_at->format('d.m.Y H:i') }}</span>
                            </div>
                        @endif
                        @if($claim->processedBy)
                            <div class="flex justify-between">
                                <span class="text-gray-400">İşlem Yapan:</span>
                                <span>{{ $claim->processedBy->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Messages -->
            @if($claim->user_message || $claim->admin_message)
                <div class="mt-6 pt-6 border-t border-accent">
                    <h4 class="font-bold text-lg mb-3">Mesajlar</h4>
                    
                    @if($claim->user_message)
                        <div class="bg-blue-900 bg-opacity-30 p-4 rounded-lg mb-4">
                            <h5 class="font-medium text-blue-400 mb-2">Kullanıcı Mesajı:</h5>
                            <p class="text-sm text-gray-300">{{ $claim->user_message }}</p>
                        </div>
                    @endif
                    
                    @if($claim->admin_message)
                        <div class="bg-green-900 bg-opacity-30 p-4 rounded-lg">
                            <h5 class="font-medium text-green-400 mb-2">Admin Cevabı:</h5>
                            <p class="text-sm text-gray-300">{{ $claim->admin_message }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            @if($claim->canBeProcessed())
                <!-- Approve Section -->
                <div class="bg-secondary rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-green-400">Talebi Onayla</h3>
                    <form method="POST" action="{{ route('admin.bonus-claims.approve', $claim) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Verilecek Tutar *</label>
                                <input type="number" 
                                       name="awarded_amount" 
                                       value="{{ $claim->claimed_amount }}"
                                       step="0.01" 
                                       min="0.01"
                                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                                       required>
                                <p class="text-xs text-gray-400 mt-1">Talep edilen: ₺{{ number_format($claim->claimed_amount, 2) }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Admin Mesajı</label>
                                <textarea name="admin_message" 
                                          rows="3"
                                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                                          placeholder="Kullanıcıya iletilecek mesaj..."></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>Bonusu Onayla
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Reject Section -->
                <div class="bg-secondary rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-red-400">Talebi Reddet</h3>
                    <form method="POST" action="{{ route('admin.bonus-claims.reject', $claim) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Red Nedeni *</label>
                                <textarea name="admin_message" 
                                          rows="4"
                                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                                          placeholder="Red nedenini detaylı şekilde açıklayın..."
                                          required></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition-colors"
                                    onclick="return confirm('Bu bonus talebini reddetmek istediğinizden emin misiniz?')">
                                <i class="fas fa-ban mr-2"></i>Talebi Reddet
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Already Processed -->
                <div class="bg-secondary rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4">İşlem Durumu</h3>
                    <div class="text-center">
                        <div class="text-4xl mb-4">
                            @if($claim->status === 'approved') ✅
                            @elseif($claim->status === 'rejected') ❌
                            @else ⏳ @endif
                        </div>
                        <p class="text-lg font-medium mb-2">{{ $claim->status_display }}</p>
                        @if($claim->processed_at)
                            <p class="text-sm text-gray-400">{{ $claim->processed_at->format('d.m.Y H:i') }}</p>
                        @endif
                        @if($claim->processedBy)
                            <p class="text-xs text-gray-500">İşlem yapan: {{ $claim->processedBy->name }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- User Actions -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Kullanıcı İşlemleri</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.show', $claim->user) }}" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors block text-center">
                        <i class="fas fa-user mr-2"></i>Kullanıcı Detayı
                    </a>
                    
                    <a href="{{ route('admin.wallets.show', $claim->user) }}" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors block text-center">
                        <i class="fas fa-wallet mr-2"></i>Cüzdan Yönetimi
                    </a>
                    
                    <a href="{{ route('admin.bonuses.show', $claim->bonus) }}" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors block text-center">
                        <i class="fas fa-gift mr-2"></i>Bonus Detayı
                    </a>
                </div>
            </div>
        </div>

        <!-- Bonus Info Panel -->
        <div class="bg-secondary rounded-xl p-6">
            <h3 class="text-xl font-bold mb-4">Bonus Detayları</h3>
            
            @if($claim->bonus->image)
                <div class="w-full h-32 bg-accent rounded-lg overflow-hidden mb-4">
                    <img src="{{ $claim->bonus->image_url }}" alt="{{ $claim->bonus->name }}" class="w-full h-full object-cover object-center">
                </div>
            @endif
            
            <div class="space-y-3 text-sm">
                <div class="text-center p-3 bg-accent rounded-lg">
                    <div class="text-2xl font-bold text-gold mb-1">{{ $claim->bonus->formatted_amount }}</div>
                    <div class="text-xs text-gray-400">{{ $claim->bonus->bonus_type_display }}</div>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-400">Talep Tutarı:</span>
                    <span class="text-gold font-bold">₺{{ number_format($claim->claimed_amount, 2) }}</span>
                </div>
                
                @if($claim->awarded_amount)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Verilen Tutar:</span>
                        <span class="text-green-400 font-bold">₺{{ number_format($claim->awarded_amount, 2) }}</span>
                    </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="text-gray-400">Min. Yatırım:</span>
                    <span>₺{{ number_format($claim->bonus->min_deposit, 2) }}</span>
                </div>
                
                @if($claim->bonus->max_bonus)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Maks. Bonus:</span>
                        <span>₺{{ number_format($claim->bonus->max_bonus, 2) }}</span>
                    </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="text-gray-400">Çevrim Şartı:</span>
                    <span>{{ $claim->bonus->wagering_requirement }}x</span>
                </div>
                
                @if($claim->bonus->valid_until)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Geçerlilik:</span>
                        <span class="{{ $claim->bonus->valid_until->isPast() ? 'text-red-400' : 'text-white' }}">
                            {{ $claim->bonus->valid_until->format('d.m.Y') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bonus History for this User -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-xl font-bold mb-6">{{ $claim->user->name }} - Bonus Geçmişi</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-accent">
                    <tr class="text-left">
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Bonus</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tutar</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Durum</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-accent">
                    @forelse($claim->user->bonusClaims()->with('bonus')->latest()->take(10)->get() as $userClaim)
                        <tr class="hover:bg-accent transition-colors {{ $userClaim->id === $claim->id ? 'bg-gold bg-opacity-10' : '' }}">
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $userClaim->bonus->name }}</div>
                                <div class="text-xs text-gray-400">{{ $userClaim->bonus->bonus_type_display }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gold">₺{{ number_format($userClaim->claimed_amount, 2) }}</div>
                                @if($userClaim->awarded_amount && $userClaim->awarded_amount != $userClaim->claimed_amount)
                                    <div class="text-xs text-green-400">Verilen: ₺{{ number_format($userClaim->awarded_amount, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $userClaim->status_color }}">
                                    {{ $userClaim->status_display }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                {{ $userClaim->created_at->format('d.m.Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                Bu kullanıcının başka bonus talebi yok
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



