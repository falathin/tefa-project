<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmergencyPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

// const EMERGENCY_PASSWORD = 'SUPER4060$1000;VS{StallerJade};';

class ForgotPasswordController extends Controller
{

    public function index()
    {
        if (! Gate::allows('isEngineer')) {
            abort(404, 'not found');
        }
        return view('superPrograms.gantiEmergencyPassword', ['data' => EmergencyPassword::find(1)]);
    }

    public function ganti(Request $request)
    {
        if (! Gate::allows('isEngineer')) {
            abort(404, 'not found');
        }

        $data = $request->validate([
            'new_password' => 'required',
        ]);
    

        $emergency_password = EmergencyPassword::find(1);
        $emergency_password->emergency_password = $data['new_password'];
        $emergency_password->save();

        return redirect(route('dashboard'))->with('status', 'ganti emergency_passwword berhasil menjadi ' . $emergency_password->emergency_password);
    }

    public function showResetForm()
    {
        return view('guest.lupa-password');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'emergency_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'email.email' => 'waduh email'
        ]);

        $user = User::where('email', $request->email)->first();
        $emergency_password = EmergencyPassword::find(1);

        if ($request->emergency_password == $emergency_password->emergency_password) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('login')->with('status', 'Password berhasil di reset!')->withInput();
        }

        return back()->withErrors('Emergency password tidak valid.')->onlyInput('email');
    }
}
