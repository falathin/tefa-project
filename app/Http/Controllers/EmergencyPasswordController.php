<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmergencyPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class EmergencyPasswordController extends Controller
{
    public function index()
    {
        if (! Gate::allows('isEngineer')) {
            abort(404, 'not foundt');
        }
        return view('auth.gantiEmergencyPassword', ['data' => EmergencyPassword::find(1)]);
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

        return redirect(route('dashboard'))->with('statusBerhasil', 'Ganti emergency_passwword berhasil!');
    }
}
