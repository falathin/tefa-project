<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $spareparts = Sparepart::when($search, function($query, $search) {
            return $query->where('nama_sparepart', 'like', '%' . $search . '%');
        })->paginate(4); // Adjust the number to control items per page

        return view('sparepart.index', compact('spareparts'));
    }

    public function create()
    {
        return view('sparepart.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        // Calculate keuntungan
        $keuntungan = $request->harga_jual - $request->harga_beli;

        // Include keuntungan in the data being saved
        Sparepart::create([
            'nama_sparepart' => $request->nama_sparepart,
            'jumlah' => $request->jumlah,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'keuntungan' => $keuntungan, // Pass calculated keuntungan
            'tanggal_masuk' => $request->tanggal_masuk,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil ditambahkan.');
    }

        public function show($sparepart_id)
    {
        $sparepart = Sparepart::findOrFail($sparepart_id);
        return view('sparepart.show', compact('sparepart'));
    }


    public function edit($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        return view('sparepart.edit', compact('sparepart'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update($request->all());
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil dihapus.');
    }
}