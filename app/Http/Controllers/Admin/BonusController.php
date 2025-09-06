<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BonusController extends Controller
{
    /**
     * Display a listing of bonuses.
     */
    public function index(Request $request)
    {
        $query = Bonus::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by bonus type
        if ($request->filled('bonus_type')) {
            $query->where('bonus_type', $request->bonus_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $bonuses = $query->latest()->paginate(20);
        $bonusTypes = ['welcome', 'daily', 'weekly', 'cashback', 'referral', 'vip', 'tournament', 'special'];

        return view('admin.bonuses.index', compact('bonuses', 'bonusTypes'));
    }

    /**
     * Show the form for creating a new bonus.
     */
    public function create()
    {
        $bonusTypes = ['welcome', 'daily', 'weekly', 'cashback', 'referral', 'vip', 'tournament', 'special'];
        $amountTypes = ['percentage', 'fixed'];
        $currencies = ['TRY', 'USD', 'EUR'];
        $countries = ['TR', 'US', 'DE', 'FR', 'UK'];

        return view('admin.bonuses.create', compact('bonusTypes', 'amountTypes', 'currencies', 'countries'));
    }

    /**
     * Store a newly created bonus in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'bonus_type' => 'required|in:welcome,daily,weekly,cashback,referral,vip,tournament,special',
            'amount_type' => 'required|in:percentage,fixed',
            'amount_value' => 'required|numeric|min:0',
            'min_deposit' => 'required|numeric|min:0',
            'max_bonus' => 'nullable|numeric|min:0',
            'wagering_requirement' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'terms_conditions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'required|integer|min:1',
            'order_index' => 'nullable|integer|min:0',
            'countries' => 'nullable|array',
            'currencies' => 'nullable|array',
        ]);

        // Generate unique slug
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/bonuses'), $filename);
            $validated['image'] = 'images/bonuses/' . $filename;
        }

        // Set default values
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['order_index'] = $validated['order_index'] ?? 0;

        Bonus::create($validated);

        return redirect()->route('admin.bonuses.index')->with('success', 'Bonus başarıyla oluşturuldu.');
    }

    /**
     * Display the specified bonus.
     */
    public function show(Bonus $bonus)
    {
        return view('admin.bonuses.show', compact('bonus'));
    }

    /**
     * Show the form for editing the specified bonus.
     */
    public function edit(Bonus $bonus)
    {
        $bonusTypes = ['welcome', 'daily', 'weekly', 'cashback', 'referral', 'vip', 'tournament', 'special'];
        $amountTypes = ['percentage', 'fixed'];
        $currencies = ['TRY', 'USD', 'EUR'];
        $countries = ['TR', 'US', 'DE', 'FR', 'UK'];

        return view('admin.bonuses.edit', compact('bonus', 'bonusTypes', 'amountTypes', 'currencies', 'countries'));
    }

    /**
     * Update the specified bonus in storage.
     */
    public function update(Request $request, Bonus $bonus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'bonus_type' => 'required|in:welcome,daily,weekly,cashback,referral,vip,tournament,special',
            'amount_type' => 'required|in:percentage,fixed',
            'amount_value' => 'required|numeric|min:0',
            'min_deposit' => 'required|numeric|min:0',
            'max_bonus' => 'nullable|numeric|min:0',
            'wagering_requirement' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'terms_conditions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'required|integer|min:1',
            'order_index' => 'nullable|integer|min:0',
            'countries' => 'nullable|array',
            'currencies' => 'nullable|array',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $bonus->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $bonus->id);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($bonus->image && file_exists(public_path($bonus->image))) {
                unlink(public_path($bonus->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/bonuses'), $filename);
            $validated['image'] = 'images/bonuses/' . $filename;
        }

        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['order_index'] = $validated['order_index'] ?? $bonus->order_index;

        $bonus->update($validated);

        return redirect()->route('admin.bonuses.show', $bonus)->with('success', 'Bonus bilgileri güncellendi.');
    }

    /**
     * Remove the specified bonus from storage.
     */
    public function destroy(Bonus $bonus)
    {
        // Delete image if exists
        if ($bonus->image && file_exists(public_path($bonus->image))) {
            unlink(public_path($bonus->image));
        }

        $bonus->delete();

        return redirect()->route('admin.bonuses.index')->with('success', 'Bonus başarıyla silindi.');
    }

    /**
     * Toggle bonus status.
     */
    public function toggleStatus(Bonus $bonus)
    {
        $bonus->update(['is_active' => !$bonus->is_active]);

        $status = $bonus->is_active ? 'aktif' : 'pasif';
        return redirect()->back()->with('success', "Bonus durumu '{$status}' olarak güncellendi.");
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Bonus $bonus)
    {
        $bonus->update(['is_featured' => !$bonus->is_featured]);

        $status = $bonus->is_featured ? 'öne çıkarıldı' : 'öne çıkan bonuslardan kaldırıldı';
        return redirect()->back()->with('success', "Bonus {$status}.");
    }

    /**
     * Generate unique slug for bonus.
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Bonus::where('slug', $slug)->when($excludeId, function($query, $excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}