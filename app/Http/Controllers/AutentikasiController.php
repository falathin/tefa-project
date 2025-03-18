<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AutentikasiController extends Controller
{
    /**
     * Tampilkan tampilan login.
     */
    public function create(): View
    {
        return view('guest.login');
    }

    /**
     * Menangani request autentikasi.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input login
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Gunakan IP address sebagai key untuk rate limiter
        $key = 'login.attempt:' . $request->ip();

        // Jika sudah terlalu banyak percobaan, tampilkan pesan error dan tetap di halaman login
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'throttle' => "Terlalu banyak percobaan. Silahkan tunggu <span id='countdown'>{$seconds}</span> detik sebelum mencoba lagi.",
            ])->withInput($request->only('email'));
        }
        
        // Jika autentikasi berhasil, bersihkan counter dan regenerasi session
        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            return redirect(route('dashboard'));
        }

        // Jika login gagal, naikkan counter (masa decay 60 detik)
        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'Input tidak sesuai!',
        ])->withInput($request->only('email'));
    }

    public function confirmLogout()
    {
        return back()->with('confirmLogout', 'Konfirmasi logout?');
    }

    /**
     * Logout sesi yang terautentikasi.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }
}