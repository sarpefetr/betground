@extends('layouts.admin')

@section('title', $user->name . ' - Kullanıcı Detayı')
@section('page-title', 'Kullanıcı Detayı')
@section('page-description', $user->name . ' kullanıcısının detay bilgileri')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Kullanıcı Listesi
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-gold text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                <i class="fas fa-edit mr-2"></i>Düzenle
            </a>
            <a href="{{ route('admin.wallets.show', $user) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-wallet mr-2"></i>Cüzdan Yönetimi
            </a>
        </div>
    </div>

    <!-- User Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Basic Info -->
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Temel Bilgiler</h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <div class="bg-gold rounded-full p-3 mr-4">
                        <i class="fas fa-user text-black text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">{{ $user->name }}</h3>
                        <p class="text-gray-400">Kullanıcı ID: {{ $user->id }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400">E-posta:</span>
                        <div class="font-medium">{{ $user->email }}</div>
                    </div>
                    <div>
                        <span class="text-gray-400">Telefon:</span>
                        <div class="font-medium">{{ $user->phone ?: 'Belirtilmemiş' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-400">Doğum Tarihi:</span>
                        <div class="font-medium">{{ $user->birth_date ? $user->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-400">Cinsiyet:</span>
                        <div class="font-medium">{{ $user->gender ? ucfirst($user->gender) : 'Belirtilmemiş' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-400">Ülke:</span>
                        <div class="font-medium">{{ $user->country }}</div>
                    </div>
                    <div>
                        <span class="text-gray-400">Para Birimi:</span>
                        <div class="font-medium">{{ $user->currency }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Info -->
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Durum Bilgileri</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Hesap Durumu:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        {{ $user->status === 'active' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                        {{ $user->status === 'suspended' ? 'bg-yellow-500 bg-opacity-20 text-yellow-400' : '' }}
                        {{ $user->status === 'banned' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-400">KYC Durumu:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        {{ $user->kyc_status === 'verified' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                        {{ $user->kyc_status === 'pending' ? 'bg-yellow-500 bg-opacity-20 text-yellow-400' : '' }}
                        {{ $user->kyc_status === 'rejected' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}">
                        {{ ucfirst($user->kyc_status) }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Kayıt Tarihi:</span>
                    <span class="font-medium">{{ $user->created_at->format('d.m.Y H:i') }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Son Giriş:</span>
                    <span class="font-medium">{{ $user->updated_at->diffForHumans() }}</span>
                </div>

                @if($user->referral_code)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Referans Kodu:</span>
                        <span class="font-mono bg-accent px-2 py-1 rounded">{{ $user->referral_code }}</span>
                    </div>
                @endif

                @if($user->referrer)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Davet Eden:</span>
                        <a href="{{ route('admin.users.show', $user->referrer) }}" class="text-gold hover:text-yellow-500">
                            {{ $user->referrer->name }}
                        </a>
                    </div>
                @endif

                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Davet Edilen:</span>
                    <span class="font-medium">{{ $user->referrals->count() }} kişi</span>
                </div>
            </div>

            <!-- Status Toggle -->
            <div class="mt-6 pt-6 border-t border-accent">
                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                    @csrf
                    <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Durum Değiştir
                        @if($user->status === 'active') (Askıya Al)
                        @elseif($user->status === 'suspended') (Aktif Et)
                        @else (Aktif Et) @endif
                    </button>
                </form>
            </div>
        </div>

        <!-- Wallet Info -->
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Cüzdan Bilgileri</h2>
            @if($user->wallet)
                <div class="space-y-4">
                    <div class="text-center p-4 bg-accent rounded-lg">
                        <div class="text-3xl font-bold text-gold mb-2">₺{{ number_format($user->wallet->balance, 2) }}</div>
                        <div class="text-sm text-gray-400">Ana Bakiye</div>
                    </div>
                    
                    <div class="text-center p-4 bg-accent rounded-lg">
                        <div class="text-2xl font-bold text-blue-400 mb-2">₺{{ number_format($user->wallet->bonus_balance, 2) }}</div>
                        <div class="text-sm text-gray-400">Bonus Bakiye</div>
                    </div>
                    
                    <div class="text-center p-4 bg-gold text-black rounded-lg">
                        <div class="text-2xl font-bold mb-2">₺{{ number_format($user->wallet->balance + $user->wallet->bonus_balance, 2) }}</div>
                        <div class="text-sm">Toplam Bakiye</div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href="{{ route('admin.wallets.show', $user) }}" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors block text-center">
                        <i class="fas fa-wallet mr-2"></i>Cüzdan Yönetimi
                    </a>
                </div>
            @else
                <p class="text-gray-400 text-center py-4">Cüzdan bulunamadı</p>
            @endif
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Son İşlemler</h2>
            <div class="space-y-3">
                @forelse($user->transactions as $transaction)
                    <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                        <div class="flex items-center">
                            <div class="mr-3">
                                @if($transaction->type === 'deposit')
                                    <i class="fas fa-arrow-down text-green-400"></i>
                                @elseif($transaction->type === 'withdrawal')
                                    <i class="fas fa-arrow-up text-red-400"></i>
                                @elseif($transaction->type === 'bet')
                                    <i class="fas fa-dice text-blue-400"></i>
                                @elseif($transaction->type === 'win')
                                    <i class="fas fa-trophy text-gold"></i>
                                @else
                                    <i class="fas fa-exchange-alt text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium">{{ ucfirst($transaction->type) }}</div>
                                <div class="text-xs text-gray-400">{{ $transaction->created_at->format('d.m.Y H:i') }}</div>
                                @if($transaction->description)
                                    <div class="text-xs text-gray-500">{{ $transaction->description }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold {{ $transaction->amount >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $transaction->amount >= 0 ? '+' : '' }}₺{{ number_format($transaction->amount, 2) }}
                            </div>
                            <div class="text-xs text-gray-400">₺{{ number_format($transaction->balance_after, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Henüz işlem yapılmamış</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Bets -->
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Son Bahisler</h2>
            <div class="space-y-3">
                @forelse($user->bets as $bet)
                    <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                        <div>
                            <div class="font-medium">
                                @if($bet->game)
                                    {{ $bet->game->name }}
                                @else
                                    Spor Bahisi
                                @endif
                            </div>
                            <div class="text-xs text-gray-400">{{ $bet->created_at->format('d.m.Y H:i') }}</div>
                            @if($bet->odds)
                                <div class="text-xs text-gold">Oran: {{ $bet->odds }}</div>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="font-bold">₺{{ number_format($bet->amount, 2) }}</div>
                            <div class="text-xs 
                                {{ $bet->status === 'won' ? 'text-green-400' : '' }}
                                {{ $bet->status === 'lost' ? 'text-red-400' : '' }}
                                {{ $bet->status === 'pending' ? 'text-yellow-400' : '' }}">
                                {{ ucfirst($bet->status) }}
                            </div>
                            @if($bet->status === 'won')
                                <div class="text-xs text-green-400">+₺{{ number_format($bet->result, 2) }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Henüz bahis yapılmamış</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Deposits and Withdrawals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Deposits -->
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Para Yatırma İşlemleri</h2>
            <div class="space-y-3">
                @forelse($user->deposits as $deposit)
                    <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                        <div>
                            <div class="font-medium">{{ ucfirst($deposit->method) }}</div>
                            <div class="text-xs text-gray-400">{{ $deposit->created_at->format('d.m.Y H:i') }}</div>
                            <div class="text-xs text-gray-500">{{ $deposit->reference_number }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-green-400">+₺{{ number_format($deposit->amount, 2) }}</div>
                            <div class="text-xs 
                                {{ $deposit->status === 'completed' ? 'text-green-400' : '' }}
                                {{ $deposit->status === 'pending' ? 'text-yellow-400' : '' }}
                                {{ $deposit->status === 'failed' ? 'text-red-400' : '' }}">
                                {{ ucfirst($deposit->status) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Henüz yatırım yapılmamış</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Para Çekme İşlemleri</h2>
            <div class="space-y-3">
                @forelse($user->withdrawals as $withdrawal)
                    <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                        <div>
                            <div class="font-medium">{{ ucfirst($withdrawal->method) }}</div>
                            <div class="text-xs text-gray-400">{{ $withdrawal->created_at->format('d.m.Y H:i') }}</div>
                            <div class="text-xs text-gray-500">{{ $withdrawal->reference_number }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-red-400">-₺{{ number_format($withdrawal->amount, 2) }}</div>
                            <div class="text-xs 
                                {{ $withdrawal->status === 'completed' ? 'text-green-400' : '' }}
                                {{ $withdrawal->status === 'pending' ? 'text-yellow-400' : '' }}
                                {{ $withdrawal->status === 'processing' ? 'text-blue-400' : '' }}
                                {{ $withdrawal->status === 'failed' ? 'text-red-400' : '' }}">
                                {{ ucfirst($withdrawal->status) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Henüz çekim yapılmamış</p>
                @endforelse
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
            
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                  onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Kullanıcıyı Sil
                </button>
            </form>
        </div>
    @endif
</div>
@endsection



