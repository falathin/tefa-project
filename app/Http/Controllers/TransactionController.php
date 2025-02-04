<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\SparepartHistory;
use App\Models\SparepartTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if (Gate::allows('isBendahara')) {
            $transactions = SparepartTransaction::with('sparepart')
                ->when($search, function ($query, $search) {
                    return $query->whereHas('sparepart', function ($query) use ($search) {
                        $query->where('nama_sparepart', 'like', "%{$search}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            return view('transactions.index', compact('transactions'));
        } else {
            $jurusan = Auth::user()->jurusan;
            $transactions = SparepartTransaction::with('sparepart')
                ->when($search, function ($query, $search) {
                    return $query->whereHas('sparepart', function ($query) use ($search) {
                        $query->where('nama_sparepart', 'like', "%{$search}%");
                    });
                })
                ->where('jurusan', 'like', $jurusan)
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            return view('transactions.index', compact('transactions'));
        }
    }

    public function create()
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
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
            'purchase_price' => $request->transaction_type == 'purchase' ? 'required|array' : 'nullable|array',  // Only required for purchases
            'jurusan' => 'required',
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
            'purchase_price.required' => 'Harga beli harus diisi.',
            'purchase_price.*.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.*.min' => 'Harga beli tidak boleh kurang dari 0.',
        ]);

        $total_profit = 0;

        foreach ($request->sparepart_id as $index => $sparepart_id) {
            // Ensure that the index exists in the quantity and purchase_price arrays
            if (!isset($request->quantity[$index]) || !isset($request->purchase_price[$index])) {
                return redirect()->back()->withErrors(['quantity' => 'Jumlah atau harga beli tidak valid.']);
            }

            $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();

            $purchase_price = $request->purchase_price[$index];

            if ($request->transaction_type == 'sale') {
                if ($sparepart->jumlah >= $request->quantity[$index]) {
                    $sparepart->decrement('jumlah', $request->quantity[$index]);

                    SparepartHistory::create([
                        'sparepart_id' => $sparepart_id,
                        'jumlah_changed' => -$request->quantity[$index],
                        'action' => 'subtract',
                    ]);

                    $profit_per_sparepart = $sparepart->harga_jual - $purchase_price;
                    $total_profit += $profit_per_sparepart * $request->quantity[$index];

                    SparepartTransaction::create([
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $request->quantity[$index],
                        'purchase_price' => $purchase_price,
                        'total_price' => $sparepart->harga_jual * $request->quantity[$index],  // Gunakan harga jual untuk total harga
                        'transaction_date' => now(),
                        'transaction_type' => 'sale',
                        'jurusan' => $request->jurusan
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
                    'purchase_price' => $purchase_price,  // Gunakan harga beli dari form
                    'total_price' => $purchase_price * $request->quantity[$index],  // Gunakan harga beli untuk total harga
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
        $sparepart = SparepartTransaction::find($id);
        if (! Gate::allows('isSameJurusan', [$sparepart])) {
            abort(403, 'Data tidak ditemukan!');
        }
        $transaction = SparepartTransaction::with('sparepart')->findOrFail($id);

        $subtotal = $transaction->sparepart->harga_jual * $transaction->quantity;

        $totalPrice = $subtotal;

        $change = 0;

        if ($transaction->transaction_type == 'sale') {
            $change = $transaction->purchase_price - $subtotal; // Kembalian = uang bayar - subtotal
        }

        return view('transactions.show', compact('transaction', 'subtotal', 'totalPrice', 'change'));
    }

    public function edit($id)
    {
        $sparepart = SparepartTransaction::find($id);
        if (! Gate::allows('isSameJurusan', [$sparepart])) {
            abort(403, 'Data tidak ditemukan!');
        }

        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
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
            'purchase_price' => 'required_if:transaction_type,purchase|numeric|min:0', // added validation for purchase_price when transaction type is 'purchase'
        ], [
            'sparepart_id.required' => 'Kolom ID sparepart harus diisi.',
            'sparepart_id.array' => 'ID sparepart harus dalam bentuk array.',
            'sparepart_id.*.exists' => 'Beberapa sparepart tidak ditemukan.',
            'quantity.required' => 'Kolom jumlah harus diisi.',
            'quantity.array' => 'Jumlah harus dalam bentuk array.',
            'quantity.*.required' => 'Jumlah harus diisi.',
            'quantity.*.numeric' => 'Jumlah harus berupa angka.',
            'quantity.*.min' => 'Jumlah minimal adalah 1.',
            'transaction_type.required' => 'Jenis transaksi harus dipilih.',
            'transaction_type.in' => 'Jenis transaksi tidak valid.',
            'purchase_price.required_if' => 'Harga beli harus diisi untuk transaksi pembelian.',
            'purchase_price.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.min' => 'Harga beli tidak boleh kurang dari 0.',
        ]);

        $transaction = SparepartTransaction::findOrFail($id);
        $transaction->transaction_type = $request->transaction_type;
        $transaction->purchase_price = $request->purchase_price;

        // Process each sparepart transaction
        foreach ($request->sparepart_id as $index => $sparepart_id) {
            $sparepart = Sparepart::findOrFail($sparepart_id);

            if ($request->transaction_type == 'sale') {
                $sparepart->decrement('jumlah', $request->quantity[$index]);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => -$request->quantity[$index],
                    'action' => 'subtract',
                ]);

                $transaction->spareparts()->updateExistingPivot($sparepart_id, ['quantity' => $request->quantity[$index]]);
            } else {
                $sparepart->increment('jumlah', $request->quantity[$index]);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => $request->quantity[$index],
                    'action' => 'add',
                ]);

                $transaction->spareparts()->updateExistingPivot($sparepart_id, ['quantity' => $request->quantity[$index]]);
            }
        }

        $transaction->save();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = SparepartTransaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}