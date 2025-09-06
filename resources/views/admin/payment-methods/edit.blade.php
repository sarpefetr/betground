@extends('layouts.admin')

@section('title', $paymentMethod->name . ' Düzenle - Admin Panel')
@section('page-title', $paymentMethod->isCategory() ? 'Kategori Düzenle' : 'Ödeme Yöntemi Düzenle')
@section('page-description', $paymentMethod->name . ' bilgilerini düzenleyin')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.payment-methods.show', $paymentMethod) }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>{{ $paymentMethod->name }} Detayı
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">
            @if($paymentMethod->isCategory())
                🏦 Kategori Bilgilerini Düzenle
            @else
                💳 Ödeme Yöntemi Bilgilerini Düzenle
            @endif
        </h2>
        
        <form method="POST" action="{{ route('admin.payment-methods.update', $paymentMethod) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="{{ $paymentMethod->type }}">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Temel Bilgiler</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">
                            {{ $paymentMethod->isCategory() ? 'Kategori' : 'Ödeme Yöntemi' }} Adı *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name"
                               value="{{ old('name', $paymentMethod->name) }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('name') border-red-500 @enderror"
                               placeholder="{{ $paymentMethod->isCategory() ? 'Örn: Banka Transferi' : 'Örn: Xpay' }}"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium mb-2">Açıklama</label>
                        <textarea id="description" 
                                  name="description"
                                  rows="3"
                                  class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('description') border-red-500 @enderror"
                                  placeholder="{{ $paymentMethod->isCategory() ? 'Kategori açıklaması...' : 'Ödeme yöntemi açıklaması...' }}">{{ old('description', $paymentMethod->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($paymentMethod->isMethod())
                        <div>
                            <label for="parent_id" class="block text-sm font-medium mb-2">Ana Kategori</label>
                            <select id="parent_id" 
                                    name="parent_id"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('parent_id') border-red-500 @enderror">
                                <option value="">Kategori Seçin (Opsiyonel)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @if(old('parent_id', $paymentMethod->parent_id) == $cat->id) selected @endif>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="method_code" class="block text-sm font-medium mb-2">Yöntem Kodu</label>
                            <select id="method_code" 
                                    name="method_code"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('method_code') border-red-500 @enderror">
                                <option value="">Kod Seçin</option>
                                @foreach($methodCodes as $code => $label)
                                    <option value="{{ $code }}" @if(old('method_code', $paymentMethod->method_code) == $code) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('method_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="image" class="block text-sm font-medium mb-2">
                            {{ $paymentMethod->isCategory() ? 'Kategori' : 'Ödeme Yöntemi' }} Resmi
                        </label>
                        
                        @if($paymentMethod->image)
                            <div class="mb-3">
                                <div class="w-32 h-20 bg-accent rounded-lg overflow-hidden">
                                    <img src="{{ $paymentMethod->image_url }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-cover object-center">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Mevcut resim</p>
                            </div>
                        @endif
                        
                        <input type="file" 
                               id="image" 
                               name="image"
                               accept="image/*"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('image') border-red-500 @enderror">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, SVG, WebP formatları desteklenir. Maksimum 5MB. (Boş bırakırsanız mevcut resim korunur)</p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">{{ $paymentMethod->isCategory() ? 'Kategori' : 'Ödeme' }} Ayarları</h3>
                    
                    @if($paymentMethod->isMethod())
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="min_amount" class="block text-sm font-medium mb-2">Minimum Tutar *</label>
                                <input type="number" 
                                       id="min_amount" 
                                       name="min_amount"
                                       value="{{ old('min_amount', $paymentMethod->min_amount) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('min_amount') border-red-500 @enderror"
                                       required>
                                @error('min_amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_amount" class="block text-sm font-medium mb-2">Maksimum Tutar *</label>
                                <input type="number" 
                                       id="max_amount" 
                                       name="max_amount"
                                       value="{{ old('max_amount', $paymentMethod->max_amount) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('max_amount') border-red-500 @enderror"
                                       required>
                                @error('max_amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="commission_rate" class="block text-sm font-medium mb-2">Komisyon Oranı (%) *</label>
                                <input type="number" 
                                       id="commission_rate" 
                                       name="commission_rate"
                                       value="{{ old('commission_rate', $paymentMethod->commission_rate) }}"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('commission_rate') border-red-500 @enderror"
                                       required>
                                @error('commission_rate')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="processing_time" class="block text-sm font-medium mb-2">İşlem Süresi</label>
                                <input type="text" 
                                       id="processing_time" 
                                       name="processing_time"
                                       value="{{ old('processing_time', $paymentMethod->processing_time) }}"
                                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('processing_time') border-red-500 @enderror"
                                       placeholder="Örn: ANLIK, 0-24 SAAT">
                                @error('processing_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Bank Details -->
                        <div class="bg-accent p-4 rounded-lg">
                            <h4 class="font-bold mb-3 text-blue-400">Banka Bilgileri (Banka transferi için)</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium mb-2">Banka Adı</label>
                                    <input type="text" 
                                           id="bank_name" 
                                           name="bank_name"
                                           value="{{ old('bank_name', $paymentMethod->bank_details['bank_name'] ?? '') }}"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('bank_name') border-red-500 @enderror"
                                           placeholder="Örn: Türkiye İş Bankası">
                                    @error('bank_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="bank_iban" class="block text-sm font-medium mb-2">IBAN</label>
                                    <input type="text" 
                                           id="bank_iban" 
                                           name="bank_iban"
                                           value="{{ old('bank_iban', $paymentMethod->bank_details['bank_iban'] ?? '') }}"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('bank_iban') border-red-500 @enderror"
                                           placeholder="TR12 3456 7890 1234 5678 9012 34">
                                    @error('bank_iban')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="account_holder" class="block text-sm font-medium mb-2">Hesap Sahibi</label>
                                    <input type="text" 
                                           id="account_holder" 
                                           name="account_holder"
                                           value="{{ old('account_holder', $paymentMethod->bank_details['account_holder'] ?? '') }}"
                                           class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('account_holder') border-red-500 @enderror"
                                           placeholder="BetGround Ltd.">
                                    @error('account_holder')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="instructions" class="block text-sm font-medium mb-2">Kullanıcı Talimatları</label>
                            <textarea id="instructions" 
                                      name="instructions"
                                      rows="4"
                                      class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('instructions') border-red-500 @enderror"
                                      placeholder="Kullanıcılara gösterilecek ödeme talimatları...">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
                            @error('instructions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="order_index" class="block text-sm font-medium mb-2">Sıralama İndeksi</label>
                        <input type="number" 
                               id="order_index" 
                               name="order_index"
                               value="{{ old('order_index', $paymentMethod->order_index) }}"
                               min="0"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('order_index') border-red-500 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Düşük sayılar önce gösterilir</p>
                        @error('order_index')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($paymentMethod->isMethod())
                        <div>
                            <label class="block text-sm font-medium mb-2">Desteklenen Para Birimleri</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach(['TRY', 'USD', 'EUR'] as $currency)
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="supported_currencies[]" 
                                               value="{{ $currency }}"
                                               class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                               @if(!old('supported_currencies') && $paymentMethod->supported_currencies && in_array($currency, $paymentMethod->supported_currencies)) checked
                                               @elseif(old('supported_currencies') && in_array($currency, old('supported_currencies', []))) checked @endif>
                                        <span class="ml-2 text-sm">{{ $currency }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Status Settings -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-gold">Durum Ayarları</h4>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_active', $paymentMethod->is_active)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-eye text-green-400 mr-1"></i>Aktif {{ $paymentMethod->isCategory() ? 'Kategori' : 'Ödeme Yöntemi' }}
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_featured', $paymentMethod->is_featured)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-star text-gold mr-1"></i>Öne Çıkan {{ $paymentMethod->isCategory() ? 'Kategori' : 'Ödeme Yöntemi' }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 mt-8">
                <button type="submit" class="bg-gold text-black px-8 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>{{ $paymentMethod->isCategory() ? 'Kategoriyi' : 'Ödeme Yöntemini' }} Güncelle
                </button>
                <a href="{{ route('admin.payment-methods.show', $paymentMethod) }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
