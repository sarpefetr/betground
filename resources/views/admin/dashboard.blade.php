@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')
@section('page-description', 'Sistem genel bakış ve istatistikler')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-secondary rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Toplam Kullanıcı</h3>
                    <p class="text-3xl font-bold text-gold">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="bg-blue-500 bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-users text-blue-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-400">{{ $stats['active_users'] }}</span> aktif kullanıcı
            </div>
        </div>

        <div class="bg-secondary rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Toplam Yatırım</h3>
                    <p class="text-3xl font-bold text-gold">₺{{ number_format($stats['total_deposits'], 2) }}</p>
                </div>
                <div class="bg-green-500 bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-arrow-down text-green-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-gray-400">Bu ay</span>
            </div>
        </div>

        <div class="bg-secondary rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Toplam Çekim</h3>
                    <p class="text-3xl font-bold text-gold">₺{{ number_format($stats['total_withdrawals'], 2) }}</p>
                </div>
                <div class="bg-red-500 bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-arrow-up text-red-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-yellow-400">{{ $stats['pending_withdrawals'] }}</span> bekleyen
            </div>
        </div>

        <div class="bg-secondary rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-400">Net Gelir</h3>
                    <p class="text-3xl font-bold text-gold">₺{{ number_format($stats['revenue'], 2) }}</p>
                </div>
                <div class="bg-purple-500 bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-gray-400">{{ $stats['total_bets'] }} toplam bahis</span>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-secondary rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Yeni Kullanıcılar</h2>
                <a href="{{ route('admin.users.index') }}" class="text-gold hover:text-yellow-500 text-sm">
                    Tümünü Gör <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recent_users as $user)
                    <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-gold rounded-full p-2 mr-3">
                                <i class="fas fa-user text-black text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">{{ $user->name }}</h4>
                                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-gold font-bold">₺{{ number_format($user->wallet->balance ?? 0, 2) }}</div>
                            <div class="text-xs {{ $user->status === 'active' ? 'text-green-400' : 'text-red-400' }}">
                                {{ ucfirst($user->status) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Henüz yeni kullanıcı yok</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Deposits -->
        <div class="bg-secondary rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Son Yatırımlar</h2>
                <a href="#" class="text-gold hover:text-yellow-500 text-sm">
                    Tümünü Gör <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recent_deposits as $deposit)
                    <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-3">
                                <i class="fas fa-arrow-down text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">{{ $deposit->user->name }}</h4>
                                <p class="text-sm text-gray-400">{{ ucfirst($deposit->method) }}</p>
                                <p class="text-xs text-gray-500">{{ $deposit->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-green-400 font-bold">+₺{{ number_format($deposit->amount, 2) }}</div>
                            <div class="text-xs {{ $deposit->status === 'completed' ? 'text-green-400' : 'text-yellow-400' }}">
                                {{ ucfirst($deposit->status) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Henüz yatırım yok</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pending Withdrawals -->
    @if($pending_withdrawals->isNotEmpty())
        <div class="bg-secondary rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-yellow-400">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Bekleyen Para Çekme İşlemleri
                </h2>
                <span class="bg-yellow-600 text-black px-3 py-1 rounded-full text-sm font-bold">
                    {{ $pending_withdrawals->count() }} işlem
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pending_withdrawals as $withdrawal)
                    <div class="bg-accent p-4 rounded-lg border border-yellow-600">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold">{{ $withdrawal->user->name }}</h4>
                            <span class="text-yellow-400 font-bold">₺{{ number_format($withdrawal->amount, 2) }}</span>
                        </div>
                        <div class="text-sm space-y-1">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Yöntem:</span>
                                <span>{{ ucfirst($withdrawal->method) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Tarih:</span>
                                <span>{{ $withdrawal->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.users.show', $withdrawal->user) }}" class="flex-1 bg-gold text-black text-center py-1 rounded text-sm hover:bg-yellow-500">
                                Detay
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Daily Stats Chart -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-xl font-bold mb-6">Son 7 Gün İstatistikleri</h2>
        <div class="space-y-4">
            @foreach($daily_stats as $stat)
                <div class="flex items-center justify-between p-3 bg-accent rounded-lg">
                    <div class="font-medium">{{ \Carbon\Carbon::parse($stat->date)->format('d.m.Y') }}</div>
                    <div class="flex space-x-6 text-sm">
                        <div class="text-green-400">
                            <i class="fas fa-arrow-down mr-1"></i>₺{{ number_format($stat->deposits, 2) }}
                        </div>
                        <div class="text-red-400">
                            <i class="fas fa-arrow-up mr-1"></i>₺{{ number_format($stat->withdrawals, 2) }}
                        </div>
                        <div class="text-blue-400">
                            <i class="fas fa-dice mr-1"></i>₺{{ number_format($stat->bets, 2) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection



