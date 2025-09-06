@extends('layouts.app')

@section('title', 'Bonus Taleplerim - BetGround')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-hand-holding-heart mr-3"></i>Bonus Taleplerim
            </h1>
            <p class="text-xl text-gray-300">Bonus talep geçmişinizi görüntüleyin</p>
        </div>
    </section>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-secondary rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-gold">{{ $claims->total() }}</div>
            <div class="text-sm text-gray-400">Toplam Talep</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-yellow-400">{{ $claims->where('status', 'pending')->count() }}</div>
            <div class="text-sm text-gray-400">Bekleyen</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-green-400">{{ $claims->where('status', 'approved')->count() }}</div>
            <div class="text-sm text-gray-400">Onaylanan</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-red-400">{{ $claims->where('status', 'rejected')->count() }}</div>
            <div class="text-sm text-gray-400">Reddedilen</div>
        </div>
    </div>

    <!-- Claims List -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">Bonus Talep Geçmişi</h2>
        
        @forelse($claims as $claim)
            <div class="bg-accent rounded-xl p-6 mb-4 last:mb-0">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Bonus Info -->
                    <div class="flex items-center space-x-4">
                        @if($claim->bonus->image)
                            <img src="{{ $claim->bonus->image_url }}" alt="{{ $claim->bonus->name }}" class="w-16 h-16 rounded-lg object-cover">
                        @else
                            <div class="w-16 h-16 bg-secondary rounded-lg flex items-center justify-center text-2xl">
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
                            <h3 class="font-bold text-lg">{{ $claim->bonus->name }}</h3>
                            <p class="text-sm text-gray-400">{{ $claim->bonus->bonus_type_display }}</p>
                            <p class="text-xs text-gray-500">{{ $claim->created_at->format('d.m.Y H:i') }} - {{ $claim->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Amount and Status -->
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gold mb-1">₺{{ number_format($claim->claimed_amount, 2) }}</div>
                        @if($claim->awarded_amount && $claim->awarded_amount != $claim->claimed_amount)
                            <div class="text-sm text-green-400 mb-2">Verilen: ₺{{ number_format($claim->awarded_amount, 2) }}</div>
                        @endif
                        
                        <div class="mb-2">
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $claim->status_color }}">
                                {{ $claim->status_display }}
                            </span>
                        </div>
                        
                        @if($claim->processed_at)
                            <div class="text-xs text-gray-400">{{ $claim->processed_at->format('d.m.Y H:i') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Messages -->
                @if($claim->user_message || $claim->admin_message)
                    <div class="mt-4 pt-4 border-t border-gray-600">
                        @if($claim->user_message)
                            <div class="bg-blue-900 bg-opacity-30 p-3 rounded-lg mb-3">
                                <h5 class="font-medium text-blue-400 text-sm mb-1">Mesajınız:</h5>
                                <p class="text-sm text-gray-300">{{ $claim->user_message }}</p>
                            </div>
                        @endif
                        
                        @if($claim->admin_message)
                            <div class="bg-{{ $claim->status === 'approved' ? 'green' : 'red' }}-900 bg-opacity-30 p-3 rounded-lg">
                                <h5 class="font-medium text-{{ $claim->status === 'approved' ? 'green' : 'red' }}-400 text-sm mb-1">Admin Cevabı:</h5>
                                <p class="text-sm text-gray-300">{{ $claim->admin_message }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Henüz bonus talebi yok</h3>
                <p class="text-gray-400 mb-6">Promosyonlar sayfasından bonus talep edebilirsiniz</p>
                <a href="{{ route('promotions') }}" class="bg-gold text-black px-6 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-gift mr-2"></i>Promosyonları Gör
                </a>
            </div>
        @endforelse
        
        <!-- Pagination -->
        @if($claims->hasPages())
            <div class="mt-8">
                {{ $claims->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <a href="{{ route('promotions') }}" class="bg-secondary p-6 rounded-xl text-center card-hover">
            <div class="text-gold text-4xl mb-3">
                <i class="fas fa-gift"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Yeni Bonus Talep Et</h3>
            <p class="text-gray-400">Promosyonlar sayfasından yeni bonuslar talep edin</p>
        </a>
        
        <a href="{{ route('deposit') }}" class="bg-secondary p-6 rounded-xl text-center card-hover">
            <div class="text-gold text-4xl mb-3">
                <i class="fas fa-credit-card"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Para Yatır</h3>
            <p class="text-gray-400">Bonus kazanmak için para yatırın</p>
        </a>
    </div>
</div>
@endsection



