<?php

namespace App\Http\Controllers;

use App\Models\SparepartTransaction;
use App\Models\Sparepart;
use App\Models\SparepartHistory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = SparepartTransaction::with('sparepart')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $spareparts = Sparepart::all();
        return view('transactions.create', compact('spareparts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sparepart_id' => 'required|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart', // Fix the validation to use the custom primary key
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
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

        $total_profit = 0;

        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::where('id_sparepart', $sparepart_id)->firstOrFail();  // Fixed the query

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
                        'purchase_price' => $sparepart->harga_beli,
                        'total_price' => $sparepart->harga_jual * $request->quantity[$index],
                        'transaction_date' => now(),
                        'transaction_type' => 'sale',
                    ]);
                } else {
                    return redirect()->back()->withErrors(['sparepart_id' => 'Stok sparepart tidak cukup.']);
                }
            }
        }

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaksi sparepart berhasil disimpan!');
    }

    public function show($id)
    {
        $transaction = SparepartTransaction::with('spareparts')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }
    
    public function edit($id)
    {
        $transaction = SparepartTransaction::findOrFail($id);
        $spareparts = Sparepart::all();
        
        $transactionDate = \Carbon\Carbon::parse($transaction->transaction_date);
    
        $formattedDate = $transactionDate->toDateString();
    
        $transactionDetails = $transaction->spareparts ?? collect();
        
        return view('transactions.edit', compact('transaction', 'spareparts', 'transactionDetails', 'formattedDate'));
    }
       
    public function update(Request $request, $id)
    {
        $request->validate([
            'sparepart_id' => 'required|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
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
        $previous_quantity = $transaction->quantity;  // Store previous quantity to adjust stock properly

        // Rollback the previous transaction quantity in the spareparts table
        $transaction->spareparts()->each(function ($sparepart) use ($previous_quantity) {
            $sparepart->increment('jumlah', $previous_quantity);
            SparepartHistory::create([
                'sparepart_id' => $sparepart->id,
                'jumlah_changed' => $previous_quantity,
                'action' => 'add',
            ]);
        });

        $total_profit = 0;

        foreach ($request->sparepart_id as $index => $sparepart_id) {
            $sparepart = Sparepart::findOrFail($sparepart_id);

            if ($sparepart->jumlah >= $request->quantity[$index]) {
                // Decrease stock based on updated quantity
                $sparepart->decrement('jumlah', $request->quantity[$index]);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => -$request->quantity[$index],
                    'action' => 'subtract',
                ]);

                $profit_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
                $total_profit += $profit_per_sparepart * $request->quantity[$index];

                // Update the transaction details
                $transaction->spareparts()->create([
                    'sparepart_id' => $sparepart_id,
                    'quantity' => $request->quantity[$index],
                    'purchase_price' => $sparepart->harga_beli,
                    'total_price' => $sparepart->harga_jual * $request->quantity[$index],
                    'transaction_date' => now(),
                    'transaction_type' => 'sale',
                ]);
            } else {
                return redirect()->back()->withErrors(['sparepart_id' => 'Stok sparepart tidak cukup.']);
            }
        }

        $transaction->total_profit = $total_profit;
        $transaction->save();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaksi sparepart berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = SparepartTransaction::findOrFail($id);

        // Rollback the transaction's changes on spareparts
        $transaction->spareparts()->each(function ($sparepart) use ($transaction) {
            $sparepart->increment('jumlah', $transaction->quantity);
            SparepartHistory::create([
                'sparepart_id' => $sparepart->id,
                'jumlah_changed' => $transaction->quantity,
                'action' => 'add',
            ]);
        });

        $transaction->delete();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaksi sparepart berhasil dihapus!');
    }
}