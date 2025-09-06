<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['required', 'date', 'before:18 years ago'],
            'gender' => ['nullable', 'in:male,female,other'],
            'country' => ['required', 'string', 'size:2'],
            'currency' => ['required', 'string', 'size:3'],
            'terms' => ['required', 'accepted'],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
        ], [
            'birth_date.before' => '18 yaşından büyük olmalısınız.',
            'terms.accepted' => 'Kullanım şartlarını kabul etmelisiniz.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Referral kodu olan kullanıcıyı bul
            $referrer = null;
            if ($request->filled('referral_code')) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
            }

            // Kullanıcı oluştur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'country' => $request->country,
                'currency' => $request->currency,
                'referral_code' => User::generateReferralCode(),
                'referred_by' => $referrer?->id,
            ]);

            // Cüzdan otomatik olarak User model observer'ı tarafından oluşturulur
            
            // 500 TL Hoşgeldin Bonusu ver (gerçek para olarak)
            if ($user->wallet) {
                $user->wallet->balance += 500;
                $user->wallet->save();
                
                // Transaction kaydı oluştur
                $user->wallet->transactions()->create([
                    'user_id' => $user->id,
                    'type' => 'bonus',
                    'amount' => 500,
                    'balance_before' => 0,
                    'balance_after' => 500,
                    'currency' => 'TRY',
                    'description' => 'Hoşgeldin Bonusu - 500 TL',
                    'reference_type' => 'welcome_bonus',
                    'reference_id' => $user->id,
                    'status' => 'completed'
                ]);
            }

            // Email doğrulama maili gönder (opsiyonel)
            // $user->sendEmailVerificationNotification();

            DB::commit();

            // Kullanıcıyı giriş yap
            auth()->login($user);

            return redirect('/')->with('success', 'Hesabınız başarıyla oluşturuldu! 500 TL hoşgeldin bonusu hesabınıza tanımlandı. İyi şanslar!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Bir hata oluştu. Lütfen tekrar deneyin.')
                ->withInput();
        }
    }
}