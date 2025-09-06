<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of payment methods.
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $paymentMethods = $query->with('parent', 'children')->latest()->paginate(20);
        $categories = PaymentMethod::getCategories();

        return view('admin.payment-methods.index', compact('paymentMethods', 'categories'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create(Request $request)
    {
        $categories = PaymentMethod::getCategories();
        $type = $request->get('type', 'method');
        $parentId = $request->get('parent_id');
        
        $methodCodes = [
            'bank_transfer' => 'Banka Transferi',
            'credit_card' => 'Kredi/Banka Kartı', 
            'crypto' => 'Kripto Para',
            'ewallet' => 'E-Cüzdan',
            'mobile' => 'Mobil Ödeme',
            'atm' => 'ATM'
        ];

        return view('admin.payment-methods.create', compact('categories', 'type', 'parentId', 'methodCodes'));
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:category,method',
            'parent_id' => 'nullable|exists:payment_methods,id',
            'method_code' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'processing_time' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_iban' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'supported_currencies' => 'nullable|array',
        ]);

        // Generate unique slug
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/payments'), $filename);
            $validated['image'] = 'images/payments/' . $filename;
        }

        // Prepare bank details
        if ($request->filled(['bank_name', 'bank_iban', 'account_holder'])) {
            $validated['bank_details'] = [
                'bank_name' => $request->bank_name,
                'bank_iban' => $request->bank_iban,
                'account_holder' => $request->account_holder,
            ];
        }

        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['order_index'] = $validated['order_index'] ?? 0;

        // Remove non-model fields
        unset($validated['bank_name'], $validated['bank_iban'], $validated['account_holder']);

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Ödeme yöntemi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        $paymentMethod->load(['parent', 'children', 'deposits']);
        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $categories = PaymentMethod::getCategories();
        $methodCodes = [
            'bank_transfer' => 'Banka Transferi',
            'credit_card' => 'Kredi/Banka Kartı', 
            'crypto' => 'Kripto Para',
            'ewallet' => 'E-Cüzdan',
            'mobile' => 'Mobil Ödeme',
            'atm' => 'ATM'
        ];

        return view('admin.payment-methods.edit', compact('paymentMethod', 'categories', 'methodCodes'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:category,method',
            'parent_id' => 'nullable|exists:payment_methods,id',
            'method_code' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'processing_time' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_iban' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'supported_currencies' => 'nullable|array',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $paymentMethod->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $paymentMethod->id);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($paymentMethod->image && file_exists(public_path($paymentMethod->image))) {
                unlink(public_path($paymentMethod->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/payments'), $filename);
            $validated['image'] = 'images/payments/' . $filename;
        }

        // Prepare bank details
        if ($request->filled(['bank_name', 'bank_iban', 'account_holder'])) {
            $validated['bank_details'] = [
                'bank_name' => $request->bank_name,
                'bank_iban' => $request->bank_iban,
                'account_holder' => $request->account_holder,
            ];
        }

        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['order_index'] = $validated['order_index'] ?? $paymentMethod->order_index;

        // Remove non-model fields
        unset($validated['bank_name'], $validated['bank_iban'], $validated['account_holder']);

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.show', $paymentMethod)->with('success', 'Ödeme yöntemi güncellendi.');
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Delete image if exists
        if ($paymentMethod->image && file_exists(public_path($paymentMethod->image))) {
            unlink(public_path($paymentMethod->image));
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'Ödeme yöntemi başarıyla silindi.');
    }

    /**
     * Toggle payment method status.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

        $status = $paymentMethod->is_active ? 'aktif' : 'pasif';
        return redirect()->back()->with('success', "Ödeme yöntemi durumu '{$status}' olarak güncellendi.");
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_featured' => !$paymentMethod->is_featured]);

        $status = $paymentMethod->is_featured ? 'öne çıkarıldı' : 'öne çıkan yöntemlerden kaldırıldı';
        return redirect()->back()->with('success', "Ödeme yöntemi {$status}.");
    }

    /**
     * Generate unique slug.
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (PaymentMethod::where('slug', $slug)->when($excludeId, function($query, $excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}