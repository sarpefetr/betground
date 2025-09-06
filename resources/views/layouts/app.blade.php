<!DOCTYPE html>
<html lang="tr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Supernovabet - En İyi Bahis Deneyimi')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1a1a1a',
                        secondary: '#2a2a2a',
                        accent: '#3a3a3a',
                        gold: '#ffd700',
                        darkgray: '#171717'
                    }
                }
            }
        }
    </script>
    
    <style>
        .bg-gradient-dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
        }
        .text-glow {
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-primary text-white min-h-screen">
    <!-- News Bar -->
    <div class="bg-gold text-black text-center py-2 text-sm font-medium">
        <div class="container mx-auto">
            <span class="animate-pulse">📢 Adresimiz 777supernovabet.com'dur sonraki adresimiz 778supernovabet.com olacaktır</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-secondary shadow-lg border-b border-accent">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-gold text-2xl font-bold text-glow">
                        <i class="fas fa-dice mr-2"></i>Supernovabet
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('home')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-home mr-2"></i>Ana Sayfa
                    </a>
                    <a href="{{ route('live-casino') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('live-casino')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-video mr-2"></i>Canlı Casino
                    </a>
                    <a href="{{ route('sports-betting') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('sports-betting')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-futbol mr-2"></i>Canlı Bahis
                    </a>
                    <a href="{{ route('slots') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('slots')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-gamepad mr-2"></i>Slot
                    </a>
                    <a href="{{ route('games') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('games')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-puzzle-piece mr-2"></i>Oyunlar
                    </a>
                    <a href="{{ route('esports') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('esports')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-desktop mr-2"></i>E-Sporlar
                    </a>
                    <a href="{{ route('virtual-sports') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('virtual-sports')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-robot mr-2"></i>Sanal Sporlar
                    </a>
                    <a href="{{ route('promotions') }}" class="flex items-center text-white hover:text-gold transition-colors @if(request()->routeIs('promotions')) text-gold border-b-2 border-gold pb-1 @endif">
                        <i class="fas fa-gift mr-2"></i>Promosyonlar
                    </a>
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="text-right">
                            @php
                                $userWallet = \App\Models\Wallet::where('user_id', auth()->id())->first();
                            @endphp
                            @if($userWallet)
                                <div class="text-gold font-bold text-lg">₺{{ number_format($userWallet->balance, 2) }}</div>
                                @if($userWallet->bonus_balance > 0)
                                    <div class="text-blue-400 font-medium text-sm">₺{{ number_format($userWallet->bonus_balance, 2) }} bonus</div>
                                @endif
                                <!-- Debug -->
                                <div class="text-xs text-gray-500">ID:{{ $userWallet->user_id }}/B:{{ $userWallet->balance }}</div>
                            @else
                                <div class="text-gold font-bold">₺0,00</div>
                                <div class="text-xs text-red-400">Cüzdan yok!</div>
                            @endif
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="bg-accent rounded-full p-2 hover:bg-gray-700 transition-colors">
                                <i class="fas fa-user text-gold"></i>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-90"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-90"
                                 class="absolute right-0 mt-2 w-48 bg-secondary rounded-lg shadow-xl border border-accent">
                                <div class="p-3 border-b border-accent">
                                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('deposit') }}" class="block px-4 py-2 text-sm hover:bg-accent">
                                    <i class="fas fa-credit-card mr-2"></i>Para Yatır
                                </a>
                                <a href="{{ route('withdraw') }}" class="block px-4 py-2 text-sm hover:bg-accent">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Para Çek
                                </a>
                                <a href="{{ route('bonuses.my-bonuses') }}" class="block px-4 py-2 text-sm hover:bg-accent">
                                    <i class="fas fa-hand-holding-heart mr-2"></i>Bonus Taleplerim
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-accent text-gold">
                                        <i class="fas fa-shield-alt mr-2"></i>Admin Panel
                                    </a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-accent text-red-400">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-gold text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Giriş
                        </a>
                        <a href="{{ route('register') }}" class="border border-gold text-gold px-4 py-2 rounded-lg hover:bg-gold hover:text-black transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>Kayıt
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button x-data @click="$dispatch('toggle-mobile-menu')" class="text-white hover:text-gold">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-data="{ open: false }" 
             x-on:toggle-mobile-menu.window="open = !open"
             x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-1"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-1"
             class="md:hidden bg-accent">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-home mr-2"></i>Ana Sayfa
                </a>
                <a href="{{ route('live-casino') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-video mr-2"></i>Canlı Casino
                </a>
                <a href="{{ route('sports-betting') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-futbol mr-2"></i>Canlı Bahis
                </a>
                <a href="{{ route('slots') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-gamepad mr-2"></i>Slot
                </a>
                <a href="{{ route('games') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-puzzle-piece mr-2"></i>Oyunlar
                </a>
                <a href="{{ route('esports') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-desktop mr-2"></i>E-Sporlar
                </a>
                <a href="{{ route('virtual-sports') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-robot mr-2"></i>Sanal Sporlar
                </a>
                <a href="{{ route('promotions') }}" class="block px-3 py-2 text-white hover:text-gold">
                    <i class="fas fa-gift mr-2"></i>Promosyonlar
                </a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-600 text-white py-3 px-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 text-white py-3 px-4 text-center">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-darkgray border-t border-accent mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-gold font-bold text-lg mb-4">Supernovabet</h3>
                    <p class="text-gray-400 text-sm">Güvenilir ve eğlenceli bahis deneyimi</p>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Oyunlar</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('live-casino') }}" class="hover:text-gold">Canlı Casino</a></li>
                        <li><a href="{{ route('slots') }}" class="hover:text-gold">Slot Oyunları</a></li>
                        <li><a href="{{ route('sports-betting') }}" class="hover:text-gold">Spor Bahisleri</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Hesap</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('deposit') }}" class="hover:text-gold">Para Yatır</a></li>
                        <li><a href="{{ route('withdraw') }}" class="hover:text-gold">Para Çek</a></li>
                        <li><a href="{{ route('promotions') }}" class="hover:text-gold">Promosyonlar</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Destek</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-gold">Canlı Destek</a></li>
                        <li><a href="#" class="hover:text-gold">SSS</a></li>
                        <li><a href="#" class="hover:text-gold">İletişim</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-accent mt-8 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; 2024 Supernovabet. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
