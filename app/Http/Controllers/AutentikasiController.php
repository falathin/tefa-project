<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AutentikasiController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('guest.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // $request->authenticate();
        // $request->session()->regenerate();

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Input tidak sesuai!',
        ])->onlyInput('email');

    }

    public function confirmLogout () {
        return back()->with('confirmLogout', 'Konfirmasi logout?');
    }

    /**
     * Logout an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
