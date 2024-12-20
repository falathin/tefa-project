<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('auth.edit');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $data = User::find(Auth::user()->id);
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email']
        ]);

        $data->name = $request['name'];
        $data->email = $request['email'];

        $data->save();

        return back()->with('statusBerhasil', 'Ubah profil berhasil!');
    }

    function updatePassword (Request $request) {
        $validated = $request->validate( [
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8'],
        ]);

        // $request->user()->update([
        //     'password' => Hash::make($validated['new_password']),
        // ]);
        // Auth::user()->password = Hash::make($validated['new_password']);

        $user = $request->user();
        if(!Hash::check($request->current_password, Auth::user()->getAuthPassword())) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai!']);
        }

        // Update password dengan hash baru
        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('statusBerhasil', 'Ubah password berhasil! ');
    }
}
