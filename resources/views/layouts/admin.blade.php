<!DOCTYPE html>
<html lang="tr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Supernovabet')</title>
    
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
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background: rgba(255, 215, 0, 0.1);
            border-color: #ffd700;
        }
        .sidebar-link.active {
            background: rgba(255, 215, 0, 0.2);
            border-color: #ffd700;
            color: #ffd700;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-primary text-white">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-secondary shadow-lg">
            <div class="p-6 border-b border-accent">
                <a href="{{ route('home') }}" class="text-gold text-2xl font-bold">
                    <i class="fas fa-dice mr-2"></i>Supernovabet
                </a>
                <p class="text-sm text-gray-400 mt-1">Admin Paneli</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    Kullanıcı Yönetimi
                </a>
                
                <a href="{{ route('admin.deposits.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.deposits.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave w-5 mr-3"></i>
                    Yatırım Onayları
                    @php
                        $pendingDeposits = \App\Models\Deposit::where('status', 'pending')->count();
                    @endphp
                    @if($pendingDeposits > 0)
                        <span class="ml-auto bg-green-600 text-white rounded-full px-2 py-1 text-xs">{{ $pendingDeposits }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.games.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                    <i class="fas fa-gamepad w-5 mr-3"></i>
                    Oyun Yönetimi
                </a>
                
                <a href="{{ route('admin.esports.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.esports.*') ? 'active' : '' }}">
                    <i class="fas fa-desktop w-5 mr-3"></i>
                    E-Spor Yönetimi
                </a>
                
                <a href="{{ route('admin.bonuses.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.bonuses.*') ? 'active' : '' }}">
                    <i class="fas fa-gift w-5 mr-3"></i>
                    Bonus Yönetimi
                </a>
                
                <a href="{{ route('admin.bonus-claims.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.bonus-claims.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-heart w-5 mr-3"></i>
                    Bonus Talepleri
                    @php
                        $pendingCount = \App\Models\UserBonusClaim::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-600 text-white rounded-full px-2 py-1 text-xs">{{ $pendingCount }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.payment-methods.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card w-5 mr-3"></i>
                    Ödeme Yöntemleri
                </a>
                
                <a href="{{ route('admin.manual-matches.index') }}" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent {{ request()->routeIs('admin.manual-matches.*') ? 'active' : '' }}">
                    <i class="fas fa-futbol w-5 mr-3"></i>
                    Canlı Maçlar
                    @php
                        $liveMatchCount = \App\Models\ManualMatch::where('is_live', true)->count();
                    @endphp
                    @if($liveMatchCount > 0)
                        <span class="ml-auto bg-red-600 text-white rounded-full px-2 py-1 text-xs animate-pulse">{{ $liveMatchCount }}</span>
                    @endif
                </a>
                
                <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent">
                    <i class="fas fa-chart-bar w-5 mr-3"></i>
                    Raporlar
                </a>
                
                <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white border-l-4 border-transparent">
                    <i class="fas fa-cog w-5 mr-3"></i>
                    Ayarlar
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6 border-t border-accent">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Çıkış Yap
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-secondary shadow-sm border-b border-accent">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-xl font-bold">@yield('page-title', 'Admin Dashboard')</h1>
                        <p class="text-sm text-gray-400">@yield('page-description', 'Sistem yönetim paneli')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-400 hover:text-gold relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">3</span>
                            </button>
                        </div>
                        
                        <!-- Admin Info -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ auth()->user()->role_display }}</div>
                            </div>
                            <div class="bg-gold rounded-full p-2">
                                <i class="fas fa-user-shield text-black"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-600 text-white py-3 px-6 border-l-4 border-green-400">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600 text-white py-3 px-6 border-l-4 border-red-400">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-primary p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
