@extends('layouts.admin')

@section('title', 'Yatırım Onayları - Admin Panel')
@section('page-title', 'Yatırım Onayları')
@section('page-description', 'Kullanıcı yatırım taleplerini yönetin')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-secondary rounded-xl p-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Kullanıcı adı, email veya referans no ile ara..."
                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
            </div>
            
            <select name="status" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                <option value="">Tüm Durumlar</option>
                @foreach($statusOptions as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        @if($status === 'pending') ⏳ Bekliyor
                        @elseif($status === 'completed') ✅ Onaylandı
                        @elseif($status === 'failed') ❌ Reddedildi
                        @elseif($status === 'cancelled') 🚫 İptal
                        @else {{ ucfirst($status) }} @endif
                    </option>
                @endforeach
            </select>
            
            <select name="method" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                <option value="">Tüm Yöntemler</option>
                @foreach($methods as $method)
                    <option value="{{ $method }}" {{ request('method') === $method ? 'selected' : '' }}>
                        @if($method === 'bank_transfer') 🏦 Banka Transferi
                        @elseif($method === 'credit_card') 💳 Kredi Kartı
                        @elseif($method === 'crypto') ₿ Kripto Para
                        @elseif($method === 'ewallet') 💰 E-Cüzdan
                        @elseif($method === 'mobile') 📱 Mobil Ödeme
                        @elseif($method === 'atm') 🏧 ATM
                        @else {{ ucfirst($method) }} @endif
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-gold text-black px-6 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                <i class="fas fa-search mr-2"></i>Ara
            </button>
        </form>
    </div>

    <!-- Deposits Table -->
    <div class="bg-secondary rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-accent">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Kullanıcı</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Ödeme Yöntemi</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Tutar</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Durum</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Referans No</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Talep Tarihi</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-accent">
                    @forelse($deposits as $deposit)
                        <tr class="hover:bg-accent transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-gold rounded-full p-2 mr-3">
                                        <i class="fas fa-user text-black text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium">{{ $deposit->user->name }}</h4>
                                        <p class="text-sm text-gray-400">{{ $deposit->user->email }}</p>
                                        <p class="text-xs text-gray-500">
                                            Bakiye: ₺{{ number_format($deposit->user->wallet->balance ?? 0, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="text-gold text-xl mr-2">
                                        @if($deposit->method === 'bank_transfer') <i class="fas fa-university"></i>
                                        @elseif($deposit->method === 'credit_card') <i class="fas fa-credit-card"></i>
                                        @elseif($deposit->method === 'crypto') <i class="fab fa-bitcoin"></i>
                                        @elseif($deposit->method === 'ewallet') <i class="fas fa-wallet"></i>
                                        @elseif($deposit->method === 'mobile') <i class="fas fa-mobile-alt"></i>
                                        @else <i class="fas fa-credit-card"></i> @endif
                                    </div>
                                    <div>
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
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xl font-bold text-gold">₺{{ number_format($deposit->amount, 2) }}</div>
                                <div class="text-xs text-gray-400">{{ $deposit->currency }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $deposit->status === 'completed' ? 'bg-green-500 bg-opacity-20 text-green-400' : '' }}
                                    {{ $deposit->status === 'pending' ? 'bg-yellow-500 bg-opacity-20 text-yellow-400' : '' }}
                                    {{ $deposit->status === 'failed' ? 'bg-red-500 bg-opacity-20 text-red-400' : '' }}
                                    {{ $deposit->status === 'cancelled' ? 'bg-gray-500 bg-opacity-20 text-gray-400' : '' }}">
                                    @if($deposit->status === 'completed') ✅ Onaylandı
                                    @elseif($deposit->status === 'pending') ⏳ Bekliyor
                                    @elseif($deposit->status === 'failed') ❌ Reddedildi
                                    @elseif($deposit->status === 'cancelled') 🚫 İptal
                                    @else {{ ucfirst($deposit->status) }} @endif
                                </span>
                                @if($deposit->processed_at)
                                    <div class="text-xs text-gray-400 mt-1">{{ $deposit->processed_at->format('d.m.Y H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-sm bg-accent px-2 py-1 rounded">{{ $deposit->reference_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div>{{ $deposit->created_at->format('d.m.Y H:i') }}</div>
                                <div class="text-xs text-gray-400">{{ $deposit->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.deposits.show', $deposit) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($deposit->status === 'pending')
                                        <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700"
                                                    onclick="return confirm('Bu yatırımı onaylamak istediğinizden emin misiniz?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button onclick="quickReject({{ $deposit->id }}, '{{ $deposit->user->name }}', {{ $deposit->amount }})" 
                                                class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                Yatırım talebi bulunamadı
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($deposits->hasPages())
            <div class="px-6 py-4 border-t border-accent">
                {{ $deposits->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $totalDeposits = \App\Models\Deposit::count();
            $pendingDeposits = \App\Models\Deposit::where('status', 'pending')->count();
            $completedDeposits = \App\Models\Deposit::where('status', 'completed')->count();
            $totalAmount = \App\Models\Deposit::where('status', 'completed')->sum('amount');
        @endphp
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gold">{{ $totalDeposits }}</div>
            <div class="text-sm text-gray-400">Toplam Talep</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ $pendingDeposits }}</div>
            <div class="text-sm text-gray-400">Bekleyen</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-green-400">{{ $completedDeposits }}</div>
            <div class="text-sm text-gray-400">Onaylanan</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gold">₺{{ number_format($totalAmount, 2) }}</div>
            <div class="text-sm text-gray-400">Toplam Tutar</div>
        </div>
    </div>
</div>

<!-- Quick Reject Modal -->
<div id="quick-reject-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-secondary rounded-xl p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4 text-red-400">Yatırımı Reddet</h3>
        <form id="quick-reject-form" method="POST">
            @csrf
            <div id="reject-details" class="mb-4 text-sm text-gray-300"></div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Red Nedeni *</label>
                <textarea name="admin_note" 
                          rows="3"
                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                          placeholder="Red nedenini açıklayın..."
                          required></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                    Reddet
                </button>
                <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function quickReject(depositId, userName, amount) {
    document.getElementById('quick-reject-form').action = `/admin/deposits/${depositId}/reject`;
    document.getElementById('reject-details').innerHTML = `
        <strong>Kullanıcı:</strong> ${userName}<br>
        <strong>Tutar:</strong> ₺${amount}
    `;
    document.getElementById('quick-reject-modal').classList.remove('hidden');
    document.getElementById('quick-reject-modal').classList.add('flex');
}

function closeRejectModal() {
    document.getElementById('quick-reject-modal').classList.add('hidden');
    document.getElementById('quick-reject-modal').classList.remove('flex');
}
</script>
@endpush
@endsection
