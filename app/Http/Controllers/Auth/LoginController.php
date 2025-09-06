<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email', 'remember'));
        }

        // Giriş bilgilerini kontrol et
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Kullanıcı durumunu kontrol et
            $user = Auth::user();
            
            if ($user->status === 'banned') {
                Auth::logout();
                return redirect()->back()
                    ->with('error', 'Hesabınız yasaklanmıştır. Destek ile iletişime geçiniz.')
                    ->withInput($request->only('email'));
            }
            
            if ($user->status === 'suspended') {
                Auth::logout();
                return redirect()->back()
                    ->with('error', 'Hesabınız askıya alınmıştır. Destek ile iletişime geçiniz.')
                    ->withInput($request->only('email'));
            }

            // Session yenileme
            $request->session()->regenerate();

            // Intended URL kontrolü
            $intended = redirect()->intended()->getTargetUrl();
            
            // Eğer API endpoint'ine yönlendiriyorsa, ana sayfaya yönlendir
            if (str_contains($intended, '/api/') || str_contains($intended, 'api.')) {
                return redirect('/')->with('success', 'Başarıyla giriş yaptınız!');
            }
            
            return redirect()->intended('/')->with('success', 'Başarıyla giriş yaptınız!');
        }

        return redirect()->back()
            ->with('error', 'E-posta veya şifre hatalı.')
            ->withInput($request->only('email', 'remember'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Başarıyla çıkış yaptınız.');
    }
}