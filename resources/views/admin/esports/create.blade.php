@extends('layouts.admin')

@section('title', 'Yeni E-Spor Maçı Ekle - Admin Panel')
@section('page-title', 'Yeni E-Spor Maçı Ekle')
@section('page-description', 'CS:GO, LoL, Valorant ve diğer e-spor maçları ekleyin')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.esports.index') }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>E-Spor Listesi
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">Yeni E-Spor Maçı Bilgileri</h2>
        
        <form method="POST" action="{{ route('admin.esports.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Match Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Maç Bilgileri</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Maç Adı *</label>
                        <input type="text" 
                               id="name" 
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('name') border-red-500 @enderror"
                               placeholder="Örn: FaZe vs NAVI - ESL Pro League Final"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium mb-2">Oyun Türü *</label>
                            <select id="type" 
                                    name="type"
                                    class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('type') border-red-500 @enderror"
                                    required>
                                <option value="">Oyun Seçin</option>
                                @foreach($esportTypes as $type)
                                    <option value="{{ $type }}" @if(old('type') == $type) selected @endif>
                                        @if($type === 'Counter-Strike 2') 🔫 {{ $type }}
                                        @elseif($type === 'League of Legends') 🏆 {{ $type }}
                                        @elseif($type === 'Dota 2') ⚔️ {{ $type }}
                                        @elseif($type === 'Valorant') 🎯 {{ $type }}
                                        @elseif($type === 'Overwatch 2') 🦾 {{ $type }}
                                        @elseif($type === 'Rainbow Six Siege') 🏠 {{ $type }}
                                        @else 🎮 {{ $type }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="provider" class="block text-sm font-medium mb-2">Turnuva Organizatörü</label>
                            <input type="text" 
                                   id="provider" 
                                   name="provider"
                                   value="{{ old('provider') }}"
                                   class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('provider') border-red-500 @enderror"
                                   placeholder="Örn: ESL, BLAST, PGL">
                            @error('provider')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="tournament_name" class="block text-sm font-medium mb-2">Turnuva Adı</label>
                        <input type="text" 
                               id="tournament_name" 
                               name="tournament_name"
                               value="{{ old('tournament_name') }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('tournament_name') border-red-500 @enderror"
                               placeholder="Örn: ESL Pro League Season 19">
                        @error('tournament_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="match_date" class="block text-sm font-medium mb-2">Maç Tarihi ve Saati *</label>
                        <input type="datetime-local" 
                               id="match_date" 
                               name="match_date"
                               value="{{ old('match_date') }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('match_date') border-red-500 @enderror"
                               required>
                        @error('match_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium mb-2">Açıklama</label>
                        <textarea id="description" 
                                  name="description"
                                  rows="3"
                                  class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('description') border-red-500 @enderror"
                                  placeholder="Maç hakkında ek bilgiler...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="thumbnail" class="block text-sm font-medium mb-2">Maç/Turnuva Resmi</label>
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

                <!-- Teams Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Takım Bilgileri</h3>
                    
                    <!-- Team 1 -->
                    <div class="bg-accent p-4 rounded-lg">
                        <h4 class="font-bold mb-3 text-blue-400">1. Takım</h4>
                        <div>
                            <label for="team1_name" class="block text-sm font-medium mb-2">Takım Adı *</label>
                            <input type="text" 
                                   id="team1_name" 
                                   name="team1_name"
                                   value="{{ old('team1_name') }}"
                                   class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('team1_name') border-red-500 @enderror"
                                   placeholder="Örn: FaZe Clan"
                                   required>
                            @error('team1_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <label for="team1_logo" class="block text-sm font-medium mb-2">Takım Logosu</label>
                            <input type="file" 
                                   id="team1_logo" 
                                   name="team1_logo"
                                   accept="image/*"
                                   class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('team1_logo') border-red-500 @enderror">
                            @error('team1_logo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Team 2 -->
                    <div class="bg-accent p-4 rounded-lg">
                        <h4 class="font-bold mb-3 text-red-400">2. Takım</h4>
                        <div>
                            <label for="team2_name" class="block text-sm font-medium mb-2">Takım Adı *</label>
                            <input type="text" 
                                   id="team2_name" 
                                   name="team2_name"
                                   value="{{ old('team2_name') }}"
                                   class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('team2_name') border-red-500 @enderror"
                                   placeholder="Örn: NAVI"
                                   required>
                            @error('team2_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <label for="team2_logo" class="block text-sm font-medium mb-2">Takım Logosu</label>
                            <input type="file" 
                                   id="team2_logo" 
                                   name="team2_logo"
                                   accept="image/*"
                                   class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('team2_logo') border-red-500 @enderror">
                            @error('team2_logo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status Settings -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-gold">Maç Durumu</h4>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_live"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_live')) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-circle text-red-400 mr-1"></i>Canlı Maç
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_featured')) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-star text-gold mr-1"></i>Öne Çıkan Maç
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_active', true)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-eye text-green-400 mr-1"></i>Aktif Maç
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 mt-8">
                <button type="submit" class="bg-gold text-black px-8 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>E-Spor Maçını Kaydet
                </button>
                <a href="{{ route('admin.esports.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection



