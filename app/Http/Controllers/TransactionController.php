<?php

namespace App\Http\Controllers;

use App\Models\SparepartTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Mendapatkan semua transaksi sparepart
        $transactions = SparepartTransaction::with('sparepart')->get();
        return view('transactions.index', compact('transactions')); // Kirim data transaksi ke view
    }

    public function create()
    {
        return view('transactions.create'); // Halaman form untuk membuat transaksi baru
    }

    public function store(Request $request)
    {
        // Validasi data transaksi
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id_sparepart',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|string',
        ]);

        // Menyimpan data transaksi
        SparepartTransaction::create($request->all());

        // Redirect ke halaman index setelah berhasil
        return redirect()->route('sparepart.transactions.index');
    }

    public function show($id)
    {
        // Menampilkan detail transaksi berdasarkan ID
        $transaction = SparepartTransaction::findOrFail($id); // Menangani jika data tidak ditemukan
        return view('transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        // Menampilkan halaman form untuk mengedit transaksi
        $transaction = SparepartTransaction::findOrFail($id);
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data transaksi
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id_sparepart',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|string',
        ]);

        // Update transaksi
        $transaction = SparepartTransaction::findOrFail($id);
        $transaction->update($request->all());

        // Redirect ke halaman index setelah berhasil
        return redirect()->route('sparepart.transactions.index');
    }

    public function destroy($id)
    {
        // Menghapus transaksi
        $transaction = SparepartTransaction::findOrFail($id);
        $transaction->delete();

        // Redirect ke halaman index setelah berhasil
        return redirect()->route('sparepart.transactions.index');
    }
}