<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\SparepartHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $jurusan = Auth::user()->jurusan;
        $search = $request->input('search');
    
        $query = Sparepart::query();
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_sparepart', 'like', "%{$search}%")
                  ->orWhere('spek', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
    
        if (Auth::user()->jurusan != 'General') {
            $query->where('jurusan', 'like', $jurusan);
        }
    
        $spareparts = $query->orderBy('created_at', 'desc')->paginate(4);
    
        return view('sparepart.index', compact('spareparts'));
    }
    
    public function create()
    {
        // Admin & kasir
        if (! Gate::allows('isAdmin') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        return view('sparepart.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'spek' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
            'deskripsi' => 'nullable|string',
            'jurusan' => 'required'
        ]);

        $keuntungan = $request->harga_jual - $request->harga_beli;

        $sparepart = Sparepart::create([
            'nama_sparepart' => $request->nama_sparepart,
            'jumlah' => $request->jumlah,
            'spek' => $request->spek,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'keuntungan' => $keuntungan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'deskripsi' => $request->deskripsi,
            'jurusan' => $request->jurusan
        ]);

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'spek' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update([
            'nama_sparepart' => $request->nama_sparepart,
            'jumlah' => $request->jumlah,
            'spek' => $request->spek,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'keuntungan' => $request->harga_jual - $request->harga_beli,
            'tanggal_masuk' => $request->tanggal_masuk,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function show($sparepart_id)
    {
        $sparepart = Sparepart::find($sparepart_id);
        if (! Gate::allows('isSameJurusan', [$sparepart])) {
            abort(403, 'data tidak ditemukan!!');
        }
        // Admin & kasir
        // if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
        //     abort(403, 'Butuh level Admin & Kasir');
        // }
        $sparepart = Sparepart::findOrFail($sparepart_id);
        return view('sparepart.show', compact('sparepart'));
    }

    public function edit($id)
    {
        $sparepart = Sparepart::find($id);
        if (! Gate::allows('isSameJurusan', [$sparepart])) {
            abort(403, 'data tidak ditemukan!!');
        }

        // Admin & kasir
        if (! Gate::allows('isAdmin') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $sparepart = Sparepart::findOrFail($id);
        return view('sparepart.edit', compact('sparepart'));
    }

    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil dihapus.');
    }
   
    public function history($id, Request $request) {
        $data = SparepartHistory::where('sparepart_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $sparepart = Sparepart::findOrFail($id);
            
        return view('sparepart.history', compact('data', 'sparepart'));
    }    
}