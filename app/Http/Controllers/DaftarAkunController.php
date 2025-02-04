<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class DaftarAkunController extends Controller
{
    public function create(): View
    {
        if (! Gate::allows('isAdminOrEngineer')) {
            abort(403, 'waduh, bukan Admin!');
        }
        return view('auth.daftar-akun');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    public function storeByAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'level' => ['required'],
            'jurusan' => ['required'],
            'phone_number' => ['required'],
            'new_password' => ['required', 'confirmed'],
        ]);

        // if (! $validated) {
        //     return back()->withErrors(['statusError' => 'Input tidak sesuai!']);
        // }

         // Membuat pengguna baru
         User::create([
            'name' => $request->username,
            'email' => $request->email,
            'jurusan' => $request->jurusan,
            'level' => $request->level,
            'password' => Hash::make($request->new_password),
            'phone_number' => $request->phone_number,
        ]);

        return redirect(route('dashboard'))->with('statusBerhasil', 'Daftar akun ' . $request->username . ' berhasil!');
    }
}
