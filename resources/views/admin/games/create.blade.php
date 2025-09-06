@extends('layouts.admin')

@section('title', 'Yeni Oyun Ekle - Admin Panel')
@section('page-title', 'Yeni Oyun Ekle')
@section('page-description', 'Casino, slot veya diğer oyunları sisteme ekleyin')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.games.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Oyun Listesi
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">Yeni Oyun Bilgileri</h2>
        
        <form method="POST" action="{{ route('admin.games.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Temel Bilgiler</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Oyun Adı *</label>
                        <input type="text" 
                               id="name" 
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('name') border-red-500 @enderror"
                               placeholder="Örn: Sweet Bonanza"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium mb-2">Kategori *</label>
                        <select id="category" 
                                name="category"
                                class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('category') border-red-500 @enderror"
                                required>
                            <option value="">Kategori Seçin</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @if(old('category') == $category) selected @endif>
                                    @if($category === 'slots') 🎰 Slot Oyunları
                                    @elseif($category === 'casino') 🃏 Canlı Casino
                                    @elseif($category === 'sports') ⚽ Spor Bahisleri
                                    @elseif($category === 'esports') 🎮 E-Sporlar
                                    @elseif($category === 'virtual') 🤖 Sanal Sporlar
                                    @else {{ ucfirst($category) }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium mb-2">Oyun Türü</label>
                            <input type="text" 
                                   id="type" 
                                   name="type"
                                   value="{{ old('type') }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('type') border-red-500 @enderror"
                                   placeholder="Örn: Video Slot">
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="provider" class="block text-sm font-medium mb-2">Sağlayıcı</label>
                            <input type="text" 
                                   id="provider" 
                                   name="provider"
                                   value="{{ old('provider') }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('provider') border-red-500 @enderror"
                                   placeholder="Örn: Pragmatic Play">
                            @error('provider')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="thumbnail" class="block text-sm font-medium mb-2">Oyun Resmi</label>
                        <input type="file" 
                               id="thumbnail" 
                               name="thumbnail"
                               accept="image/*"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('thumbnail') border-red-500 @enderror">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, SVG, WebP formatları desteklenir. Maksimum 5MB.</p>
                        @error('thumbnail')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Game Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Oyun Ayarları</h3>
                    
                    <div>
                        <label for="rtp" class="block text-sm font-medium mb-2">RTP (Return to Player) %</label>
                        <input type="number" 
                               id="rtp" 
                               name="rtp"
                               value="{{ old('rtp') }}"
                               step="0.01"
                               min="0"
                               max="100"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('rtp') border-red-500 @enderror"
                               placeholder="96.50">
                        @error('rtp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="min_bet" class="block text-sm font-medium mb-2">Minimum Bahis *</label>
                            <input type="number" 
                                   id="min_bet" 
                                   name="min_bet"
                                   value="{{ old('min_bet', '1.00') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('min_bet') border-red-500 @enderror"
                                   placeholder="1.00"
                                   required>
                            @error('min_bet')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_bet" class="block text-sm font-medium mb-2">Maksimum Bahis *</label>
                            <input type="number" 
                                   id="max_bet" 
                                   name="max_bet"
                                   value="{{ old('max_bet', '10000.00') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('max_bet') border-red-500 @enderror"
                                   placeholder="10000.00"
                                   required>
                            @error('max_bet')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="order_index" class="block text-sm font-medium mb-2">Sıralama İndeksi</label>
                        <input type="number" 
                               id="order_index" 
                               name="order_index"
                               value="{{ old('order_index', '0') }}"
                               min="0"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('order_index') border-red-500 @enderror"
                               placeholder="0">
                        <p class="text-xs text-gray-400 mt-1">Düşük sayılar önce gösterilir</p>
                        @error('order_index')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_live"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_live')) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-circle text-red-400 mr-1"></i>Canlı Oyun
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_featured')) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-star text-gold mr-1"></i>Öne Çıkan Oyun
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_active', true)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-eye text-green-400 mr-1"></i>Aktif Oyun
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 mt-8">
                <button type="submit" class="bg-gold text-black px-8 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>Oyunu Kaydet
                </button>
                <a href="{{ route('admin.games.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
