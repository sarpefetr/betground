<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    /**
     * Display a listing of deposits.
     */
    public function index(Request $request)
    {
        $query = Deposit::with(['user.wallet']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by method
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        $deposits = $query->latest()->paginate(20);
        $statusOptions = ['pending', 'completed', 'failed', 'cancelled'];
        $methods = ['bank_transfer', 'credit_card', 'crypto', 'ewallet', 'mobile', 'atm'];

        return view('admin.deposits.index', compact('deposits', 'statusOptions', 'methods'));
    }

    /**
     * Display the specified deposit.
     */
    public function show(Deposit $deposit)
    {
        $deposit->load(['user.wallet', 'transaction']);
        return view('admin.deposits.show', compact('deposit'));
    }

    /**
     * Approve deposit.
     */
    public function approve(Request $request, Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'Bu yatırım talebi zaten işlenmiş.');
        }

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        $adminNote = $validated['admin_note'] ?? '';

        try {
            DB::beginTransaction();

            // Update deposit status
            $deposit->update([
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Add money to user wallet
            $user = $deposit->user;
            $wallet = $user->wallet;

            if ($wallet) {
                $oldBalance = $wallet->balance;
                $newBalance = $oldBalance + $deposit->amount;
                
                $wallet->update(['balance' => $newBalance]);

                // Create transaction record
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'type' => 'deposit',
                    'amount' => $deposit->amount,
                    'balance_before' => $oldBalance,
                    'balance_after' => $newBalance,
                    'currency' => $deposit->currency,
                    'description' => "Yatırım onaylandı - {$deposit->reference_number}" . ($adminNote ? " | Not: {$adminNote}" : ""),
                    'reference_type' => 'deposit',
                    'reference_id' => $deposit->id,
                    'status' => 'completed',
                ]);

                // Link transaction to deposit
                $deposit->update(['transaction_id' => $transaction->id]);
            }

            DB::commit();

            return redirect()->back()->with('success', 
                "Yatırım onaylandı! Kullanıcıya ₺{$deposit->amount} eklendi."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Yatırım onaylanırken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Reject deposit.
     */
    public function reject(Request $request, Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'Bu yatırım talebi zaten işlenmiş.');
        }

        $validated = $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        try {
            $deposit->update([
                'status' => 'failed',
                'processed_at' => now(),
            ]);

            // Create failed transaction record for tracking
            Transaction::create([
                'user_id' => $deposit->user_id,
                'wallet_id' => $deposit->user->wallet->id ?? null,
                'type' => 'deposit',
                'amount' => 0, // No amount added
                'balance_before' => $deposit->user->wallet->balance ?? 0,
                'balance_after' => $deposit->user->wallet->balance ?? 0,
                'currency' => $deposit->currency,
                'description' => "Yatırım reddedildi - {$deposit->reference_number} | Sebep: {$validated['admin_note']}",
                'reference_type' => 'deposit',
                'reference_id' => $deposit->id,
                'status' => 'failed',
            ]);

            return redirect()->back()->with('success', 'Yatırım talebi reddedildi.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Yatırım reddedilirken hata oluştu: ' . $e->getMessage());
        }
    }
}