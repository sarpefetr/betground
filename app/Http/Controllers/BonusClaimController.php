<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\UserBonusClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusClaimController extends Controller
{
    /**
     * Create bonus claim request.
     */
    public function claim(Request $request, Bonus $bonus)
    {
        $user = auth()->user();

        // Check if bonus is valid
        if (!$bonus->isValid()) {
            return redirect()->back()->with('error', 'Bu bonus artık geçerli değil.');
        }

        // Check if user can claim this bonus
        if (!$bonus->canUserClaim($user)) {
            return redirect()->back()->with('error', 'Bu bonusu alamazsınız.');
        }

        // Check if user has already claimed this bonus
        if ($bonus->hasUserClaimed($user->id)) {
            return redirect()->back()->with('error', 'Bu bonusu zaten talep ettiniz.');
        }

        // Calculate potential bonus amount
        $userBalance = $user->wallet->balance ?? 0;
        $claimedAmount = 0;

        if ($bonus->amount_type === 'percentage') {
            // For percentage bonuses, we need deposit amount
            if ($bonus->bonus_type === 'welcome' || $bonus->bonus_type === 'daily' || $bonus->bonus_type === 'weekly') {
                $claimedAmount = min(
                    ($bonus->min_deposit * $bonus->amount_value / 100),
                    $bonus->max_bonus ?? PHP_INT_MAX
                );
            } else {
                $claimedAmount = $bonus->amount_value; // For cashback, etc.
            }
        } else {
            $claimedAmount = $bonus->amount_value;
        }

        // Store bonus data at time of claim
        $bonusData = [
            'bonus_name' => $bonus->name,
            'bonus_type' => $bonus->bonus_type,
            'amount_type' => $bonus->amount_type,
            'amount_value' => $bonus->amount_value,
            'min_deposit' => $bonus->min_deposit,
            'max_bonus' => $bonus->max_bonus,
            'wagering_requirement' => $bonus->wagering_requirement,
            'user_balance_at_claim' => $userBalance,
        ];

        try {
            DB::beginTransaction();

            UserBonusClaim::create([
                'user_id' => $user->id,
                'bonus_id' => $bonus->id,
                'claimed_amount' => $claimedAmount,
                'user_message' => $request->input('message'),
                'bonus_data' => $bonusData,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Bonus talebiniz başarıyla gönderildi! Admin onayı bekleniyor.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Talep gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Show user's bonus claim history.
     */
    public function myBonuses()
    {
        $user = auth()->user();
        $claims = $user->bonusClaims()->with('bonus')->latest()->paginate(10);

        return view('user.bonus-claims', compact('claims'));
    }
}