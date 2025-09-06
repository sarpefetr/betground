@extends('layouts.admin')

@section('title', $user->name . ' - Cüzdan Yönetimi')
@section('page-title', 'Cüzdan Yönetimi')
@section('page-description', $user->name . ' kullanıcısının cüzdan işlemleri')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.show', $user) }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>{{ $user->name }} Kullanıcısına Dön
        </a>
        <div class="text-sm text-gray-400">
            Kullanıcı ID: {{ $user->id }} | {{ $user->email }}
        </div>
    </div>

    <!-- Wallet Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-secondary rounded-xl p-6 text-center">
            <div class="text-4xl font-bold text-gold mb-2">₺{{ number_format($user->wallet->balance, 2) }}</div>
            <div class="text-gray-400">Ana Bakiye</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-6 text-center">
            <div class="text-4xl font-bold text-blue-400 mb-2">₺{{ number_format($user->wallet->bonus_balance, 2) }}</div>
            <div class="text-gray-400">Bonus Bakiye</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-6 text-center">
            <div class="text-4xl font-bold text-green-400 mb-2">₺{{ number_format($user->wallet->balance + $user->wallet->bonus_balance, 2) }}</div>
            <div class="text-gray-400">Toplam Bakiye</div>
        </div>
    </div>

    <!-- Balance Management -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Balance -->
        <div class="bg-secondary rounded-xl p-6">
            <h3 class="text-xl font-bold mb-4 text-green-400">
                <i class="fas fa-plus mr-2"></i>Bakiye Ekle
            </h3>
            <form method="POST" action="{{ route('admin.wallets.add-balance', $user) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Tutar</label>
                        <input type="number" 
                               name="amount" 
                               step="0.01" 
                               min="0.01" 
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold" 
                               placeholder="0.00"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Tür</label>
                        <select name="type" class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                            <option value="balance">Ana Bakiye</option>
                            <option value="bonus">Bonus Bakiye</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Açıklama (Opsiyonel)</label>
                        <input type="text" 
                               name="description" 
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold" 
                               placeholder="İşlem açıklaması">
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Bakiye Ekle
                    </button>
                </div>
            </form>
        </div>

        <!-- Remove Balance -->
        <div class="bg-secondary rounded-xl p-6">
            <h3 class="text-xl font-bold mb-4 text-red-400">
                <i class="fas fa-minus mr-2"></i>Bakiye Düş
            </h3>
            <form method="POST" action="{{ route('admin.wallets.remove-balance', $user) }}" onsubmit="return confirm('Bu tutarı düşürmek istediğinizden emin misiniz?')">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Tutar</label>
                        <input type="number" 
                               name="amount" 
                               step="0.01" 
                               min="0.01" 
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold" 
                               placeholder="0.00"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Tür</label>
                        <select name="type" class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                            <option value="balance">Ana Bakiye (Mevcut: ₺{{ number_format($user->wallet->balance, 2) }})</option>
                            <option value="bonus">Bonus Bakiye (Mevcut: ₺{{ number_format($user->wallet->bonus_balance, 2) }})</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Açıklama (Opsiyonel)</label>
                        <input type="text" 
                               name="description" 
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold" 
                               placeholder="İşlem açıklaması">
                    </div>
                    
                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-minus mr-2"></i>Bakiye Düş
                    </button>
                </div>
            </form>
        </div>

        <!-- Set Exact Balance -->
        <div class="bg-secondary rounded-xl p-6">
            <h3 class="text-xl font-bold mb-4 text-gold">
                <i class="fas fa-edit mr-2"></i>Bakiye Ayarla
            </h3>
            <form method="POST" action="{{ route('admin.wallets.set-balance', $user) }}" onsubmit="return confirm('Bakiyeyi bu tutarlara ayarlamak istediğinizden emin misiniz?')">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Ana Bakiye</label>
                        <input type="number" 
                               name="balance" 
                               step="0.01" 
                               min="0" 
                               value="{{ $user->wallet->balance }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold" 
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Bonus Bakiye</label>
                        <input type="number" 
                               name="bonus_balance" 
                               step="0.01" 
                               min="0" 
                               value="{{ $user->wallet->bonus_balance }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold" 
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Açıklama (Opsiyonel)</label>
                        <input type="text" 
                               name="description" 
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold" 
                               placeholder="İşlem açıklaması">
                    </div>
                    
                    <button type="submit" class="w-full bg-gold text-black py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Bakiyeyi Ayarla
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-xl font-bold mb-6">İşlem Geçmişi</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-accent">
                    <tr class="text-left">
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tarih</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tür</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Tutar</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Önceki Bakiye</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Sonraki Bakiye</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Durum</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-300">Açıklama</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-accent">
                    @forelse($user->transactions as $transaction)
                        <tr class="hover:bg-accent transition-colors">
                            <td class="px-4 py-3 text-sm">{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $transaction->type === 'deposit' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                                    {{ $transaction->type === 'withdrawal' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}
                                    {{ $transaction->type === 'bet' ? 'bg-blue-500 bg-opacity-20 text-blue-400' : '' }}
                                    {{ $transaction->type === 'win' ? 'bg-gold bg-opacity-20 text-gold' : '' }}
                                    {{ $transaction->type === 'bonus' ? 'bg-purple-500 bg-opacity-20 text-purple-400' : '' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold {{ $transaction->amount >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $transaction->amount >= 0 ? '+' : '' }}₺{{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm">₺{{ number_format($transaction->balance_before, 2) }}</td>
                            <td class="px-4 py-3 text-sm">₺{{ number_format($transaction->balance_after, 2) }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded text-xs {{ $transaction->status === 'completed' ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-yellow-500 bg-opacity-20 text-yellow-400' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $transaction->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                Henüz işlem yapılmamış
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



