@extends('layouts.admin')

@section('title', 'Ödeme Yöntemleri - Admin Panel')
@section('page-title', 'Ödeme Yöntemleri')
@section('page-description', 'Ödeme kategorileri ve yöntemlerini yönetin')

@section('content')
<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="bg-secondary rounded-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search and Filters -->
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Ödeme yöntemi adı ile ara..."
                           class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                </div>
                
                <select name="type" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tümü</option>
                    <option value="category" {{ request('type') === 'category' ? 'selected' : '' }}>Kategoriler</option>
                    <option value="method" {{ request('type') === 'method' ? 'selected' : '' }}>Ödeme Yöntemleri</option>
                </select>
                
                <select name="status" class="bg-accent border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-gold">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Pasif</option>
                </select>
                
                <button type="submit" class="bg-gold text-black px-6 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>Ara
                </button>
            </form>

            <!-- Add Buttons -->
            <div class="flex gap-2">
                <a href="{{ route('admin.payment-methods.create', ['type' => 'category']) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Yeni Kategori
                </a>
                <a href="{{ route('admin.payment-methods.create', ['type' => 'method']) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Yeni Ödeme Yöntemi
                </a>
            </div>
        </div>
    </div>

    <!-- Payment Methods Tree View -->
    <div class="space-y-6">
        @foreach($categories as $category)
            <!-- Category Card -->
            <div class="bg-secondary rounded-xl overflow-hidden">
                <div class="relative h-32 bg-gradient-to-br from-indigo-600 to-purple-600">
                    @if($category->image)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover object-center opacity-60">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"></div>
                    @endif
                    
                    <div class="absolute inset-0 flex items-center justify-between p-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $category->name }}</h2>
                            <p class="text-gray-200 text-sm">{{ $category->activeChildren()->count() }} aktif yöntem</p>
                        </div>
                        
                        <div class="flex gap-2">
                            @if(!$category->is_active)
                                <span class="bg-red-600 text-white px-3 py-1 rounded text-sm">PASİF</span>
                            @endif
                            
                            <a href="{{ route('admin.payment-methods.edit', $category) }}" class="bg-black bg-opacity-50 text-white px-3 py-1 rounded hover:bg-opacity-75">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <a href="{{ route('admin.payment-methods.create', ['type' => 'method', 'parent_id' => $category->id]) }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Category Methods -->
                @if($category->activeChildren()->count() > 0)
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($category->activeChildren as $method)
                                <div class="bg-accent rounded-lg overflow-hidden card-hover relative">
                                    <!-- Method Image -->
                                    <div class="relative h-24 bg-gradient-to-br from-gray-600 to-gray-700">
                                        @if($method->image)
                                            <img src="{{ $method->image_url }}" alt="{{ $method->name }}" class="w-full h-full object-cover object-center">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl">
                                                @if($method->method_code === 'bank_transfer') 🏦
                                                @elseif($method->method_code === 'credit_card') 💳
                                                @elseif($method->method_code === 'crypto') ₿
                                                @elseif($method->method_code === 'ewallet') 💰
                                                @elseif($method->method_code === 'mobile') 📱
                                                @elseif($method->method_code === 'atm') 🏧
                                                @else 💳 @endif
                                            </div>
                                        @endif

                                        <!-- Status Badges -->
                                        <div class="absolute top-1 right-1 flex gap-1">
                                            @if($method->is_featured)
                                                <span class="bg-gold text-black px-1 py-0.5 rounded text-xs font-bold">HOT</span>
                                            @endif
                                            @if($method->processing_time)
                                                <span class="bg-green-600 text-white px-1 py-0.5 rounded text-xs">{{ $method->processing_time }}</span>
                                            @endif
                                        </div>

                                        <!-- Quick Actions -->
                                        <div class="absolute bottom-1 right-1 flex gap-1">
                                            <form method="POST" action="{{ route('admin.payment-methods.toggle-status', $method) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-black bg-opacity-50 text-white p-1 rounded hover:bg-opacity-75" title="Durum Değiştir">
                                                    <i class="fas {{ $method->is_active ? 'fa-eye' : 'fa-eye-slash' }} text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Method Info -->
                                    <div class="p-3">
                                        <h4 class="font-bold text-sm mb-1">{{ $method->name }}</h4>
                                        <div class="text-xs text-gray-400 space-y-1">
                                            <div>{{ $method->amount_range }}</div>
                                            <div>{{ $method->commission_display }}</div>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="flex gap-1 mt-2">
                                            <a href="{{ route('admin.payment-methods.show', $method) }}" class="flex-1 bg-blue-600 text-white py-1 px-2 rounded text-center text-xs hover:bg-blue-700">
                                                Detay
                                            </a>
                                            <a href="{{ route('admin.payment-methods.edit', $method) }}" class="flex-1 bg-gold text-black py-1 px-2 rounded text-center text-xs hover:bg-yellow-500">
                                                Düzenle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-400">
                        <p>Bu kategoride henüz ödeme yöntemi yok</p>
                        <a href="{{ route('admin.payment-methods.create', ['type' => 'method', 'parent_id' => $category->id]) }}" class="text-gold hover:text-yellow-500 text-sm">
                            <i class="fas fa-plus mr-1"></i>İlk yöntemi ekle
                        </a>
                    </div>
                @endif
            </div>
        @endforeach

        @if($categories->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Henüz ödeme kategorisi eklenmemiş</h3>
                <p class="text-gray-400 mb-6">İlk kategoriyi ekleyerek başlayın</p>
                <a href="{{ route('admin.payment-methods.create', ['type' => 'category']) }}" class="bg-gold text-black px-6 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>İlk Kategoriyi Ekle
                </a>
            </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $totalCategories = \App\Models\PaymentMethod::where('type', 'category')->count();
            $totalMethods = \App\Models\PaymentMethod::where('type', 'method')->count();
            $activeMethods = \App\Models\PaymentMethod::where('type', 'method')->where('is_active', true)->count();
            $featuredMethods = \App\Models\PaymentMethod::where('is_featured', true)->count();
        @endphp
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gold">{{ $totalCategories }}</div>
            <div class="text-sm text-gray-400">Kategori</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-blue-400">{{ $totalMethods }}</div>
            <div class="text-sm text-gray-400">Ödeme Yöntemi</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-green-400">{{ $activeMethods }}</div>
            <div class="text-sm text-gray-400">Aktif</div>
        </div>
        
        <div class="bg-secondary rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gold">{{ $featuredMethods }}</div>
            <div class="text-sm text-gray-400">Öne Çıkan</div>
        </div>
    </div>
</div>
@endsection
