@extends('layouts.admin')

@section('title', $paymentMethod->name . ' - Ödeme Yöntemi Detayı')
@section('page-title', $paymentMethod->isCategory() ? 'Kategori Detayı' : 'Ödeme Yöntemi Detayı')
@section('page-description', $paymentMethod->name . ' detay bilgileri')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.payment-methods.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Ödeme Yöntemleri Listesi
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" class="bg-gold text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                <i class="fas fa-edit mr-2"></i>Düzenle
            </a>
            @if($paymentMethod->isCategory())
                <a href="{{ route('admin.payment-methods.create', ['type' => 'method', 'parent_id' => $paymentMethod->id]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Yöntem Ekle
                </a>
            @endif
        </div>
    </div>

    <!-- Method Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Method Image and Basic Info -->
        <div class="lg:col-span-2 bg-secondary rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Method Image -->
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ $paymentMethod->isCategory() ? 'Kategori' : 'Ödeme Yöntemi' }} Görseli</h3>
                    <div class="w-full h-48 bg-accent rounded-lg overflow-hidden">
                        @if($paymentMethod->image)
                            <img src="{{ $paymentMethod->image_url }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-cover object-center">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl">
                                @if($paymentMethod->method_code === 'bank_transfer') 🏦
                                @elseif($paymentMethod->method_code === 'credit_card') 💳
                                @elseif($paymentMethod->method_code === 'crypto') ₿
                                @elseif($paymentMethod->method_code === 'ewallet') 💰
                                @elseif($paymentMethod->method_code === 'mobile') 📱
                                @elseif($paymentMethod->method_code === 'atm') 🏧
                                @else 💳 @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Method Details -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Detay Bilgileri</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Ad:</span>
                            <span class="font-medium">{{ $paymentMethod->name }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Slug:</span>
                            <span class="font-mono text-sm bg-accent px-2 py-1 rounded">{{ $paymentMethod->slug }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tür:</span>
                            <span class="px-2 py-1 bg-{{ $paymentMethod->isCategory() ? 'blue' : 'green' }}-600 text-white rounded text-sm">
                                {{ $paymentMethod->isCategory() ? '📁 Kategori' : '💳 Ödeme Yöntemi' }}
                            </span>
                        </div>
                        
                        @if($paymentMethod->parent)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Ana Kategori:</span>
                                <a href="{{ route('admin.payment-methods.show', $paymentMethod->parent) }}" class="text-gold hover:text-yellow-500">
                                    {{ $paymentMethod->parent->name }}
                                </a>
                            </div>
                        @endif
                        
                        @if($paymentMethod->method_code)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Yöntem Kodu:</span>
                                <span class="font-medium">{{ $paymentMethod->method_code }}</span>
                            </div>
                        @endif
                        
                        @if($paymentMethod->isMethod())
                            <div class="flex justify-between">
                                <span class="text-gray-400">Tutar Limiti:</span>
                                <span class="font-medium">{{ $paymentMethod->amount_range }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-400">Komisyon:</span>
                                <span class="font-medium">{{ $paymentMethod->commission_display }}</span>
                            </div>
                            
                            @if($paymentMethod->processing_time)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">İşlem Süresi:</span>
                                    <span class="text-green-400 font-bold">{{ $paymentMethod->processing_time }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($paymentMethod->description)
                <div class="mt-6 pt-6 border-t border-accent">
                    <h4 class="font-bold text-lg mb-3">Açıklama</h4>
                    <p class="text-gray-300 leading-relaxed">{{ $paymentMethod->description }}</p>
                </div>
            @endif

            <!-- Bank Details -->
            @if($paymentMethod->isMethod() && $paymentMethod->bank_details)
                <div class="mt-6 pt-6 border-t border-accent">
                    <h4 class="font-bold text-lg mb-3">Banka Bilgileri</h4>
                    <div class="bg-accent p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            @if(isset($paymentMethod->bank_details['bank_name']))
                                <div>
                                    <span class="text-gray-400">Banka:</span>
                                    <div class="text-gold font-bold">{{ $paymentMethod->bank_details['bank_name'] }}</div>
                                </div>
                            @endif
                            @if(isset($paymentMethod->bank_details['bank_iban']))
                                <div>
                                    <span class="text-gray-400">IBAN:</span>
                                    <div class="text-gold font-mono text-sm">{{ $paymentMethod->bank_details['bank_iban'] }}</div>
                                </div>
                            @endif
                            @if(isset($paymentMethod->bank_details['account_holder']))
                                <div>
                                    <span class="text-gray-400">Hesap Sahibi:</span>
                                    <div class="text-gold font-bold">{{ $paymentMethod->bank_details['account_holder'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Instructions -->
            @if($paymentMethod->instructions)
                <div class="mt-6 pt-6 border-t border-accent">
                    <h4 class="font-bold text-lg mb-3">Kullanıcı Talimatları</h4>
                    <div class="bg-blue-900 bg-opacity-30 p-4 rounded-lg">
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $paymentMethod->instructions }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Status and Actions -->
        <div class="space-y-6">
            <!-- Status Info -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Durum Bilgileri</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Aktif:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $paymentMethod->is_active ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                            {{ $paymentMethod->is_active ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Öne Çıkan:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $paymentMethod->is_featured ? 'bg-gold bg-opacity-20 text-gold' : 'bg-gray-500 bg-opacity-20 text-gray-400' }}">
                            {{ $paymentMethod->is_featured ? 'Evet' : 'Hayır' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Sıralama:</span>
                        <span class="font-medium">{{ $paymentMethod->order_index }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Oluşturulma:</span>
                        <span class="font-medium text-sm">{{ $paymentMethod->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Son Güncelleme:</span>
                        <span class="font-medium text-sm">{{ $paymentMethod->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-secondary rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">Hızlı İşlemler</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.payment-methods.toggle-featured', $paymentMethod) }}">
                        @csrf
                        <button type="submit" class="w-full bg-gold text-black py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                            <i class="fas fa-star mr-2"></i>
                            {{ $paymentMethod->is_featured ? 'Öne Çıkandan Kaldır' : 'Öne Çıkar' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.payment-methods.toggle-status', $paymentMethod) }}">
                        @csrf
                        <button type="submit" class="w-full {{ $paymentMethod->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 rounded-lg transition-colors">
                            <i class="fas {{ $paymentMethod->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                            {{ $paymentMethod->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                        </button>
                    </form>
                    
                    @if($paymentMethod->isCategory())
                        <a href="{{ route('admin.payment-methods.create', ['type' => 'method', 'parent_id' => $paymentMethod->id]) }}" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors block text-center">
                            <i class="fas fa-plus mr-2"></i>Yeni Yöntem Ekle
                        </a>
                    @endif
                </div>
            </div>

            @if($paymentMethod->isMethod() && $paymentMethod->supported_currencies)
                <!-- Supported Currencies -->
                <div class="bg-secondary rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4">Desteklenen Para Birimleri</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($paymentMethod->supported_currencies as $currency)
                            <span class="px-3 py-1 bg-green-600 text-white rounded text-sm">{{ $currency }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Child Methods (if category) -->
    @if($paymentMethod->isCategory() && $paymentMethod->children->isNotEmpty())
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">{{ $paymentMethod->name }} - Alt Ödeme Yöntemleri</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($paymentMethod->children as $method)
                    <div class="bg-accent rounded-lg overflow-hidden card-hover">
                        <!-- Method Image -->
                        <div class="relative h-32 bg-gradient-to-br from-gray-600 to-gray-700">
                            @if($method->image)
                                <img src="{{ $method->image_url }}" alt="{{ $method->name }}" class="w-full h-full object-cover object-center">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl">
                                    @if($method->method_code === 'bank_transfer') 🏦
                                    @elseif($method->method_code === 'credit_card') 💳
                                    @elseif($method->method_code === 'crypto') ₿
                                    @elseif($method->method_code === 'ewallet') 💰
                                    @elseif($method->method_code === 'mobile') 📱
                                    @elseif($method->method_code === 'atm') 🏧
                                    @else 💳 @endif
                                </div>
                            @endif

                            <!-- Status -->
                            @if(!$method->is_active)
                                <div class="absolute top-2 left-2">
                                    <span class="bg-red-600 text-white px-2 py-1 rounded text-xs">PASİF</span>
                                </div>
                            @endif

                            @if($method->is_featured)
                                <div class="absolute top-2 right-2">
                                    <span class="bg-gold text-black px-2 py-1 rounded text-xs font-bold">HOT</span>
                                </div>
                            @endif
                        </div>

                        <!-- Method Info -->
                        <div class="p-4">
                            <h4 class="font-bold text-lg mb-2">{{ $method->name }}</h4>
                            <div class="space-y-1 text-sm text-gray-400">
                                <div>{{ $method->amount_range }}</div>
                                <div>{{ $method->commission_display }}</div>
                                @if($method->processing_time)
                                    <div class="text-green-400">{{ $method->processing_time }}</div>
                                @endif
                            </div>
                            
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('admin.payment-methods.show', $method) }}" class="flex-1 bg-blue-600 text-white py-1 px-2 rounded text-center text-sm hover:bg-blue-700">
                                    Detay
                                </a>
                                <a href="{{ route('admin.payment-methods.edit', $method) }}" class="flex-1 bg-gold text-black py-1 px-2 rounded text-center text-sm hover:bg-yellow-500">
                                    Düzenle
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Usage Statistics -->
    @if($paymentMethod->isMethod())
        <div class="bg-secondary rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6">Kullanım İstatistikleri</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gold">{{ $paymentMethod->deposits->count() }}</div>
                    <div class="text-sm text-gray-400">Toplam İşlem</div>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-400">₺{{ number_format($paymentMethod->deposits->where('status', 'completed')->sum('amount'), 2) }}</div>
                    <div class="text-sm text-gray-400">Toplam Tutar</div>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400">{{ $paymentMethod->deposits->where('status', 'completed')->count() }}</div>
                    <div class="text-sm text-gray-400">Başarılı İşlem</div>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-400">{{ $paymentMethod->deposits->where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-gray-400">Bekleyen</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Danger Zone -->
    @if(auth()->user()->isSuperAdmin())
        <div class="bg-red-900 bg-opacity-20 border border-red-600 rounded-xl p-6">
            <h2 class="text-xl font-bold mb-4 text-red-400">
                <i class="fas fa-exclamation-triangle mr-2"></i>Tehlikeli İşlemler
            </h2>
            <p class="text-gray-300 mb-4">Bu işlemler geri alınamaz. Lütfen dikkatli olun.</p>
            
            <form method="POST" action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" 
                  onsubmit="return confirm('Bu {{ $paymentMethod->isCategory() ? 'kategoriyi' : 'ödeme yöntemini' }} silmek istediğinizden emin misiniz?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>{{ $paymentMethod->isCategory() ? 'Kategoriyi' : 'Ödeme Yöntemini' }} Sil
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
