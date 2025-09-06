@extends('layouts.admin')

@section('title', 'Yatırım Detayı - ' . $deposit->reference_number)
@section('page-title', 'Yatırım Talebi Detayı')
@section('page-description', $deposit->user->name . ' kullanıcısının yatırım talebi')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.deposits.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Yatırım Onayları Listesi
        </a>
        <div class="text-sm text-gray-400">
            {{ $deposit->reference_number }} | {{ $deposit->created_at->format('d.m.Y H:i') }}
        </div>
    </div>

    <!-- Deposit Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Deposit Details -->
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
                            <h4 class="font-bold text-lg">{{ $deposit->user->name }}</h4>
                            <p class="text-gray-400">{{ $deposit->user->email }}</p>
                            <p class="text-xs text-gray-500">ID: {{ $deposit->user->id }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Mevcut Bakiye:</span>
                            <span class="text-gold font-bold">₺{{ number_format($deposit->user->wallet->balance ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Bakiye:</span>
                            <span class="text-blue-400 font-bold">₺{{ number_format($deposit->user->wallet->bonus_balance ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Hesap Durumu:</span>
                            <span class="px-2 py-1 rounded text-xs {{ $deposit->user->status === 'active' ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                                {{ ucfirst($deposit->user->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Deposit Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Yatırım Bilgileri</h3>
                    <div class="space-y-3">
                        <div class="text-center p-4 bg-accent rounded-lg">
                            <div class="text-3xl font-bold text-gold mb-1">₺{{ number_format($deposit->amount, 2) }}</div>
                            <div class="text-sm text-gray-400">Yatırım Tutarı</div>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Ödeme Yöntemi:</span>
                            <div class="text-right">
                                <div class="font-medium">
                                    @if(isset($deposit->payment_details['payment_method_name']))
                                        {{ $deposit->payment_details['payment_method_name'] }}
                                    @else
                                        {{ ucfirst($deposit->method) }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400">{{ ucfirst($deposit->method) }}</div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Para Birimi:</span>
                            <span class="font-medium">{{ $deposit->currency }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Talep Tarihi:</span>
                            <span class="font-medium">{{ $deposit->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        
                        @if($deposit->processed_at)
                            <div class="flex justify-between">
                                <span class="text-gray-400">İşlem Tarihi:</span>
                                <span class="font-medium">{{ $deposit->processed_at->format('d.m.Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bank Details (if available) -->
            @if(isset($deposit->payment_details['bank_details']) && $deposit->payment_details['bank_details'])
                <div class="mt-6 pt-6 border-t border-accent">
                    <h4 class="font-bold text-lg mb-4">Banka Bilgileri</h4>
                    <div class="bg-accent p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            @if(isset($deposit->payment_details['bank_details']['bank_name']))
                                <div>
                                    <span class="text-gray-400">Banka:</span>
                                    <div class="text-gold font-bold">{{ $deposit->payment_details['bank_details']['bank_name'] }}</div>
                                </div>
                            @endif
                            @if(isset($deposit->payment_details['bank_details']['bank_iban']))
                                <div>
                                    <span class="text-gray-400">IBAN:</span>
                                    <div class="text-gold font-mono text-sm">{{ $deposit->payment_details['bank_details']['bank_iban'] }}</div>
                                </div>
                            @endif
                            @if(isset($deposit->payment_details['bank_details']['account_holder']))
                                <div>
                                    <span class="text-gray-400">Hesap Sahibi:</span>
                                    <div class="text-gold font-bold">{{ $deposit->payment_details['bank_details']['account_holder'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Message -->
            @if(isset($deposit->payment_details['user_message']) && $deposit->payment_details['user_message'])
                <div class="mt-6 pt-6 border-t border-accent">
                    <h4 class="font-bold text-lg mb-3">Kullanıcı Mesajı</h4>
                    <div class="bg-blue-900 bg-opacity-30 p-4 rounded-lg">
                        <p class="text-sm text-gray-300">{{ $deposit->payment_details['user_message'] }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            @if($deposit->status === 'pending')
                <!-- Approve Section -->
                <div class="bg-secondary rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-green-400">Yatırımı Onayla</h3>
                    <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}">
                        @csrf
                        <div class="space-y-4">
                            <div class="text-center p-4 bg-green-900 bg-opacity-30 rounded-lg">
                                <div class="text-2xl font-bold text-green-400">₺{{ number_format($deposit->amount, 2) }}</div>
                                <div class="text-sm text-gray-400">Kullanıcıya Eklenecek</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Admin Notu (Opsiyonel)</label>
                                <textarea name="admin_note" 
                                          rows="3"
                                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                                          placeholder="Onay hakkında not..."></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors"
                                    onclick="return confirm('Bu yatırımı onaylayıp kullanıcıya ₺{{ number_format($deposit->amount, 2) }} eklemek istediğinizden emin misiniz?')">
                                <i class="fas fa-check mr-2"></i>Yatırımı Onayla
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Reject Section -->
                <div class="bg-secondary rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-red-400">Yatırımı Reddet</h3>
                    <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Red Nedeni *</label>
                                <textarea name="admin_note" 
                                          rows="4"
                                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                                          placeholder="Red nedenini detaylı açıklayın..."
                                          required></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition-colors"
                                    onclick="return confirm('Bu yatırım talebini reddetmek istediğinizden emin misiniz?')">
                                <i class="fas fa-ban mr-2"></i>Yatırımı Reddet
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
                            @if($deposit->status === 'completed') ✅
                            @elseif($deposit->status === 'failed') ❌
                            @elseif($deposit->status === 'cancelled') 🚫
                            @else ⏳ @endif
                        </div>
                        <p class="text-lg font-medium mb-2">
                            @if($deposit->status === 'completed') Onaylandı
                            @elseif($deposit->status === 'failed') Reddedildi
                            @elseif($deposit->status === 'cancelled') İptal Edildi
                            @else {{ ucfirst($deposit->status) }} @endif
                        </p>
                        @if($deposit->processed_at)
                            <p class="text-sm text-gray-400">{{ $deposit->processed_at->format('d.m.Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- User Actions -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Kullanıcı İşlemleri</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.show', $deposit->user) }}" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors block text-center">
                        <i class="fas fa-user mr-2"></i>Kullanıcı Detayı
                    </a>
                    
                    <a href="{{ route('admin.wallets.show', $deposit->user) }}" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors block text-center">
                        <i class="fas fa-wallet mr-2"></i>Cüzdan Yönetimi
                    </a>
                </div>
            </div>

            <!-- Deposit Statistics -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Kullanıcı İstatistikleri</h3>
                <div class="space-y-3 text-sm">
                    @php
                        $userDeposits = $deposit->user->deposits;
                        $totalDeposits = $userDeposits->where('status', 'completed')->sum('amount');
                        $depositCount = $userDeposits->where('status', 'completed')->count();
                        $pendingCount = $userDeposits->where('status', 'pending')->count();
                    @endphp
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Toplam Yatırım:</span>
                        <span class="text-gold font-bold">₺{{ number_format($totalDeposits, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Yatırım Sayısı:</span>
                        <span class="font-medium">{{ $depositCount }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Bekleyen:</span>
                        <span class="text-yellow-400 font-bold">{{ $pendingCount }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Üye Tarihi:</span>
                        <span class="font-medium">{{ $deposit->user->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Panel -->
        <div class="bg-secondary rounded-xl p-6">
            <h3 class="text-xl font-bold mb-4">Yatırım Durumu</h3>
            
            <div class="text-center mb-6">
                <div class="text-4xl mb-3">
                    @if($deposit->status === 'completed') ✅
                    @elseif($deposit->status === 'pending') ⏳
                    @elseif($deposit->status === 'failed') ❌
                    @elseif($deposit->status === 'cancelled') 🚫
                    @else ⚪ @endif
                </div>
                
                <div class="text-xl font-bold mb-2 
                    {{ $deposit->status === 'completed' ? 'text-green-400' : '' }}
                    {{ $deposit->status === 'pending' ? 'text-yellow-400' : '' }}
                    {{ $deposit->status === 'failed' ? 'text-red-400' : '' }}
                    {{ $deposit->status === 'cancelled' ? 'text-gray-400' : '' }}">
                    @if($deposit->status === 'completed') Onaylandı
                    @elseif($deposit->status === 'pending') Bekliyor
                    @elseif($deposit->status === 'failed') Reddedildi
                    @elseif($deposit->status === 'cancelled') İptal Edildi
                    @else {{ ucfirst($deposit->status) }} @endif
                </div>
                
                <div class="text-2xl font-bold text-gold mb-2">₺{{ number_format($deposit->amount, 2) }}</div>
                <div class="text-sm text-gray-400">{{ $deposit->currency }}</div>
            </div>
            
            <div class="space-y-2 text-sm border-t border-accent pt-4">
                <div class="flex justify-between">
                    <span class="text-gray-400">Referans No:</span>
                    <span class="font-mono text-gold">{{ $deposit->reference_number }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-400">Yöntem:</span>
                    <span class="font-medium">{{ ucfirst($deposit->method) }}</span>
                </div>
                
                @if($deposit->processed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-400">İşlem Tarihi:</span>
                        <span class="font-medium">{{ $deposit->processed_at->format('d.m.Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent User Deposits -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-xl font-bold mb-6">{{ $deposit->user->name }} - Yatırım Geçmişi</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-accent">
                    <tr class="text-left">
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Referans</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Yöntem</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tutar</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Durum</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-accent">
                    @foreach($deposit->user->deposits()->latest()->take(10)->get() as $userDeposit)
                        <tr class="hover:bg-accent transition-colors {{ $userDeposit->id === $deposit->id ? 'bg-gold bg-opacity-10' : '' }}">
                            <td class="px-4 py-3 font-mono text-sm">{{ $userDeposit->reference_number }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">
                                    @if(isset($userDeposit->payment_details['payment_method_name']))
                                        {{ $userDeposit->payment_details['payment_method_name'] }}
                                    @else
                                        {{ ucfirst($userDeposit->method) }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-bold text-gold">₺{{ number_format($userDeposit->amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{ $userDeposit->status === 'completed' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                                    {{ $userDeposit->status === 'pending' ? 'bg-yellow-500 bg-opacity-20 text-yellow-400' : '' }}
                                    {{ $userDeposit->status === 'failed' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}">
                                    {{ ucfirst($userDeposit->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $userDeposit->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
