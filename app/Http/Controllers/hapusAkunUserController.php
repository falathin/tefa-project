<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class hapusAkunUserController extends Controller
{
    public function index()
    {
        if (! Gate::allows('isAdminOrEngineer')) {
            abort(404, 'not found');
        }
        return view('auth.hapusAkunUser', ['users' => User::latest()->get()]);
    }

    public function hapusAkun(Request $request)
    {
        // @foreach ($users->sortBy('id') as $user)
        // @if (! Gate::allows('isEngineer'))
        //     @if ($user->level == 'engineer')
        //         @continue
        //     @endif
        // @endif
        
        $data = $request->validate([
            'id' => 'required',
        ]);
        $hapusAkun = User::find($data['id']);
        

        if(! Gate::allows('isEngineer')) {
            if ($hapusAkun->level == 'engineer') {
                return back()->with('status', 'super akun tidak dapat dihapus');
            }
        }
        
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
