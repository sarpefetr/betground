@extends('layouts.admin')

@section('title', $esport->name . ' - E-Spor Maçını Düzenle')
@section('page-title', 'E-Spor Maçını Düzenle')
@section('page-description', $esport->name . ' maçını düzenle')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.esports.show', $esport) }}" class="text-gold hover:text-yellow-500">
            <i class="fas fa-arrow-left mr-2"></i>Maç Detayına Dön
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-secondary rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">{{ $esport->name }} - Maç Bilgilerini Düzenle</h2>
        
        <form method="POST" action="{{ route('admin.esports.update', $esport) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Match Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gold">Maç Bilgileri</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Maç Adı *</label>
                        <input type="text" 
                               id="name" 
                               name="name"
                               value="{{ old('name', $esport->name) }}"
                               class="w-full bg-accent border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('name') border-red-500 @enderror"
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
                                @foreach(['Counter-Strike 2', 'League of Legends', 'Dota 2', 'Valorant', 'Overwatch 2', 'Rainbow Six Siege'] as $type)
                                    <option value="{{ $type }}" @if(old('type', $esport->type) == $type) selected @endif>
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
                                   value="{{ old('provider', $esport->provider) }}"
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
                               value="{{ old('tournament_name', $esportsData['tournament_name'] ?? '') }}"
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
                               value="{{ old('match_date', isset($esportsData['match_date']) ? \Carbon\Carbon::parse($esportsData['match_date'])->format('Y-m-d\TH:i') : '') }}"
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
                                  placeholder="Maç hakkında ek bilgiler...">{{ old('description', $esportsData['description'] ?? '') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Thumbnail -->
                    @if($esport->thumbnail)
                        <div>
                            <label class="block text-sm font-medium mb-2">Mevcut Maç Resmi</label>
                            <div class="w-32 h-32 bg-accent rounded-lg overflow-hidden">
                                <img src="{{ $esport->thumbnail_url }}" alt="{{ $esport->name }}" class="w-full h-full object-cover object-center">
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="thumbnail" class="block text-sm font-medium mb-2">Yeni Maç/Turnuva Resmi</label>
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
                        
                        @if(isset($esportsData['team1_logo']))
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-2">Mevcut Logo</label>
                                <div class="w-16 h-16 bg-secondary rounded-lg overflow-hidden">
                                    <img src="{{ asset($esportsData['team1_logo']) }}" alt="Team 1 Logo" class="w-full h-full object-cover object-center">
                                </div>
                            </div>
                        @endif
                        
                        <div>
                            <label for="team1_name" class="block text-sm font-medium mb-2">Takım Adı *</label>
                            <input type="text" 
                                   id="team1_name" 
                                   name="team1_name"
                                   value="{{ old('team1_name', $esportsData['team1_name'] ?? '') }}"
                                   class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('team1_name') border-red-500 @enderror"
                                   required>
                            @error('team1_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <label for="team1_logo" class="block text-sm font-medium mb-2">Yeni Takım Logosu</label>
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
                        
                        @if(isset($esportsData['team2_logo']))
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-2">Mevcut Logo</label>
                                <div class="w-16 h-16 bg-secondary rounded-lg overflow-hidden">
                                    <img src="{{ asset($esportsData['team2_logo']) }}" alt="Team 2 Logo" class="w-full h-full object-cover object-center">
                                </div>
                            </div>
                        @endif
                        
                        <div>
                            <label for="team2_name" class="block text-sm font-medium mb-2">Takım Adı *</label>
                            <input type="text" 
                                   id="team2_name" 
                                   name="team2_name"
                                   value="{{ old('team2_name', $esportsData['team2_name'] ?? '') }}"
                                   class="w-full bg-secondary border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-gold @error('team2_name') border-red-500 @enderror"
                                   required>
                            @error('team2_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <label for="team2_logo" class="block text-sm font-medium mb-2">Yeni Takım Logosu</label>
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
                                   @if(old('is_live', $esport->is_live)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-circle text-red-400 mr-1"></i>Canlı Maç
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_featured', $esport->is_featured)) checked @endif>
                            <span class="ml-3 text-sm">
                                <i class="fas fa-star text-gold mr-1"></i>Öne Çıkan Maç
                            </span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active"
                                   value="1"
                                   class="w-4 h-4 text-gold bg-accent border-gray-600 rounded focus:ring-gold focus:ring-2"
                                   @if(old('is_active', $esport->is_active)) checked @endif>
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
                    <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                </button>
                <a href="{{ route('admin.esports.show', $esport) }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection



