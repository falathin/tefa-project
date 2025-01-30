<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($hapusAkun == null) {
            return back()->with('status', 'id tidak ditemukan!');
        }
        if ($hapusAkun->jurusan != Auth::user()->jurusan) {
            return back()->with('status', 'akun jurusan tidak sama!');
        }

        if(! Gate::allows('isEngineer')) {
            if ($hapusAkun->level == 'engineer') {
                return back()->with('status', 'super akun tidak dapat dihapus');
            }
        }
        
        if ($hapusAkun) {
            $hapusAkun->delete();
            return back()->with('status', 'hapus akun berhasil');
        } else {
            // Handle jika data tidak ditemukan
            return back()->with('status', 'id akun tidak ditemukan!'); 
        }
    }
}
