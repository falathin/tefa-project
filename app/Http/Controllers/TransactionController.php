<?php

namespace App\Http\Controllers;

use App\Models\SparepartTransaction;
use App\Models\Sparepart;
use App\Models\SparepartHistory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $transactions = SparepartTransaction::with('sparepart')
            ->when($search, function ($query, $search) {
                return $query->whereHas('sparepart', function ($query) use ($search) {
                    $query->where('nama_sparepart', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $spareparts = Sparepart::all();
        $transactions = SparepartTransaction::all();
        return view('transactions.create', compact('spareparts', 'transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:sale,purchase',
            'sparepart_id' => 'required|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
        ], [
            'transaction_type.required' => 'Jenis transaksi harus dipilih.',
            'transaction_type.in' => 'Jenis transaksi tidak valid.',
            'sparepart_id.required' => 'Kolom ID sparepart harus diisi.',
            'sparepart_id.array' => 'ID sparepart harus dalam bentuk array.',
            'sparepart_id.*.exists' => 'Beberapa sparepart tidak ditemukan.',
            'quantity.required' => 'Kolom jumlah harus diisi.',
            'quantity.array' => 'Jumlah harus dalam bentuk array.',
            'quantity.*.required' => 'Jumlah harus diisi.',
            'quantity.*.numeric' => 'Jumlah harus berupa angka.',
            'quantity.*.min' => 'Jumlah minimal adalah 1.',
        ]);

        $total_profit = 0;

        foreach ($request->sparepart_id as $index => $sparepart_id) {
            $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();

            if ($request->transaction_type == 'sale') {
                if ($sparepart->jumlah >= $request->quantity[$index]) {
                    $sparepart->decrement('jumlah', $request->quantity[$index]);

                    SparepartHistory::create([
                        'sparepart_id' => $sparepart_id,
                        'jumlah_changed' => -$request->quantity[$index],
                        'action' => 'subtract',
                    ]);

                    $profit_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
                    $total_profit += $profit_per_sparepart * $request->quantity[$index];

                    SparepartTransaction::create([
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $request->quantity[$index],
                        'purchase_price' => $sparepart->harga_jual,
                        'total_price' => $sparepart->harga_jual * $request->quantity[$index],
                        'transaction_date' => now(),
                        'transaction_type' => 'sale',
                    ]);
                } else {
                    return redirect()->back()->withErrors(['sparepart_id' => 'Stok sparepart tidak cukup untuk salah satu item.']);
                }
            } elseif ($request->transaction_type == 'purchase') {
                $sparepart->increment('jumlah', $request->quantity[$index]);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => $request->quantity[$index],
                    'action' => 'add',
                ]);

                SparepartTransaction::create([
                    'sparepart_id' => $sparepart_id,
                    'quantity' => $request->quantity[$index],
                    'purchase_price' => $sparepart->harga_jual,
                    'total_price' => $sparepart->harga_beli * $request->quantity[$index],
                    'transaction_date' => now(),
                    'transaction_type' => 'purchase',
                ]);
            }
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi sparepart berhasil disimpan!');
    }
    public function show($id)
    {
        $transaction = SparepartTransaction::with('sparepart')->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = SparepartTransaction::findOrFail($id);
        $spareparts = Sparepart::all();
        $transactionDate = \Carbon\Carbon::parse($transaction->transaction_date);
        $formattedDate = $transactionDate->toDateString();
        $transactionDetails = $transaction->sparepart ?? collect();
        return view('transactions.edit', compact('transaction', 'spareparts', 'transactionDetails', 'formattedDate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sparepart_id' => 'required|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'transaction_type' => 'required|in:sale,purchase',
        ], [
            'sparepart_id.required' => 'Kolom ID sparepart harus diisi.',
            'sparepart_id.array' => 'ID sparepart harus dalam bentuk array.',
            'sparepart_id.*.exists' => 'Beberapa sparepart tidak ditemukan.',
            'quantity.required' => 'Kolom jumlah harus diisi.',
            'quantity.array' => 'Jumlah harus dalam bentuk array.',
            'quantity.*.required' => 'Jumlah harus diisi.',
            'quantity.*.numeric' => 'Jumlah harus berupa angka.',
            'quantity.*.min' => 'Jumlah minimal adalah 1.',
        ]);

        $transaction = SparepartTransaction::findOrFail($id);
        $total_profit = 0;

        foreach ($request->sparepart_id as $index => $sparepart_id) {
            $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();

            $previous_quantity = $transaction->quantity ?? 0;

            if ($previous_quantity > $request->quantity[$index]) {
                $difference = $previous_quantity - $request->quantity[$index];

                // Mengembalikan stok yang telah berkurang
                $sparepart->increment('jumlah', $difference);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => $difference,
                    'action' => 'add', // Tindakan pengembalian
                ]);
            } elseif ($previous_quantity < $request->quantity[$index]) {
                // Jika jumlah bertambah, lakukan pengurangan
                $difference = $request->quantity[$index] - $previous_quantity;

                // Mengurangi stok sparepart
                $sparepart->decrement('jumlah', $difference);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => -$difference,
                    'action' => 'subtract',
                ]);
            }

            $profit_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
            $total_profit += $profit_per_sparepart * $request->quantity[$index];

            $transaction->sparepart_id = $sparepart_id;
            $transaction->quantity = $request->quantity[$index];
            $transaction->purchase_price = $sparepart->harga_jual;
            $transaction->total_price = $sparepart->harga_jual * $request->quantity[$index];
            $transaction->transaction_date = now();
            $transaction->transaction_type = $request->input('transaction_type');
            $transaction->save();
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi sparepart berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = SparepartTransaction::findOrFail($id);

        foreach ($transaction->spareparts as $sparepart) {
            $quantity = $transaction->quantity;

            $sparepart->increment('jumlah', $quantity);
            SparepartHistory::create([
                'sparepart_id' => $sparepart->id_sparepart,
                'jumlah_changed' => $quantity,
                'action' => 'add',
            ]);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi sparepart berhasil dihapus!');
    }
}