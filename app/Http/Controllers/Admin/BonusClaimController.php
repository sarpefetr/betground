<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserBonusClaim;
use Illuminate\Http\Request;

class BonusClaimController extends Controller
{
    /**
     * Display a listing of bonus claims.
     */
    public function index(Request $request)
    {
        $query = UserBonusClaim::with(['user', 'bonus', 'processedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by bonus type
        if ($request->filled('bonus_type')) {
            $query->whereHas('bonus', function($q) use ($request) {
                $q->where('bonus_type', $request->bonus_type);
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('bonus', function($bonusQuery) use ($search) {
                    $bonusQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $claims = $query->latest()->paginate(20);
        $statusOptions = ['pending', 'approved', 'rejected'];
        $bonusTypes = ['welcome', 'daily', 'weekly', 'cashback', 'referral', 'vip', 'tournament', 'special'];

        return view('admin.bonus-claims.index', compact('claims', 'statusOptions', 'bonusTypes'));
    }

    /**
     * Display the specified bonus claim.
     */
    public function show(UserBonusClaim $claim)
    {
        $claim->load(['user.wallet', 'bonus', 'processedBy']);
        return view('admin.bonus-claims.show', compact('claim'));
    }

    /**
     * Approve bonus claim.
     */
    public function approve(Request $request, UserBonusClaim $claim)
    {
        if (!$claim->canBeProcessed()) {
            return redirect()->back()->with('error', 'Bu talep zaten işlenmiş.');
        }

        $validated = $request->validate([
            'awarded_amount' => 'required|numeric|min:0.01',
            'admin_message' => 'nullable|string|max:500',
        ]);

        try {
            $claim->approve(
                auth()->id(), 
                $validated['awarded_amount'],
                $validated['admin_message']
            );

            return redirect()->back()->with('success', 
                "Bonus talebi onaylandı! Kullanıcıya ₺{$validated['awarded_amount']} bonus verildi.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bonus onaylanırken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Reject bonus claim.
     */
    public function reject(Request $request, UserBonusClaim $claim)
    {
        if (!$claim->canBeProcessed()) {
            return redirect()->back()->with('error', 'Bu talep zaten işlenmiş.');
        }

        $validated = $request->validate([
            'admin_message' => 'required|string|max:500',
        ]);

        try {
            $claim->reject(auth()->id(), $validated['admin_message']);

            return redirect()->back()->with('success', 'Bonus talebi reddedildi.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bonus reddedilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for bonus claims.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'claim_ids' => 'required|array',
            'claim_ids.*' => 'exists:user_bonus_claims,id',
            'bulk_message' => 'nullable|string|max:500',
        ]);

        $claims = UserBonusClaim::whereIn('id', $validated['claim_ids'])
                                ->where('status', 'pending')
                                ->get();

        $processedCount = 0;

        foreach ($claims as $claim) {
            try {
                if ($validated['action'] === 'approve') {
                    $claim->approve(auth()->id(), $claim->claimed_amount, $validated['bulk_message']);
                } else {
                    $claim->reject(auth()->id(), $validated['bulk_message'] ?: 'Toplu işlem ile reddedildi');
                }
                $processedCount++;
            } catch (\Exception $e) {
                // Continue processing other claims
                continue;
            }
        }

        $action = $validated['action'] === 'approve' ? 'onaylandı' : 'reddedildi';
        return redirect()->back()->with('success', "{$processedCount} bonus talebi {$action}.");
    }
}