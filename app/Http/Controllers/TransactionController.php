<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\SparepartHistory;
use App\Models\Customer;
use App\Models\SparepartTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Exports\SparepartTransactionExport;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isEmpty;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if (Gate::allows('isBendahara')) {
            $transactions = Transaction::query()
                ->when($search, function ($query, $search) {
                    return $query->whereHas('spareparts', function ($query) use ($search) {
                        $query->where('nama_sparepart', 'like', "%{$search}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        } elseif (Gate::allows('isSA')) {
            abort(403, 'Butuh level Admin | Kasir | Bendahara');
        } else {
            $transactions = Transaction::query()
                ->where('jurusan', 'like', Auth::user()->jurusan)
                ->when($search, function ($query, $search) {
                    return $query->whereHas('spareparts', function ($query) use ($search) {
                        $query->where('nama_sparepart', 'like', "%{$search}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        }

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Admin & kasir
        if (! Gate::allows('isAdmin') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $spareparts = Sparepart::all();
        $customers = Customer::all();
        $transactions = SparepartTransaction::all();
        return view('transactions.create', compact('spareparts', 'transactions', 'customers'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'transaction_type' => 'required|in:sale,purchase',
            'sparepart_id' => 'required',
            // 'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'purchase_price.*' => 'numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'required|integer|min:0',
            'payment_method' => 'required|string|max:255',
            'jurusan' => 'required',
        ], [
            'sparepart_id.required' => 'Harap pilih minimal 1 sparepart.',
            'sparepart_id.*.exists' => 'Sparepart yang dipilih tidak valid.',
            'quantity.*.required' => 'Masukkan jumlah untuk setiap sparepart.',
        ]);

        $sparepartIds = explode(',', $request->sparepart_id);

        $total_price = $request->total_price - ($request->total_price * $request->discount / 100);
        // dd($total_price);
        $transaction = Transaction::create([
            'name' => $request->name,
            'purchase_price' => $request->purchase_price,
            'total_price' => $total_price,
            'discount' => $request->discount,
            'payment_method' => $request->payment_method,
            'transaction_date' => $request->transaction_date,
            'transaction_type' => $request->transaction_type,
            'jurusan' => $request->jurusan
        ]);

        foreach ($sparepartIds as $index => $sparepart_id) {
            if (!isset($request->quantity[$index])) {
                return redirect()->back()->withErrors(['quantity' => 'Jumlah tidak valid untuk sparepart tertentu.']);
            }

            $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();
            $quantity = $request->quantity[$index];

            if ($request->transaction_type == 'sale') {
                if ($sparepart->jumlah >= $quantity) {
                    $sparepart->decrement('jumlah', $quantity);
                    SparepartTransaction::create([
                        'transaction_id' => $transaction->id,
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $quantity,
                        'harga_beli' => $sparepart->harga_beli,
                        'harga_jual' => $sparepart->harga_jual,
                        'nama_sparepart' => $sparepart->nama_sparepart,
                        'spek' => $sparepart->spek
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
                SparepartTransaction::create([
                    'transaction_id' => $transaction->id,
                    'sparepart_id' => $sparepart_id,
                    'quantity' => $quantity,
                    'harga_beli' => $sparepart->harga_beli,
                    'harga_jual' => $sparepart->harga_jual,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'spek' => $sparepart->spek
                ]);
            }
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi sparepart berhasil disimpan! Total harga: Rp'
                . number_format($request->total_price * ((100 - $request->discount) / 100), 0, ',', '.')
                . ' (Diskon ' . number_format($request->discount, 0, ',', '.') . '%)');
    }

    public function show($id)
    {
        // Ambil data transaksi beserta sparepart
        $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);

        // Cek izin jurusan
        if (!Gate::allows('isSameJurusan', [$transaction])) {
            abort(403, 'Data tidak ditemukan!');
        }
        if (!Gate::allows('isSameJurusan', [$transaction])) {
            abort(403, 'Data tidak ditemukan!');
        }

        // Hitung total harga & kembalian
        $totalPrice = $transaction->total_price;
        $purchasePrice = $transaction->purchase_price;
        $discount = $transaction->discount;
        $change = $purchasePrice - ($totalPrice - $discount);

        // Hitung subtotal sebelum diskon
        $subtotalBeforeDiscount = $transaction->transactionSpareparts->sum(function ($sparepart) {
            return $sparepart->quantity * $sparepart->harga_jual;
        });

        return view('transactions.show', compact('transaction', 'totalPrice', 'change', 'subtotalBeforeDiscount'));
    }

    public function edit($id)
    {
        $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);

        if (! Gate::allows('isSameJurusan', [$transaction])) {
            abort(403, 'Data tidak ditemukan!');
        }

        if (! Gate::allows('isAdmin')) {
            abort(403, 'Butuh level Admin');
        }

        $spareparts = Sparepart::all();
        $customers = Customer::all(); // Pastikan variabel ini tersedia
        $transactionDate = \Carbon\Carbon::parse($transaction->transaction_date);
        $formattedDate = $transactionDate->toDateString();

        $transactionSparepart = $transaction->transactionSpareparts;

        $transactionDetails = $transaction->transactionSpareparts->map(function ($transactionSparepart) {
            $subtotal = $transactionSparepart->quantity * $transactionSparepart->harga_jual;
            return [
                'sparepart_id' => $transactionSparepart->sparepart_id,
                'nama_sparepart' => $transactionSparepart->nama_sparepart,
                'harga_jual' => $transactionSparepart->harga_jual,
                'quantity' => $transactionSparepart->quantity,
                'subtotal' => $subtotal,
            ];
        });
        $subtotalBeforeDiscount = $transactionDetails->sum('subtotal');

        return view('transactions.edit', compact('transaction', 'spareparts', 'customers', 'transactionDetails', 'formattedDate', 'subtotalBeforeDiscount', 'transactionSparepart'));
    }

    public function restore($id)
    {
        $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);
        if (isEmpty($transaction->spareparts) == true) {
            return redirect()->route('transactions.index')
                ->with('error', 'sparepart telah dihapus sebelumnya!');
        }
        foreach ($transaction->transactionSpareparts as $ts) {
            $sparepart = Sparepart::find($ts->sparepart_id);
            if ($sparepart != null) {
                $sparepart->increment('jumlah', $ts->quantity);
            }
        }
        $transaction->delete();
        return redirect()->route('transactions.index')
            ->with('success', 'restore sparepart berhasil!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'transaction_type' => 'required|in:sale,purchase',
            'sparepart_id' => 'required|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'purchase_price.*' => 'numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'required|string|in:cash,credit,transfer',
            'total_price' => 'required',
        ], [
            'name.required' => 'Nama pelanggan harus diisi.',
            'transaction_type.required' => 'Jenis transaksi harus dipilih.',
            'transaction_type.in' => 'Jenis transaksi tidak valid.',
            'sparepart_id.required' => 'Kolom nama sparepart harus diisi.',
            'sparepart_id.*.exists' => 'Beberapa sparepart tidak ditemukan.',
            'quantity.required' => 'Kolom jumlah harus diisi.',
            'quantity.*.required' => 'Jumlah harus diisi.',
            'quantity.*.numeric' => 'Jumlah harus berupa angka.',
            'quantity.*.min' => 'Jumlah minimal adalah 1.',
            'purchase_price.*.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.*.min' => 'Harga beli tidak boleh kurang dari 0.',
            'discount.numeric' => 'Diskon harus berupa angka.',
            'discount.min' => 'Diskon tidak boleh kurang dari 0.',
            'discount.max' => 'Diskon tidak boleh lebih dari 100.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ]);

        $transaction = Transaction::with('transactionSpareparts.sparepart')->findOrFail($id);
        $oldSpareparts = $transaction->transactionSpareparts->keyBy('sparepart_id');
        $transaction->transactionSpareparts()->delete();
        // $transaction->update($request->except('sparepart_id', 'quantity'));
        foreach ($transaction->transactionSpareparts as $ts) {
            $sparepart = Sparepart::findOrFail($ts->sparepart_id);
            $ts->update([
                'nama_sparepart' => $sparepart->nama_sparepart,
                'spek' => $sparepart->spek,
                'harga_beli' => $sparepart->harga_beli,
                'harga_jual' => $sparepart->harga_jual,
            ]);
            $ts->save();
        }

        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);
                $newQuantity = $request->quantity[$index];
                $oldQuantity = $oldSpareparts->has($sparepart_id) ? $oldSpareparts[$sparepart_id]->quantity : 0;
                $difference = $newQuantity - $oldQuantity;

                if ($sparepart->jumlah + $oldQuantity < $newQuantity) {
                    return back()->withErrors(['sparepart_id' => 'Stok tidak cukup untuk ' . $sparepart->nama_sparepart]);
                }
                if ($difference != 0) {
                    $sparepart->decrement('jumlah', $difference);
                }
                SparepartTransaction::create([
                    'transaction_id' => $transaction->id,
                    'sparepart_id' => $sparepart_id,
                    'quantity' => $newQuantity,
                    'nama_sparepart' => $sparepart->nama_sparepart,
                    'spek' => $sparepart->spek,
                    'harga_beli' => $sparepart->harga_beli,
                    'harga_jual' => $sparepart->harga_jual,
                ]);
            }
        }

        foreach ($oldSpareparts as $old) {
            if (!in_array($old->sparepart_id, $request->sparepart_id ?? [])) {
                Sparepart::find($old->sparepart_id)->increment('jumlah', $old->quantity);
            }
        }

        if ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('payment_proofs', 'public');
            $transaction->update(['payment_proof' => $paymentProof]);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
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
