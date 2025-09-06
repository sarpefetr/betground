<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Show dynamic payment page (like X-Pay).
     */
    public function show(PaymentMethod $paymentMethod)
    {
        if (!$paymentMethod->isMethod() || !$paymentMethod->is_active) {
            abort(404, 'Ödeme yöntemi bulunamadı veya aktif değil.');
        }

        $user = auth()->user();
        $wallet = $user->wallet;

        return view('payment.method', compact('paymentMethod', 'user', 'wallet'));
    }

    /**
     * Process payment request.
     */
    public function process(Request $request, PaymentMethod $paymentMethod)
    {
        if (!$paymentMethod->isMethod() || !$paymentMethod->is_active) {
            abort(404, 'Ödeme yöntemi bulunamadı veya aktif değil.');
        }

        $user = auth()->user();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:' . $paymentMethod->min_amount . '|max:' . $paymentMethod->max_amount,
            'user_message' => 'nullable|string|max:500',
        ]);

        // Check if user supports this currency
        if (!$paymentMethod->supportsCurrency($user->currency)) {
            return redirect()->back()->with('error', 'Bu ödeme yöntemi sizin para biriminizi desteklemiyor.');
        }

        try {
            DB::beginTransaction();

            // Create deposit record
            $deposit = Deposit::create([
                'user_id' => $user->id,
                'method' => $paymentMethod->method_code,
                'amount' => $validated['amount'],
                'currency' => $user->currency,
                'payment_details' => [
                    'payment_method_id' => $paymentMethod->id,
                    'payment_method_name' => $paymentMethod->name,
                    'user_message' => $validated['user_message'],
                    'bank_details' => $paymentMethod->bank_details,
                ],
                'reference_number' => 'DEP-' . time() . '-' . $user->id,
                'status' => 'pending',
            ]);

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Yatırım talebiniz alındı! Admin onayı bekleniyor.',
                    'reference_number' => $deposit->reference_number
                ]);
            }

            return redirect()->back()->with('success', 
                'Yatırım talebiniz alındı! Referans No: ' . $deposit->reference_number . '. Admin onayı bekleniyor.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'İşlem sırasında hata oluştu: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'İşlem sırasında hata oluştu: ' . $e->getMessage());
        }
    }
}