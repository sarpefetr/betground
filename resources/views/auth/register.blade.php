@extends('layouts.app')

@section('title', 'Kayıt Ol - BetGround')

@push('styles')
<style>
    .bg-gradient-gold {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    }
    .register-card {
        backdrop-filter: blur(10px);
        background: rgba(42, 42, 42, 0.95);
    }
    .input-focus:focus {
        border-color: #ffd700;
        box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
    }
    .step-indicator {
        transition: all 0.3s ease;
    }
    .step-active {
        background: #ffd700;
        color: #000;
    }
    .step-completed {
        background: #10b981;
        color: #fff;
    }
</style>
@endpush

@section('content')
<!-- Bonus Banner -->
<section class="bg-gradient-gold text-black py-6">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl font-bold mb-2">🎉 Hoş Geldin Bonusu 🎉</h2>
        <p class="text-lg">İlk yatırımınıza <strong>%200 BONUS</strong> + <strong>100 Freespin</strong></p>
    </div>
</section>

<!-- Main Content -->
<main class="py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Registration Card -->
        <div class="register-card rounded-2xl p-8 border border-accent">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-glow mb-2">Kayıt Ol</h1>
                <p class="text-gray-300">Hemen hesap oluşturun ve bonuslardan faydalanın</p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" x-data="{ 
                step: 1, 
                showPassword: false,
                showConfirmPassword: false
            }">
                @csrf

                <!-- Step Indicator -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center space-x-4">
                        <div :class="step >= 1 ? (step > 1 ? 'step-completed' : 'step-active') : ''" 
                             class="step-indicator w-10 h-10 rounded-full bg-accent flex items-center justify-center font-bold">
                            <span x-show="step === 1">1</span>
                            <i x-show="step > 1" class="fas fa-check"></i>
                        </div>
                        <div class="w-16 h-1 bg-accent"></div>
                        <div :class="step >= 2 ? (step > 2 ? 'step-completed' : 'step-active') : ''" 
                             class="step-indicator w-10 h-10 rounded-full bg-accent flex items-center justify-center font-bold">
                            <span x-show="step <= 2">2</span>
                            <i x-show="step > 2" class="fas fa-check"></i>
                        </div>
                        <div class="w-16 h-1 bg-accent"></div>
                        <div :class="step >= 3 ? 'step-active' : ''" 
                             class="step-indicator w-10 h-10 rounded-full bg-accent flex items-center justify-center font-bold">3</div>
                    </div>
                </div>

                <!-- Step 1: Account Information -->
                <div x-show="step === 1" x-transition>
                    <h3 class="text-xl font-bold text-center mb-6">Hesap Bilgileri</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">E-posta</label>
                            <input type="email" 
                                   id="email" 
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all @error('email') border-red-500 @enderror"
                                   placeholder="ornek@email.com">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2">Ad Soyad</label>
                            <input type="text" 
                                   id="name" 
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all @error('name') border-red-500 @enderror"
                                   placeholder="Adınız Soyadınız">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium mb-2">Şifre</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" 
                                       id="password" 
                                       name="password"
                                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all pr-12 @error('password') border-red-500 @enderror"
                                       placeholder="Şifreniz (min. 8 karakter)">
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gold transition-colors">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium mb-2">Şifre Tekrar</label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all pr-12"
                                       placeholder="Şifrenizi tekrar girin">
                                <button type="button" 
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gold transition-colors">
                                    <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            @click="step = 2"
                            class="w-full bg-gold text-black py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors mt-6">
                        İleri <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>

                <!-- Step 2: Personal Information -->
                <div x-show="step === 2" x-transition>
                    <h3 class="text-xl font-bold text-center mb-6">Kişisel Bilgiler</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium mb-2">Telefon</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all @error('phone') border-red-500 @enderror"
                                   placeholder="05XX XXX XX XX">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium mb-2">Doğum Tarihi</label>
                            <input type="date" 
                                   id="birth_date" 
                                   name="birth_date"
                                   value="{{ old('birth_date') }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium mb-2">Cinsiyet</label>
                            <select id="gender" 
                                    name="gender"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all @error('gender') border-red-500 @enderror">
                                <option value="">Seçiniz</option>
                                <option value="male" @if(old('gender') == 'male') selected @endif>Erkek</option>
                                <option value="female" @if(old('gender') == 'female') selected @endif>Kadın</option>
                                <option value="other" @if(old('gender') == 'other') selected @endif>Diğer</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium mb-2">Para Birimi</label>
                            <select id="currency" 
                                    name="currency"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none input-focus transition-all @error('currency') border-red-500 @enderror">
                                <option value="TRY" @if(old('currency', 'TRY') == 'TRY') selected @endif>Türk Lirası (₺)</option>
                                <option value="USD" @if(old('currency') == 'USD') selected @endif>US Dollar ($)</option>
                                <option value="EUR" @if(old('currency') == 'EUR') selected @endif>Euro (€)</option>
                            </select>
                            @error('currency')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium mb-2">Ülke</label>
                            <input type="text" 
                                   id="country" 
                                   name="country"
                                   value="{{ old('country', 'TR') }}"
                                   maxlength="2"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all @error('country') border-red-500 @enderror"
                                   placeholder="TR">
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Referral Code -->
                    <div class="mt-4">
                        <label for="referral_code" class="block text-sm font-medium mb-2">Referans Kodu (Opsiyonel)</label>
                        <input type="text" 
                               id="referral_code" 
                               name="referral_code"
                               value="{{ old('referral_code') }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all @error('referral_code') border-red-500 @enderror"
                               placeholder="Referans kodunuz">
                        @error('referral_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex space-x-4 mt-6">
                        <button type="button" 
                                @click="step = 1"
                                class="flex-1 bg-accent text-white py-3 rounded-lg font-bold text-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Geri
                        </button>
                        <button type="button" 
                                @click="step = 3"
                                class="flex-1 bg-gold text-black py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors">
                            İleri <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Terms and Confirmation -->
                <div x-show="step === 3" x-transition>
                    <h3 class="text-xl font-bold text-center mb-6">Şartlar ve Onay</h3>
                    
                    <!-- Terms -->
                    <div class="space-y-4">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   name="terms"
                                   value="1"
                                   class="w-5 h-5 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2 mt-1"
                                   @if(old('terms')) checked @endif>
                            <span class="ml-3 text-sm text-gray-300">
                                <a href="#" class="text-gold hover:text-yellow-500">Kullanım Şartları</a> ve 
                                <a href="#" class="text-gold hover:text-yellow-500">Gizlilik Politikası</a>'nı okudum ve kabul ediyorum.
                            </span>
                        </label>
                        @error('terms')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror

                        <label class="flex items-start">
                            <input type="checkbox" 
                                   name="marketing"
                                   class="w-5 h-5 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2 mt-1">
                            <span class="ml-3 text-sm text-gray-300">
                                Promosyonlar ve kampanyalar hakkında e-posta almak istiyorum.
                            </span>
                        </label>
                    </div>

                    <!-- Age Verification -->
                    <div class="bg-accent p-4 rounded-lg mt-6">
                        <div class="flex items-center text-yellow-500 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-medium">Yaş Sınırı</span>
                        </div>
                        <p class="text-sm text-gray-300">
                            18 yaşından büyük olduğunuzu ve sorumlu oyun oynayacağınızı onaylıyorsunuz.
                        </p>
                    </div>

                    <div class="flex space-x-4 mt-6">
                        <button type="button" 
                                @click="step = 2"
                                class="flex-1 bg-accent text-white py-3 rounded-lg font-bold text-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Geri
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-gold text-black py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>Kayıt Ol
                        </button>
                    </div>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-8 pt-6 border-t border-gray-600">
                <p class="text-gray-300">
                    Zaten hesabınız var mı? 
                    <a href="{{ route('login') }}" class="text-gold hover:text-yellow-500 font-medium transition-colors">
                        Giriş yapın
                    </a>
                </p>
            </div>
        </div>
    </div>
</main>

<!-- Benefits Section -->
<section class="bg-secondary py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Neden BetGround'u Seçmelisiniz?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-gold text-4xl mb-4">
                    <i class="fas fa-gift"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">%200 Bonus</h3>
                <p class="text-gray-300">İlk yatırımınıza devasa bonus</p>
            </div>
            <div class="text-center">
                <div class="text-gold text-4xl mb-4">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">5000+ Oyun</h3>
                <p class="text-gray-300">Binlerce slot ve casino oyunu</p>
            </div>
            <div class="text-center">
                <div class="text-gold text-4xl mb-4">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Güvenli</h3>
                <p class="text-gray-300">SSL şifreleme ve lisanslı</p>
            </div>
            <div class="text-center">
                <div class="text-gold text-4xl mb-4">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Hızlı Ödemeler</h3>
                <p class="text-gray-300">Anında para yatırma ve çekme</p>
            </div>
        </div>
    </div>
</section>
@endsection



