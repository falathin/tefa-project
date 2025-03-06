<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\SparepartHistory;
use App\Models\SparepartTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Exports\SparepartTransactionExport;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        if (Gate::allows('isBendahara')) {
            // $transactions = SparepartTransaction::with('sparepart')
            $transactions = Transaction::query()
                ->when($search, function ($query, $search) {
                    return $query->whereHas('sparepart', function ($query) use ($search) {
                        $query->where('nama_sparepart', 'like', "%{$search}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            return view('transactions.index', compact('transactions'));
        } else {
            $transactions = Transaction::query()->where('jurusan', 'like', Auth::user()->jurusan);
            $transactions = Transaction::query()
                ->when($search, function ($query, $search) {
                    return $query->whereHas('sparepart', function ($query) use ($search) {
                        $query->where('nama_sparepart', 'like', "%{$search}%");
                    });
                })
                ->where('jurusan', 'like', Auth::user()->jurusan)
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
            // 'purchase_price' => $request->transaction_type == 'purchase' ? 'required|array' : 'nullable|array',
            'purchase_price.*' => 'numeric|min:0',
            'total_price' => 'required',
            'jurusan' => 'required',
        ], [
            'transaction_type.required' => 'Jenis transaksi harus dipilih.',
            'transaction_type.in' => 'Jenis transaksi tidak valid.',
            'sparepart_id.required' => 'Kolom ID sparepart harus diisi.',
            // 'sparepart_id.array' => 'ID sparepart harus dalam bentuk array.',
            'sparepart_id.*.exists' => 'Beberapa sparepart tidak ditemukan.',
            'quantity.required' => 'Kolom jumlah harus diisi.',
            'quantity.array' => 'Jumlah harus dalam bentuk array.',
            'quantity.*.required' => 'Jumlah harus diisi.',
            'quantity.*.numeric' => 'Jumlah harus berupa angka.',
            'quantity.*.min' => 'Jumlah minimal adalah 1.',
            'purchase_price.required' => 'Harga beli harus diisi untuk pembelian.',
            'purchase_price.*.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.*.min' => 'Harga beli tidak boleh kurang dari 0.',
        ]);

        $transaction = Transaction::create([
            'purchase_price' => $request->purchase_price,
            'total_price' => $request->total_price,
            'transaction_date' => $request->transaction_date,
            'transaction_type' => $request->transaction_type,
            'jurusan' => $request->jurusan
        ]);

        foreach ($request->sparepart_id as $index => $sparepart_id) {
            if (!isset($request->quantity[$index])) {
                return redirect()->back()->withErrors(['quantity' => 'Jumlah tidak valid untuk sparepart tertentu.']);
            }

            $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();
            $quantity = $request->quantity[$index];

            if ($request->transaction_type == 'sale') {
                if ($sparepart->jumlah >= $quantity) {
                    $sparepart->decrement('jumlah', $quantity);

                    SparepartHistory::create([
                        'sparepart_id' => $sparepart_id,
                        'jumlah_changed' => -$quantity,
                        'action' => 'subtract',
                    ]);

                    SparepartTransaction::create([
                        'transaction_id' => $transaction->id,
                        'sparepart_id' => $sparepart_id,
                        'transaction_id' => $transaction->id,
                        'quantity' => $quantity,
                    ]);
                } else {
                    return redirect()->back()->withErrors(['sparepart_id' => 'Stok sparepart tidak cukup untuk salah satu item.']);
                }
            } elseif ($request->transaction_type == 'purchase') {
                if (!isset($request->purchase_price[$index])) {
                    return redirect()->back()->withErrors(['purchase_price' => 'Harga beli tidak valid.']);
                }

                $purchase_price = $request->purchase_price[$index];

                $sparepart->increment('jumlah', $quantity);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => $quantity,
                    'action' => 'add',
                ]);

                SparepartTransaction::create([
                    'transaction_id' => $transaction->id,
                    'sparepart_id' => $sparepart_id,
                    'quantity' => $quantity,
                ]);
            }
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi sparepart berhasil disimpan! Total harga: Rp' . number_format($request->total_price, 0, ',', '.'));
    }

    public function show($id)
    {
        // Ambil data Transaction berdasarkan ID, bukan SparepartTransaction
        $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);


        // Cek izin jurusan
        if (!Gate::allows('isSameJurusan', [$transaction])) {
            abort(403, 'Data tidak ditemukan!');
        }

        // Hitung total harga dan kembalian
        // dd($transaction->sparepart->harga_jual);

        $totalPrice = $transaction->total_price;
        $purchasePrice = $transaction->purchase_price;
        $change = $purchasePrice - $totalPrice;

        return view('transactions.show', compact('transaction', 'totalPrice', 'change'));
    }

    // public function edit($id)
    // {
    //     // Ambil data Transaction berdasarkan ID, bukan SparepartTransaction
    //     $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);

    //     if (! Gate::allows('isSameJurusan', [$transaction])) {
    //         abort(403, 'Data tidak ditemukan!');
    //     }

    //     if (! Gate::allows('isAdminOrEngineer')) {
    //         abort(403, 'Butuh level Admin');
    //     }
    //     // $transaction = SparepartTransaction::findOrFail($id);
    //     $spareparts = Sparepart::all();
    //     dd($transaction->transactionSpareparts);
    //     $transactionDate = \Carbon\Carbon::parse($transaction->transaction_date);
    //     $formattedDate = $transactionDate->toDateString();
    //     $transactionDetails = $transaction->sparepart ?? collect();
    //     return view('transactions.edit', compact('transaction', 'spareparts', 'transactionDetails', 'formattedDate'));
    // }

    public function edit($id)
    {
        // Ambil data Transaction berdasarkan ID, bukan SparepartTransaction
        $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);

        if (! Gate::allows('isSameJurusan', [$transaction])) {
            abort(403, 'Data tidak ditemukan!');
        }

        if (! Gate::allows('isAdminOrEngineer')) {
            abort(403, 'Butuh level Admin');
        }

        $spareparts = Sparepart::all();
        $transactionDate = \Carbon\Carbon::parse($transaction->transaction_date);
        $formattedDate = $transactionDate->toDateString();

        // Hitung subtotal untuk setiap sparepart dalam transaksi
        $transactionDetails = $transaction->transactionSpareparts->map(function ($transactionSparepart) {
            $subtotal = $transactionSparepart->quantity * $transactionSparepart->sparepart->harga_jual;
            return [
                'sparepart_id' => $transactionSparepart->sparepart_id,
                'nama_sparepart' => $transactionSparepart->sparepart->nama_sparepart,
                'harga_jual' => $transactionSparepart->sparepart->harga_jual,
                'quantity' => $transactionSparepart->quantity,
                'subtotal' => $subtotal, // Menambahkan subtotal
            ];
        });

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
            // 'total_price' => 'required|numeric',
            'purchase_price' => 'required_if:transaction_type,purchase|numeric|min:0',
            'transaction_date' => 'required|date',
            // 'jurusan' => 'required',
        ]);

        $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);

        // Revert efek transaksi lama ke stok
        foreach ($transaction->transactionSpareparts as $transactionSparepart) {
            $sparepart = $transactionSparepart->sparepart;
            if ($transaction->transaction_type == 'sale') {
                $sparepart->increment('jumlah', $transactionSparepart->quantity);
            } else {
                $sparepart->decrement('jumlah', $transactionSparepart->quantity);
            }
            $transactionSparepart->delete();
        }

        $totalPrice = 0;
        foreach ($request->sparepart_id as $index => $sparepart_id) {
            $sparepart = Sparepart::findOrFail($sparepart_id);
            $quantity = $request->quantity[$index];

            if ($request->transaction_type == 'sale') {
                if ($sparepart->jumlah < $quantity) {
                    return redirect()->back()->withErrors(['sparepart_id' => 'Stok tidak cukup untuk ' . $sparepart->nama_sparepart]);
                }
                $sparepart->decrement('jumlah', $quantity);
                $totalPrice += $sparepart->harga_jual * $quantity;
            } else {
                $purchasePrice = $request->purchase_price;
                $sparepart->increment('jumlah', $quantity);
                $totalPrice += $purchasePrice * $quantity;
            }

            SparepartTransaction::create([
                'transaction_id' => $transaction->id,
                'sparepart_id' => $sparepart_id,
                'quantity' => $quantity,
            ]);
        }

        $transaction->update([
            'transaction_type' => $request->transaction_type,
            'transaction_date' => $request->transaction_date,
            'purchase_price' => $request->purchase_price,
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }

    public function export()
    {
        return Excel::download(new SparepartTransactionExport, 'sparepart-transactions.xlsx');
    }
}
