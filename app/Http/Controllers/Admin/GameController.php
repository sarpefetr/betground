<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GameController extends Controller
{
    /**
     * Display a listing of games.
     */
    public function index(Request $request)
    {
        $query = Game::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $games = $query->latest()->paginate(20);
        $categories = ['slots', 'casino', 'sports', 'esports', 'virtual'];

        return view('admin.games.index', compact('games', 'categories'));
    }

    /**
     * Show the form for creating a new game.
     */
    public function create()
    {
        $categories = ['slots', 'casino', 'sports', 'esports', 'virtual'];
        return view('admin.games.create', compact('categories'));
    }

    /**
     * Store a newly created game in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:slots,casino,sports,esports,virtual',
            'type' => 'nullable|string|max:100',
            'provider' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'rtp' => 'nullable|numeric|between:0,100',
            'min_bet' => 'required|numeric|min:0',
            'max_bet' => 'required|numeric|min:0',
            'is_live' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer',
        ]);

        // Generate unique slug
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/games'), $filename);
            $validated['thumbnail'] = 'images/games/' . $filename;
        }

        // Set default values
        $validated['is_live'] = $request->has('is_live');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['order_index'] = $validated['order_index'] ?? 0;

        Game::create($validated);

        return redirect()->route('admin.games.index')->with('success', 'Oyun başarıyla oluşturuldu.');
    }

    /**
     * Display the specified game.
     */
    public function show(Game $game)
    {
        $game->load(['bets' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.games.show', compact('game'));
    }

    /**
     * Show the form for editing the specified game.
     */
    public function edit(Game $game)
    {
        $categories = ['slots', 'casino', 'sports', 'esports', 'virtual'];
        return view('admin.games.edit', compact('game', 'categories'));
    }

    /**
     * Update the specified game in storage.
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:slots,casino,sports,esports,virtual',
            'type' => 'nullable|string|max:100',
            'provider' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'rtp' => 'nullable|numeric|between:0,100',
            'min_bet' => 'required|numeric|min:0',
            'max_bet' => 'required|numeric|min:0',
            'is_live' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $game->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $game->id);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($game->thumbnail && file_exists(public_path($game->thumbnail))) {
                unlink(public_path($game->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/games'), $filename);
            $validated['thumbnail'] = 'images/games/' . $filename;
        }

        // Set boolean values
        $validated['is_live'] = $request->has('is_live');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['order_index'] = $validated['order_index'] ?? $game->order_index;

        $game->update($validated);

        return redirect()->route('admin.games.show', $game)->with('success', 'Oyun bilgileri güncellendi.');
    }

    /**
     * Remove the specified game from storage.
     */
    public function destroy(Game $game)
    {
        // Delete thumbnail if exists
        if ($game->thumbnail && file_exists(public_path($game->thumbnail))) {
            unlink(public_path($game->thumbnail));
        }

        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Oyun başarıyla silindi.');
    }

    /**
     * Toggle game status.
     */
    public function toggleStatus(Game $game)
    {
        $game->update(['is_active' => !$game->is_active]);

        $status = $game->is_active ? 'aktif' : 'pasif';
        return redirect()->back()->with('success', "Oyun durumu '{$status}' olarak güncellendi.");
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Game $game)
    {
        $game->update(['is_featured' => !$game->is_featured]);

        $status = $game->is_featured ? 'öne çıkarıldı' : 'öne çıkan oyunlardan kaldırıldı';
        return redirect()->back()->with('success', "Oyun {$status}.");
    }

    /**
     * Generate unique slug for game.
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