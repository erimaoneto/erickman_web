<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'keuangan') {
                return redirect()->route('admin.finance.index');
            }
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Honeypot check: if bot fills the trap field, reject immediately
        if ($request->filled('_hp_security_check')) {
            return back()->withErrors([
                'email' => 'Permintaan tidak dapat diproses.',
            ])->onlyInput('email');
        }

        // Throttle key based on email and IP
        $throttleKey = Str::transliterate(Str::lower($request->input('email', 'guest')).'|'.$request->ip());

        // Max 5 attempts per minute
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$seconds} detik demi keamanan.",
            ])->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            if (Auth::user()->role === 'keuangan') {
                return redirect()->route('admin.finance.index')->with('success', 'Selamat datang di Modul Keuangan Erickman!');
            }

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali di Admin Panel Erickman!');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
