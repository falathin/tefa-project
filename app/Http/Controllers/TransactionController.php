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
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $search = $request->input('search');
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
            'purchase_price' => 'required|array',
            'purchase_price.*' => 'required|numeric|min:0',  // Validasi harga beli dari form
            // 'jurusan' => 'required',
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
            $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();

            $purchase_price = $request->purchase_price[$index];

            $userId = Auth::user()->jurusan;
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
                        'jurusan' => $userId,
                        'quantity' => $request->quantity[$index],
                        'purchase_price' => $purchase_price,
                        'total_price' => $sparepart->harga_jual * $request->quantity[$index],  // Gunakan harga jual untuk total harga
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
                    'jurusan' => $userId,
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
        $sparepartsTransaction = SparepartTransaction::find($id);
        if (! Gate::allows('isSameJurusan', [$sparepartsTransaction])) {
            abort(403, 'data tidak ditemukan!!');
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
        $sparepartsTransaction = SparepartTransaction::find($id);
        if (! Gate::allows('isSameJurusan', [$sparepartsTransaction])) {
            abort(403, 'data tidak ditemukan!!');
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
            'purchase_price.required_if' => 'Harga pembelian harus diisi untuk transaksi pembelian.',
            'purchase_price.numeric' => 'Harga pembelian harus berupa angka.',
            'purchase_price.min' => 'Harga pembelian tidak boleh kurang dari 0.',
        ]);

        $transaction = SparepartTransaction::findOrFail($id);
        $total_profit = 0;

        foreach ($request->sparepart_id as $index => $sparepart_id) {
            $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();

            $previous_quantity = $transaction->quantity ?? 0;

            if ($previous_quantity > $request->quantity[$index]) {
                $difference = $previous_quantity - $request->quantity[$index];

                $sparepart->increment('jumlah', $difference);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => $difference,
                    'action' => 'add',
                ]);
            } elseif ($previous_quantity < $request->quantity[$index]) {
                $difference = $request->quantity[$index] - $previous_quantity;

                $sparepart->decrement('jumlah', $difference);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => -$difference,
                    'action' => 'subtract',
                ]);
            }

            if ($request->transaction_type == 'sale') {
                $profit_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
                $total_profit += $profit_per_sparepart * $request->quantity[$index];

                $transaction->purchase_price = $sparepart->harga_beli;
                $transaction->total_price = $sparepart->harga_jual * $request->quantity[$index];
            } elseif ($request->transaction_type == 'purchase') {
                $purchase_price = $request->purchase_price;

                $transaction->purchase_price = $purchase_price;
                $transaction->total_price = $purchase_price * $request->quantity[$index];

                $sparepart->increment('jumlah', $request->quantity[$index]);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => $request->quantity[$index],
                    'action' => 'add', // Tindakan penambahan untuk pembelian
                ]);
            }

            $transaction->sparepart_id = $sparepart_id;
            $transaction->quantity = $request->quantity[$index];
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