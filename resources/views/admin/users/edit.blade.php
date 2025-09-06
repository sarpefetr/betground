@extends('layouts.admin')

@section('title', $user->name . ' - Kullanıcı Düzenle')
@section('page-title', 'Kullanıcı Düzenle')
@section('page-description', $user->name . ' kullanıcısının bilgilerini düzenle')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.show', $user) }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Kullanıcı Detayına Dön
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">Kullanıcı Bilgilerini Düzenle</h2>
        
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gold">Temel Bilgiler</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Ad Soyad</label>
                        <input type="text" 
                               id="name" 
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">E-posta</label>
                        <input type="email" 
                               id="email" 
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium mb-2">Telefon</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium mb-2">Doğum Tarihi</label>
                        <input type="date" 
                               id="birth_date" 
                               name="birth_date"
                               value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('birth_date') border-red-500 @enderror"
                               required>
                        @error('birth_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Yeni Şifre (Opsiyonel)</label>
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('password') border-red-500 @enderror"
                               placeholder="Değiştirmek istemiyorsanız boş bırakın">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gold">Ek Bilgiler</h3>
                    
                    <div>
                        <label for="gender" class="block text-sm font-medium mb-2">Cinsiyet</label>
                        <select id="gender" 
                                name="gender"
                                class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('gender') border-red-500 @enderror">
                            <option value="">Seçiniz</option>
                            <option value="male" @if(old('gender', $user->gender) == 'male') selected @endif>Erkek</option>
                            <option value="female" @if(old('gender', $user->gender) == 'female') selected @endif>Kadın</option>
                            <option value="other" @if(old('gender', $user->gender) == 'other') selected @endif>Diğer</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="country" class="block text-sm font-medium mb-2">Ülke</label>
                            <input type="text" 
                                   id="country" 
                                   name="country"
                                   value="{{ old('country', $user->country) }}"
                                   maxlength="2"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('country') border-red-500 @enderror"
                                   required>
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium mb-2">Para Birimi</label>
                            <select id="currency" 
                                    name="currency"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('currency') border-red-500 @enderror">
                                <option value="TRY" @if(old('currency', $user->currency) == 'TRY') selected @endif>Türk Lirası (₺)</option>
                                <option value="USD" @if(old('currency', $user->currency) == 'USD') selected @endif>US Dollar ($)</option>
                                <option value="EUR" @if(old('currency', $user->currency) == 'EUR') selected @endif>Euro (€)</option>
                            </select>
                            @error('currency')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium mb-2">Hesap Durumu</label>
                            <select id="status" 
                                    name="status"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('status') border-red-500 @enderror">
                                <option value="active" @if(old('status', $user->status) == 'active') selected @endif>Aktif</option>
                                <option value="suspended" @if(old('status', $user->status) == 'suspended') selected @endif>Askıya Alınmış</option>
                                <option value="banned" @if(old('status', $user->status) == 'banned') selected @endif>Yasaklı</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kyc_status" class="block text-sm font-medium mb-2">KYC Durumu</label>
                            <select id="kyc_status" 
                                    name="kyc_status"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('kyc_status') border-red-500 @enderror">
                                <option value="pending" @if(old('kyc_status', $user->kyc_status) == 'pending') selected @endif>Bekleyen</option>
                                <option value="verified" @if(old('kyc_status', $user->kyc_status) == 'verified') selected @endif>Doğrulanmış</option>
                                <option value="rejected" @if(old('kyc_status', $user->kyc_status) == 'rejected') selected @endif>Reddedilmiş</option>
                            </select>
                            @error('kyc_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 mt-8">
                <button type="submit" class="bg-gold text-black px-8 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                </button>
                <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection



