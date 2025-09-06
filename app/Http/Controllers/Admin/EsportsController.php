<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EsportsController extends Controller
{
    /**
     * Display a listing of esports games.
     */
    public function index(Request $request)
    {
        $query = Game::where('category', 'esports');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $games = $query->latest()->paginate(20);
        $esportTypes = ['Counter-Strike 2', 'League of Legends', 'Dota 2', 'Valorant', 'Overwatch 2', 'Rainbow Six Siege'];

        return view('admin.esports.index', compact('games', 'esportTypes'));
    }

    /**
     * Show the form for creating a new esports game.
     */
    public function create()
    {
        $esportTypes = ['Counter-Strike 2', 'League of Legends', 'Dota 2', 'Valorant', 'Overwatch 2', 'Rainbow Six Siege'];
        return view('admin.esports.create', compact('esportTypes'));
    }

    /**
     * Store a newly created esports game in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100', // CS:GO, LoL, etc.
            'provider' => 'nullable|string|max:100', // Tournament organizer
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'description' => 'nullable|string',
            'tournament_name' => 'nullable|string|max:255',
            'team1_name' => 'required|string|max:100',
            'team1_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'team2_name' => 'required|string|max:100', 
            'team2_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'match_date' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!strtotime($value)) {
                    $fail('Geçerli bir tarih ve saat girin.');
                }
            }],
            'is_live' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Generate unique slug
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['category'] = 'esports';

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/games'), $filename);
            $validated['thumbnail'] = 'images/games/' . $filename;
        }

        // Handle team logos
        $teamLogos = [];
        if ($request->hasFile('team1_logo')) {
            $file = $request->file('team1_logo');
            $filename = time() . '_team1_' . Str::slug($validated['team1_name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/teams'), $filename);
            $teamLogos['team1_logo'] = 'images/teams/' . $filename;
        }

        if ($request->hasFile('team2_logo')) {
            $file = $request->file('team2_logo');
            $filename = time() . '_team2_' . Str::slug($validated['team2_name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/teams'), $filename);
            $teamLogos['team2_logo'] = 'images/teams/' . $filename;
        }

        // Create esports data structure
        $esportsData = [
            'tournament_name' => $validated['tournament_name'],
            'team1_name' => $validated['team1_name'],
            'team2_name' => $validated['team2_name'],
            'team1_logo' => $teamLogos['team1_logo'] ?? null,
            'team2_logo' => $teamLogos['team2_logo'] ?? null,
            'match_date' => $validated['match_date'],
            'description' => $validated['description'] ?? null,
        ];

        // Set default values
        $validated['is_live'] = $request->has('is_live');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['min_bet'] = 10.00;
        $validated['max_bet'] = 25000.00;

        // Store esports data in bet_data field (we'll use this as esports_data)
        $validated['bet_data'] = json_encode($esportsData);

        // Remove fields that don't exist in games table
        unset($validated['tournament_name'], $validated['team1_name'], $validated['team2_name'], 
              $validated['team1_logo'], $validated['team2_logo'], $validated['match_date'], $validated['description']);

        Game::create($validated);

        return redirect()->route('admin.esports.index')->with('success', 'E-spor maçı başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified esports game.
     */
    public function edit(Game $esport)
    {
        if ($esport->category !== 'esports') {
            abort(404);
        }

        $esportTypes = ['Counter-Strike 2', 'League of Legends', 'Dota 2', 'Valorant', 'Overwatch 2', 'Rainbow Six Siege'];
        $esportsData = json_decode($esport->bet_data, true) ?? [];
        
        return view('admin.esports.edit', compact('esport', 'esportTypes', 'esportsData'));
    }

    /**
     * Update the specified esports game in storage.
     */
    public function update(Request $request, Game $esport)
    {
        if ($esport->category !== 'esports') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'provider' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'description' => 'nullable|string',
            'tournament_name' => 'nullable|string|max:255',
            'team1_name' => 'required|string|max:100',
            'team1_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'team2_name' => 'required|string|max:100',
            'team2_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'match_date' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!strtotime($value)) {
                    $fail('Geçerli bir tarih ve saat girin.');
                }
            }],
            'is_live' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $esport->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $esport->id);
        }

        // Get existing esports data
        $esportsData = json_decode($esport->bet_data, true) ?? [];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($esport->thumbnail && file_exists(public_path($esport->thumbnail))) {
                unlink(public_path($esport->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/games'), $filename);
            $validated['thumbnail'] = 'images/games/' . $filename;
        }

        // Handle team logo uploads
        if ($request->hasFile('team1_logo')) {
            if (isset($esportsData['team1_logo']) && file_exists(public_path($esportsData['team1_logo']))) {
                unlink(public_path($esportsData['team1_logo']));
            }

            $file = $request->file('team1_logo');
            $filename = time() . '_team1_' . Str::slug($validated['team1_name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/teams'), $filename);
            $esportsData['team1_logo'] = 'images/teams/' . $filename;
        }

        if ($request->hasFile('team2_logo')) {
            if (isset($esportsData['team2_logo']) && file_exists(public_path($esportsData['team2_logo']))) {
                unlink(public_path($esportsData['team2_logo']));
            }

            $file = $request->file('team2_logo');
            $filename = time() . '_team2_' . Str::slug($validated['team2_name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/teams'), $filename);
            $esportsData['team2_logo'] = 'images/teams/' . $filename;
        }

        // Update esports data
        $esportsData['tournament_name'] = $validated['tournament_name'];
        $esportsData['team1_name'] = $validated['team1_name'];
        $esportsData['team2_name'] = $validated['team2_name'];
        $esportsData['match_date'] = $validated['match_date'];
        $esportsData['description'] = $validated['description'];

        // Set boolean values
        $validated['is_live'] = $request->has('is_live');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['bet_data'] = json_encode($esportsData);

        // Remove fields that don't exist in games table
        unset($validated['tournament_name'], $validated['team1_name'], $validated['team2_name'], 
              $validated['team1_logo'], $validated['team2_logo'], $validated['match_date'], $validated['description']);

        $esport->update($validated);

        return redirect()->route('admin.esports.show', $esport)->with('success', 'E-spor maçı güncellendi.');
    }

    /**
     * Display the specified esports game.
     */
    public function show(Game $esport)
    {
        if ($esport->category !== 'esports') {
            abort(404);
        }

        $esportsData = json_decode($esport->bet_data, true) ?? [];
        return view('admin.esports.show', compact('esport', 'esportsData'));
    }

    /**
     * Remove the specified esports game from storage.
     */
    public function destroy(Game $esport)
    {
        if ($esport->category !== 'esports') {
            abort(404);
        }

        // Delete thumbnail and team logos
        if ($esport->thumbnail && file_exists(public_path($esport->thumbnail))) {
            unlink(public_path($esport->thumbnail));
        }

        $esportsData = json_decode($esport->bet_data, true) ?? [];
        if (isset($esportsData['team1_logo']) && file_exists(public_path($esportsData['team1_logo']))) {
            unlink(public_path($esportsData['team1_logo']));
        }
        if (isset($esportsData['team2_logo']) && file_exists(public_path($esportsData['team2_logo']))) {
            unlink(public_path($esportsData['team2_logo']));
        }

        $esport->delete();

        return redirect()->route('admin.esports.index')->with('success', 'E-spor maçı başarıyla silindi.');
    }

    /**
     * Toggle esports match status.
     */
    public function toggleStatus(Game $esport)
    {
        if ($esport->category !== 'esports') {
            abort(404);
        }

        $esport->update(['is_active' => !$esport->is_active]);

        $status = $esport->is_active ? 'aktif' : 'pasif';
        return redirect()->back()->with('success', "E-spor maçı durumu '{$status}' olarak güncellendi.");
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Game $esport)
    {
        if ($esport->category !== 'esports') {
            abort(404);
        }

        $esport->update(['is_featured' => !$esport->is_featured]);

        $status = $esport->is_featured ? 'öne çıkarıldı' : 'öne çıkan maçlardan kaldırıldı';
        return redirect()->back()->with('success', "E-spor maçı {$status}.");
    }

    /**
     * Generate unique slug for esports game.
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Game::where('slug', $slug)->when($excludeId, function($query, $excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}