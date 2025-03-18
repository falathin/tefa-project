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
        if (! Gate::allows('isAdmin')) {
            abort(403, 'Waduh, bukan Admin!');
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
        $request->validate(
            [
                'username'       => ['required', 'string', 'max:255'],
                'email'          => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'level'          => ['required'],
                'jurusan'        => ['required'],
                'phone_number'   => ['required', 'regex:/^08[0-9\-\s()]*$/'],
                'new_password'   => ['required', 'confirmed'],
            ],
            [
                'username.required'     => 'Nama pengguna wajib diisi.',
                'username.string'       => 'Nama pengguna harus berupa teks.',
                'username.max'          => 'Nama pengguna tidak boleh lebih dari 255 karakter.',
                'email.required'        => 'Email wajib diisi.',
                'email.string'          => 'Email harus berupa teks.',
                'email.email'           => 'Format email tidak valid.',
                'email.max'             => 'Email tidak boleh lebih dari 255 karakter.',
                'email.unique'          => 'Email sudah terdaftar.',
                'level.required'        => 'Level pengguna wajib dipilih.',
                'jurusan.required'      => 'Jurusan wajib dipilih.',
                'phone_number.required' => 'Nomor telepon wajib diisi.',
                'phone_number.regex'    => 'Nomor telepon harus diawali dengan "08" dan hanya mengandung angka, spasi, tanda kurung, atau strip.',
                'new_password.required' => 'Kata sandi wajib diisi.',
                'new_password.confirmed'=> 'Konfirmasi kata sandi tidak sesuai.',
            ]
        );

        // Membuat pengguna baru
        User::create([
            'name'         => $request->username,
            'email'        => $request->email,
            'jurusan'      => $request->jurusan,
            'level'        => $request->level,
            'password'     => Hash::make($request->new_password),
            'phone_number' => $request->phone_number,
        ]);

        return redirect(route('dashboard'))
                ->with('statusBerhasil', 'Daftar akun ' . $request->username . ' berhasil!');
    }
}