@extends('layouts.app')

@section('title', 'Sanal Sporlar - BetGround')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <section class="mb-12">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4 text-glow">
                <i class="fas fa-robot mr-3"></i>Sanal Sporlar
            </h1>
            <p class="text-xl text-gray-300">7/24 sanal maçlar ve yarışlar</p>
        </div>
    </section>

    <!-- Virtual Sports Games -->
    <section class="mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-secondary rounded-xl p-6 card-hover">
                <div class="text-center mb-4">
                    <div class="text-5xl mb-4">⚽</div>
                    <h3 class="text-xl font-bold mb-2">Sanal Futbol</h3>
                    <p class="text-gray-300 text-sm">Her 3 dakikada bir maç</p>
                </div>
                <div class="text-center">
                    <div class="text-gold font-bold mb-2">Sonraki Maç: 2:45</div>
                    <button class="bg-gold text-black px-6 py-2 rounded font-medium hover:bg-yellow-500 transition-colors" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Sanal spor yakında!')" @endguest>
                        Bahis Yap
                    </button>
                </div>
            </div>
            
            <div class="bg-secondary rounded-xl p-6 card-hover">
                <div class="text-center mb-4">
                    <div class="text-5xl mb-4">🏇</div>
                    <h3 class="text-xl font-bold mb-2">At Yarışı</h3>
                    <p class="text-gray-300 text-sm">Her 2 dakikada bir yarış</p>
                </div>
                <div class="text-center">
                    <div class="text-gold font-bold mb-2">Sonraki Yarış: 1:23</div>
                    <button class="bg-gold text-black px-6 py-2 rounded font-medium hover:bg-yellow-500 transition-colors" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Sanal spor yakında!')" @endguest>
                        Bahis Yap
                    </button>
                </div>
            </div>
            
            <div class="bg-secondary rounded-xl p-6 card-hover">
                <div class="text-center mb-4">
                    <div class="text-5xl mb-4">🏀</div>
                    <h3 class="text-xl font-bold mb-2">Sanal Basketbol</h3>
                    <p class="text-gray-300 text-sm">Her 5 dakikada bir maç</p>
                </div>
                <div class="text-center">
                    <div class="text-gold font-bold mb-2">Sonraki Maç: 4:12</div>
                    <button class="bg-gold text-black px-6 py-2 rounded font-medium hover:bg-yellow-500 transition-colors" @guest onclick="window.location='{{ route('login') }}'" @else onclick="alert('Sanal spor yakında!')" @endguest>
                        Bahis Yap
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection



