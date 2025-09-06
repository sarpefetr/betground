<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Show wallet management for a specific user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load(['wallet', 'transactions' => function($query) {
            $query->latest()->take(20);
        }]);

        return view('admin.wallets.show', compact('user'));
    }

    /**
     * Add balance to user wallet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addBalance(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:balance,bonus',
            'description' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $wallet = $user->wallet;
            $oldBalance = $validated['type'] === 'bonus' ? $wallet->bonus_balance : $wallet->balance;
            $newAmount = $oldBalance + $validated['amount'];

            if ($validated['type'] === 'bonus') {
                $wallet->update(['bonus_balance' => $newAmount]);
                $transactionType = 'bonus';
            } else {
                $wallet->update(['balance' => $newAmount]);
                $transactionType = 'deposit';
            }

            // Transaction record
            Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => $transactionType,
                'amount' => $validated['amount'],
                'balance_before' => $oldBalance,
                'balance_after' => $newAmount,
                'currency' => $wallet->currency,
                'description' => $validated['description'] ?: "Admin tarafından eklendi",
                'status' => 'completed',
            ]);

            DB::commit();

            return redirect()->back()->with('success', "₺{$validated['amount']} başarıyla eklendi.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'İşlem sırasında hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove balance from user wallet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeBalance(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:balance,bonus',
            'description' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $wallet = $user->wallet;
            $currentBalance = $validated['type'] === 'bonus' ? $wallet->bonus_balance : $wallet->balance;

            if ($validated['amount'] > $currentBalance) {
                return redirect()->back()->with('error', 'Yetersiz bakiye. Mevcut bakiye: ₺' . number_format($currentBalance, 2));
            }

            $newAmount = $currentBalance - $validated['amount'];

            if ($validated['type'] === 'bonus') {
                $wallet->update(['bonus_balance' => $newAmount]);
            } else {
                $wallet->update(['balance' => $newAmount]);
            }

            // Transaction record
            Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'withdrawal',
                'amount' => -$validated['amount'],
                'balance_before' => $currentBalance,
                'balance_after' => $newAmount,
                'currency' => $wallet->currency,
                'description' => $validated['description'] ?: "Admin tarafından düşüldü",
                'status' => 'completed',
            ]);

            DB::commit();

            return redirect()->back()->with('success', "₺{$validated['amount']} başarıyla düşürüldü.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'İşlem sırasında hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Set exact balance for user wallet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setBalance(Request $request, User $user)
    {
        $validated = $request->validate([
            'balance' => 'required|numeric|min:0',
            'bonus_balance' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $wallet = $user->wallet;
            $oldBalance = $wallet->balance;
            $oldBonusBalance = $wallet->bonus_balance;

            $wallet->update([
                'balance' => $validated['balance'],
                'bonus_balance' => $validated['bonus_balance'],
            ]);

            // Transaction records
            if ($oldBalance != $validated['balance']) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'type' => 'commission', // Admin adjustment
                    'amount' => $validated['balance'] - $oldBalance,
                    'balance_before' => $oldBalance,
                    'balance_after' => $validated['balance'],
                    'currency' => $wallet->currency,
                    'description' => $validated['description'] ?: "Admin tarafından bakiye ayarlandı",
                    'status' => 'completed',
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Bakiye başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'İşlem sırasında hata oluştu: ' . $e->getMessage());
        }
    }
}