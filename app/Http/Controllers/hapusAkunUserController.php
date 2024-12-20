<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class hapusAkunUserController extends Controller
{
    public function index()
    {
        if (! Gate::allows('isEngineer')) {
            abort(404, 'not found');
        }
        return view('auth.hapusAkunUser', ['users' => User::latest()->get()]);
    }

    public function hapusAkun(Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
        ]);
        $hapusAkun = User::find($data['id']);
        
        if ($hapusAkun) {
            $hapusAkun->delete();
            $hapusAkun->delete();
            return back()->with('status', 'hapus akun berhasil');
        } else {
            // Handle jika data tidak ditemukan
            return back()->with('status', 'id akun tidak ditemukan!'); 
        }
    }
}
