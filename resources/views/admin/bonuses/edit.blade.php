@extends('layouts.admin')

@section('title', $bonus->name . ' - Bonusu Düzenle')
@section('page-title', 'Bonusu Düzenle')
@section('page-description', $bonus->name . ' bonusunu düzenle')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bonuses.show', $bonus) }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Bonus Detayına Dön
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">{{ $bonus->name }} - Bonus Bilgilerini Düzenle</h2>
        
        <form method="POST" action="{{ route('admin.bonuses.update', $bonus) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Temel Bilgiler</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Bonus Adı *</label>
                        <input type="text" 
                               id="name" 
                               name="name"
                               value="{{ old('name', $bonus->name) }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium mb-2">Bonus Açıklaması *</label>
                        <textarea id="description" 
                                  name="description"
                                  rows="4"
                                  class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('description') border-red-500 @enderror"
                                  required>{{ old('description', $bonus->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="bonus_type" class="block text-sm font-medium mb-2">Bonus Türü *</label>
                            <select id="bonus_type" 
                                    name="bonus_type"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('bonus_type') border-red-500 @enderror"
                                    required>
                                @foreach($bonusTypes as $type)
                                    <option value="{{ $type }}" @if(old('bonus_type', $bonus->bonus_type) == $type) selected @endif>
                                        @if($type === 'welcome') 🎉 Hoş Geldin Bonusu
                                        @elseif($type === 'daily') 📅 Günlük Bonus
                                        @elseif($type === 'weekly') 🎊 Haftalık Bonus
                                        @elseif($type === 'cashback') 💰 Cashback
                                        @elseif($type === 'referral') 🤝 Referans Bonusu
                                        @elseif($type === 'vip') 👑 VIP Bonus
                                        @elseif($type === 'tournament') 🏆 Turnuva Bonusu
                                        @elseif($type === 'special') 🎁 Özel Bonus
                                        @else {{ ucfirst($type) }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('bonus_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount_type" class="block text-sm font-medium mb-2">Bonus Tipi *</label>
                            <select id="amount_type" 
                                    name="amount_type"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('amount_type') border-red-500 @enderror"
                                    required>
                                <option value="percentage" @if(old('amount_type', $bonus->amount_type) == 'percentage') selected @endif>Yüzde (%)</option>
                                <option value="fixed" @if(old('amount_type', $bonus->amount_type) == 'fixed') selected @endif>Sabit Tutar (₺)</option>
                            </select>
                            @error('amount_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="amount_value" class="block text-sm font-medium mb-2">Bonus Miktarı *</label>
                        <input type="number" 
                               id="amount_value" 
                               name="amount_value"
                               value="{{ old('amount_value', $bonus->amount_value) }}"
                               step="0.01"
                               min="0"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('amount_value') border-red-500 @enderror"
                               required>
                        @error('amount_value')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Image -->
                    @if($bonus->image)
                        <div>
                            <label class="block text-sm font-medium mb-2">Mevcut Bonus Resmi</label>
                            <div class="w-32 h-32 bg-accent rounded-lg overflow-hidden">
                                <img src="{{ $bonus->image_url }}" alt="{{ $bonus->name }}" class="w-full h-full object-cover object-center">
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="image" class="block text-sm font-medium mb-2">Yeni Bonus Resmi</label>
                        <input type="file" 
                               id="image" 
                               name="image"
                               accept="image/*"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('image') border-red-500 @enderror">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, SVG, WebP formatları desteklenir. Maksimum 5MB.</p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bonus Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Bonus Ayarları</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="min_deposit" class="block text-sm font-medium mb-2">Minimum Yatırım *</label>
                            <input type="number" 
                                   id="min_deposit" 
                                   name="min_deposit"
                                   value="{{ old('min_deposit', $bonus->min_deposit) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('min_deposit') border-red-500 @enderror"
                                   required>
                            @error('min_deposit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_bonus" class="block text-sm font-medium mb-2">Maksimum Bonus</label>
                            <input type="number" 
                                   id="max_bonus" 
                                   name="max_bonus"
                                   value="{{ old('max_bonus', $bonus->max_bonus) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('max_bonus') border-red-500 @enderror">
                            @error('max_bonus')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="wagering_requirement" class="block text-sm font-medium mb-2">Çevrim Şartı *</label>
                            <input type="number" 
                                   id="wagering_requirement" 
                                   name="wagering_requirement"
                                   value="{{ old('wagering_requirement', $bonus->wagering_requirement) }}"
                                   min="1"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('wagering_requirement') border-red-500 @enderror"
                                   required>
                            @error('wagering_requirement')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="user_limit" class="block text-sm font-medium mb-2">Kullanıcı Başına Limit *</label>
                            <input type="number" 
                                   id="user_limit" 
                                   name="user_limit"
                                   value="{{ old('user_limit', $bonus->user_limit) }}"
                                   min="1"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('user_limit') border-red-500 @enderror"
                                   required>
                            @error('user_limit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="valid_from" class="block text-sm font-medium mb-2">Başlangıç Tarihi</label>
                            <input type="datetime-local" 
                                   id="valid_from" 
                                   name="valid_from"
                                   value="{{ old('valid_from', $bonus->valid_from?->format('Y-m-d\TH:i')) }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('valid_from') border-red-500 @enderror">
                            @error('valid_from')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="valid_until" class="block text-sm font-medium mb-2">Bitiş Tarihi</label>
                            <input type="datetime-local" 
                                   id="valid_until" 
                                   name="valid_until"
                                   value="{{ old('valid_until', $bonus->valid_until?->format('Y-m-d\TH:i')) }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('valid_until') border-red-500 @enderror">
                            @error('valid_until')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="terms_conditions" class="block text-sm font-medium mb-2">Şartlar ve Koşullar</label>
                        <textarea id="terms_conditions" 
                                  name="terms_conditions"
                                  rows="4"
                                  class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('terms_conditions') border-red-500 @enderror">{{ old('terms_conditions', $bonus->terms_conditions) }}</textarea>
                        @error('terms_conditions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Settings -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-gold">Durum Ayarları</h4>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_active', $bonus->is_active)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-eye text-green-400 mr-1"></i>Aktif Bonus
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_featured', $bonus->is_featured)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-star text-gold mr-1"></i>Öne Çıkan Bonus
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Advanced Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Gelişmiş Ayarlar</h3>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Geçerli Ülkeler</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($countries as $country)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="countries[]" 
                                           value="{{ $country }}"
                                           class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                           @if(old('countries', $bonus->countries ?? []) && in_array($country, old('countries', $bonus->countries ?? []))) checked @endif>
                                    <span class="ml-2 text-sm">{{ $country }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Hiçbiri seçilmezse tüm ülkeler için geçerli</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Geçerli Para Birimleri</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($currencies as $currency)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="currencies[]" 
                                           value="{{ $currency }}"
                                           class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                           @if(old('currencies', $bonus->currencies ?? []) && in_array($currency, old('currencies', $bonus->currencies ?? []))) checked @endif>
                                    <span class="ml-2 text-sm">{{ $currency }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Hiçbiri seçilmezse tüm para birimleri için geçerli</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 mt-8">
                <button type="submit" class="bg-gold text-black px-8 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                </button>
                <a href="{{ route('admin.bonuses.show', $bonus) }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection



