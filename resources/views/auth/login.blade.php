@extends('layouts.app')

@section('title', 'Giriş Yap - BetGround')

@push('styles')
<style>
    .login-card {
        backdrop-filter: blur(10px);
        background: rgba(42, 42, 42, 0.95);
    }
    .input-focus:focus {
        border-color: #ffd700;
        box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
    }
</style>
@endpush

@section('content')
<main class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <!-- Login Card -->
        <div class="login-card rounded-2xl p-8 border border-accent">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-glow mb-2">Giriş Yap</h1>
                <p class="text-gray-300">Hesabınıza giriş yapın ve oynamaya başlayın</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                @csrf
                
                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2">E-posta veya Kullanıcı Adı</label>
                    <div class="relative">
                        <input type="email" 
                               id="email" 
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all @error('email') border-red-500 @enderror"
                               placeholder="E-posta adresinizi girin"
                               required>
                        <i class="fas fa-user absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium mb-2">Şifre</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none input-focus transition-all pr-12 @error('password') border-red-500 @enderror"
                               placeholder="Şifrenizi girin"
                               required>
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

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember"
                               class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2">
                        <span class="ml-2 text-sm text-gray-300">Beni hatırla</span>
                    </label>
                    <a href="#" class="text-sm text-gold hover:text-yellow-500 transition-colors">Şifremi unuttum</a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gold text-black py-3 rounded-lg font-bold text-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Giriş Yap
                </button>
            </form>

            <!-- Social Login -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-secondary text-gray-400">veya</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button class="flex justify-center items-center px-4 py-2 border border-gray-600 rounded-lg bg-accent text-white hover:bg-gray-700 transition-colors">
                        <i class="fab fa-google text-red-500 mr-2"></i>
                        Google
                    </button>
                    <button class="flex justify-center items-center px-4 py-2 border border-gray-600 rounded-lg bg-accent text-white hover:bg-gray-700 transition-colors">
                        <i class="fab fa-facebook text-blue-500 mr-2"></i>
                        Facebook
                    </button>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center mt-8">
                <p class="text-gray-300">
                    Hesabınız yok mu? 
                    <a href="{{ route('register') }}" class="text-gold hover:text-yellow-500 font-medium transition-colors">
                        Hemen kayıt olun
                    </a>
                </p>
            </div>
        </div>

        <!-- Security Info -->
        <div class="mt-8 text-center text-sm text-gray-400">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    SSL Korumalı
                </div>
                <div class="flex items-center">
                    <i class="fas fa-lock text-gold mr-2"></i>
                    256-bit Şifreleme
                </div>
            </div>
            <p class="mt-2">Bilgileriniz güvenli şekilde şifrelenir ve saklanır.</p>
        </div>
    </div>
</main>

<!-- Benefits Section -->
<section class="bg-secondary py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Neden BetGround?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-gold text-4xl mb-4">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Hızlı İşlemler</h3>
                <p class="text-gray-300">Anında para yatırma ve hızlı çekim işlemleri</p>
            </div>
            <div class="text-center">
                <div class="text-gold text-4xl mb-4">
                    <i class="fas fa-gift"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Büyük Bonuslar</h3>
                <p class="text-gray-300">%200'e varan hoş geldin bonusları</p>
            </div>
            <div class="text-center">
                <div class="text-gold text-4xl mb-4">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">7/24 Destek</h3>
                <p class="text-gray-300">Her zaman yanınızdayız</p>
            </div>
        </div>
    </div>
</section>
@endsection



