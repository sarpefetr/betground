@extends('layouts.admin')

@section('title', 'Kullanıcı Yönetimi - Admin Panel')
@section('page-title', 'Kullanıcı Yönetimi')
@section('page-description', 'Sistem kullanıcılarını yönetin')

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
                           placeholder="Ad, email veya telefon ile ara..."
                           class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                </div>
                
                <select name="status" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Askıya Alınmış</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Yasaklı</option>
                </select>
                
                <select name="kyc_status" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm KYC Durumları</option>
                    <option value="pending" {{ request('kyc_status') === 'pending' ? 'selected' : '' }}>Bekleyen</option>
                    <option value="verified" {{ request('kyc_status') === 'verified' ? 'selected' : '' }}>Doğrulanmış</option>
                    <option value="rejected" {{ request('kyc_status') === 'rejected' ? 'selected' : '' }}>Reddedilmiş</option>
                </select>
                
                <button type="submit" class="bg-gold text-black px-6 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>Ara
                </button>
            </form>

            <!-- Add New User Button -->
            <a href="{{ route('admin.users.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Yeni Kullanıcı
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-secondary rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-accent">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Kullanıcı</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">İletişim</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Bakiye</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Durum</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">KYC</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Kayıt Tarihi</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-accent">
                    @forelse($users as $user)
                        <tr class="hover:bg-accent transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-gold rounded-full p-2 mr-3">
                                        <i class="fas fa-user text-black text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium">{{ $user->name }}</h4>
                                        <p class="text-sm text-gray-400">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div>{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-gray-400">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="font-bold text-gold">₺{{ number_format($user->wallet->balance ?? 0, 2) }}</div>
                                    @if($user->wallet && $user->wallet->bonus_balance > 0)
                                        <div class="text-blue-400">+₺{{ number_format($user->wallet->bonus_balance, 2) }} bonus</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $user->status === 'active' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                                    {{ $user->status === 'suspended' ? 'bg-yellow-500 bg-opacity-20 text-yellow-400' : '' }}
                                    {{ $user->status === 'banned' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $user->kyc_status === 'verified' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                                    {{ $user->kyc_status === 'pending' ? 'bg-yellow-500 bg-opacity-20 text-yellow-400' : '' }}
                                    {{ $user->kyc_status === 'rejected' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}">
                                    {{ ucfirst($user->kyc_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $user->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-gold text-black px-3 py-1 rounded text-xs hover:bg-yellow-500">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.wallets.show', $user) }}" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                        <i class="fas fa-wallet"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded text-xs hover:bg-purple-700">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                Kullanıcı bulunamadı
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-accent">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



