@extends('layouts.admin')

@section('title', 'Bonus Talepleri - Admin Panel')
@section('page-title', 'Bonus Talepleri')
@section('page-description', 'Kullanıcı bonus taleplerini yönetin')

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
                           placeholder="Kullanıcı adı, email veya bonus adı ile ara..."
                           class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                </div>
                
                <select name="status" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Durumlar</option>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            @if($status === 'pending') ⏳ Bekliyor
                            @elseif($status === 'approved') ✅ Onaylandı
                            @elseif($status === 'rejected') ❌ Reddedildi
                            @else {{ ucfirst($status) }} @endif
                        </option>
                    @endforeach
                </select>
                
                <select name="bonus_type" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Bonus Türleri</option>
                    @foreach($bonusTypes as $type)
                        <option value="{{ $type }}" {{ request('bonus_type') === $type ? 'selected' : '' }}>
                            @if($type === 'welcome') 🎉 Hoş Geldin
                            @elseif($type === 'daily') 📅 Günlük
                            @elseif($type === 'weekly') 🎊 Haftalık
                            @elseif($type === 'cashback') 💰 Cashback
                            @elseif($type === 'referral') 🤝 Referans
                            @elseif($type === 'vip') 👑 VIP
                            @elseif($type === 'tournament') 🏆 Turnuva
                            @elseif($type === 'special') 🎁 Özel
                            @else {{ ucfirst($type) }} @endif
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="bg-gold text-black px-6 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>Ara
                </button>
            </form>

            <!-- Bulk Actions -->
            <div class="flex gap-2">
                <button onclick="showBulkApproveModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check-double mr-2"></i>Toplu Onayla
                </button>
                <button onclick="showBulkRejectModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-ban mr-2"></i>Toplu Reddet
                </button>
            </div>
        </div>
    </div>

    <!-- Claims Table -->
    <div class="bg-secondary rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-accent">
                    <tr class="text-left">
                        <th class="px-4 py-3">
                            <input type="checkbox" id="select-all" class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2">
                        </th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Kullanıcı</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Bonus</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Talep Tutarı</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Durum</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">Talep Tarihi</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-300">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-accent">
                    @forelse($claims as $claim)
                        <tr class="hover:bg-accent transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" 
                                       name="claim_ids[]" 
                                       value="{{ $claim->id }}" 
                                       class="claim-checkbox w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                       {{ $claim->status !== 'pending' ? 'disabled' : '' }}>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-gold rounded-full p-2 mr-3">
                                        <i class="fas fa-user text-black text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium">{{ $claim->user->name }}</h4>
                                        <p class="text-sm text-gray-400">{{ $claim->user->email }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $claim->user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($claim->bonus->image)
                                        <img src="{{ $claim->bonus->image_url }}" alt="{{ $claim->bonus->name }}" class="w-10 h-10 rounded mr-3 object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-accent rounded mr-3 flex items-center justify-center text-lg">
                                            @if($claim->bonus->bonus_type === 'welcome') 🎉
                                            @elseif($claim->bonus->bonus_type === 'daily') 📅
                                            @elseif($claim->bonus->bonus_type === 'weekly') 🎊
                                            @elseif($claim->bonus->bonus_type === 'cashback') 💰
                                            @elseif($claim->bonus->bonus_type === 'referral') 🤝
                                            @elseif($claim->bonus->bonus_type === 'vip') 👑
                                            @elseif($claim->bonus->bonus_type === 'tournament') 🏆
                                            @else 🎁 @endif
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-medium">{{ $claim->bonus->name }}</h4>
                                        <p class="text-sm text-gray-400">{{ $claim->bonus->bonus_type_display }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gold">₺{{ number_format($claim->claimed_amount, 2) }}</div>
                                @if($claim->awarded_amount && $claim->awarded_amount != $claim->claimed_amount)
                                    <div class="text-sm text-green-400">Verilen: ₺{{ number_format($claim->awarded_amount, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $claim->status_color }}">
                                    {{ $claim->status_display }}
                                </span>
                                @if($claim->processed_at)
                                    <div class="text-xs text-gray-400 mt-1">{{ $claim->processed_at->format('d.m.Y H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <div>{{ $claim->created_at->format('d.m.Y H:i') }}</div>
                                <div class="text-xs">{{ $claim->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.bonus-claims.show', $claim) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($claim->canBeProcessed())
                                        <button onclick="quickApprove({{ $claim->id }}, '{{ $claim->user->name }}', '{{ $claim->bonus->name }}', {{ $claim->claimed_amount }})" 
                                                class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="quickReject({{ $claim->id }}, '{{ $claim->user->name }}', '{{ $claim->bonus->name }}')" 
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
                                Bonus talebi bulunamadı
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($claims->hasPages())
            <div class="px-6 py-4 border-t border-accent">
                {{ $claims->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $totalClaims = \App\Models\UserBonusClaim::count();
            $pendingClaims = \App\Models\UserBonusClaim::where('status', 'pending')->count();
            $approvedClaims = \App\Models\UserBonusClaim::where('status', 'approved')->count();
            $rejectedClaims = \App\Models\UserBonusClaim::where('status', 'rejected')->count();
        @endphp
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gold">{{ $totalClaims }}</div>
            <div class="text-sm text-gray-400">Toplam Talep</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ $pendingClaims }}</div>
            <div class="text-sm text-gray-400">Bekleyen</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-green-400">{{ $approvedClaims }}</div>
            <div class="text-sm text-gray-400">Onaylanan</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-red-400">{{ $rejectedClaims }}</div>
            <div class="text-sm text-gray-400">Reddedilen</div>
        </div>
    </div>
</div>

<!-- Quick Approve Modal -->
<div id="quick-approve-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-secondary rounded-xl p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4 text-green-400">Bonus Talebini Onayla</h3>
        <form id="quick-approve-form" method="POST">
            @csrf
            <div id="approve-details" class="mb-4 text-sm text-gray-300"></div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Verilecek Tutar</label>
                <input type="number" 
                       name="awarded_amount" 
                       id="awarded_amount"
                       step="0.01" 
                       min="0.01"
                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                       required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Admin Mesajı (Opsiyonel)</label>
                <textarea name="admin_message" 
                          rows="3"
                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                          placeholder="Kullanıcıya iletilecek mesaj..."></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    Onayla
                </button>
                <button type="button" onclick="closeModals()" class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quick Reject Modal -->
<div id="quick-reject-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-secondary rounded-xl p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4 text-red-400">Bonus Talebini Reddet</h3>
        <form id="quick-reject-form" method="POST">
            @csrf
            <div id="reject-details" class="mb-4 text-sm text-gray-300"></div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Red Nedeni *</label>
                <textarea name="admin_message" 
                          rows="3"
                          class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold"
                          placeholder="Red nedenini açıklayın..."
                          required></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                    Reddet
                </button>
                <button type="button" onclick="closeModals()" class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function quickApprove(claimId, userName, bonusName, claimedAmount) {
    document.getElementById('quick-approve-form').action = `/admin/bonus-claims/${claimId}/approve`;
    document.getElementById('approve-details').innerHTML = `
        <strong>Kullanıcı:</strong> ${userName}<br>
        <strong>Bonus:</strong> ${bonusName}<br>
        <strong>Talep Edilen:</strong> ₺${claimedAmount}
    `;
    document.getElementById('awarded_amount').value = claimedAmount;
    document.getElementById('quick-approve-modal').classList.remove('hidden');
    document.getElementById('quick-approve-modal').classList.add('flex');
}

function quickReject(claimId, userName, bonusName) {
    document.getElementById('quick-reject-form').action = `/admin/bonus-claims/${claimId}/reject`;
    document.getElementById('reject-details').innerHTML = `
        <strong>Kullanıcı:</strong> ${userName}<br>
        <strong>Bonus:</strong> ${bonusName}
    `;
    document.getElementById('quick-reject-modal').classList.remove('hidden');
    document.getElementById('quick-reject-modal').classList.add('flex');
}

function closeModals() {
    document.getElementById('quick-approve-modal').classList.add('hidden');
    document.getElementById('quick-approve-modal').classList.remove('flex');
    document.getElementById('quick-reject-modal').classList.add('hidden');
    document.getElementById('quick-reject-modal').classList.remove('flex');
}

// Select all functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.claim-checkbox:not(:disabled)');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

function showBulkApproveModal() {
    const selected = document.querySelectorAll('.claim-checkbox:checked');
    if (selected.length === 0) {
        alert('Lütfen en az bir talep seçin.');
        return;
    }
    
    if (confirm(`${selected.length} bonus talebini onaylamak istediğinizden emin misiniz?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/bonus-claims/bulk';
        
        // CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfInput);
        
        // Action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'approve';
        form.appendChild(actionInput);
        
        // Selected IDs
        selected.forEach(checkbox => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'claim_ids[]';
            idInput.value = checkbox.value;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function showBulkRejectModal() {
    const selected = document.querySelectorAll('.claim-checkbox:checked');
    if (selected.length === 0) {
        alert('Lütfen en az bir talep seçin.');
        return;
    }
    
    const message = prompt(`${selected.length} bonus talebini reddetmek istediğinizden emin misiniz?\n\nRed nedenini yazın:`);
    if (message) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/bonus-claims/bulk';
        
        // CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfInput);
        
        // Action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'reject';
        form.appendChild(actionInput);
        
        // Message
        const messageInput = document.createElement('input');
        messageInput.type = 'hidden';
        messageInput.name = 'bulk_message';
        messageInput.value = message;
        form.appendChild(messageInput);
        
        // Selected IDs
        selected.forEach(checkbox => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'claim_ids[]';
            idInput.value = checkbox.value;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection



