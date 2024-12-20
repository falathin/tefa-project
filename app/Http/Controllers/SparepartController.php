<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\SparepartHistory;
use Illuminate\Support\Facades\Gate;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $search = $request->search;
        $spareparts = Sparepart::when($search, function($query, $search) {
            return $query->where('nama_sparepart', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(4);

        return view('sparepart.index', compact('spareparts'));
    }

    public function create()
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
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

        $keuntungan = $request->harga_jual - $request->harga_beli;

        $sparepart = Sparepart::create([
            'nama_sparepart' => $request->nama_sparepart,
            'jumlah' => $request->jumlah,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'keuntungan' => $keuntungan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'deskripsi' => $request->deskripsi,
        ]);

        // Insert into SparepartHistory
        SparepartHistory::create([
            'sparepart_id' => $sparepart->id_sparepart,
            'jumlah_changed' => $request->jumlah,
            'action' => 'add',
            'remaining_stock' => $sparepart->jumlah, // Add the remaining stock here
            'description' => 'Menambah stok sebanyak ' . $request->jumlah . ' unit.',
        ]);

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil ditambahkan.');
    }

    public function show($sparepart_id)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $sparepart = Sparepart::findOrFail($sparepart_id);
        return view('sparepart.show', compact('sparepart'));
    }

    public function edit($id)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
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

        $old_quantity = $sparepart->jumlah;
        $new_quantity = $request->jumlah;
        $quantity_changed = $new_quantity - $old_quantity;

        $sparepart->update($request->all());

        if ($quantity_changed != 0) {
            $action = $quantity_changed > 0 ? 'add' : 'use';
            SparepartHistory::create([
                'sparepart_id' => $sparepart->id_sparepart,
                'jumlah_changed' => $quantity_changed,
                'action' => $action,
                'description' => $action == 'add' ? 
                    'Menambah stok sebanyak ' . abs($quantity_changed) . ' unit.' :
                    'Mengurangi stok sebanyak ' . abs($quantity_changed) . ' unit.',
            ]);
        }

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil dihapus.');
    }

    public function history($id, Request $request)
    {
        $sparepart = Sparepart::findOrFail($id);
        
        $query = SparepartHistory::where('sparepart_id', $id);
        
        if ($request->search) {
            $query->where('action', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filter_date) {
            $query->whereDate('created_at', $request->filter_date);
        }
        
        $histories = $query->orderBy('created_at', 'desc')->paginate(5);
        
        $todayChanges = SparepartHistory::where('sparepart_id', $id)
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->sum(function($history) {
                return $history->action == 'add' ? $history->jumlah_changed : -$history->jumlah_changed;
            });
    
        $todayActionsCount = SparepartHistory::where('sparepart_id', $id)
            ->whereDate('created_at', Carbon::today())
            ->count();
    
        $todayAdded = SparepartHistory::where('sparepart_id', $id)
            ->whereDate('created_at', Carbon::today())
            ->where('action', 'add')
            ->sum('jumlah_changed');
    
        $todaySubtracted = SparepartHistory::where('sparepart_id', $id)
            ->whereDate('created_at', Carbon::today())
            ->where('action', 'subtract')
            ->sum('jumlah_changed');
        
        $monthlyChanges = SparepartHistory::where('sparepart_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->get()
            ->sum(function($history) {
                return $history->action == 'add' ? $history->jumlah_changed : -$history->jumlah_changed;
            });
    
        $monthlyActionsCount = SparepartHistory::where('sparepart_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        $monthlyAdded = SparepartHistory::where('sparepart_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('action', 'add')
            ->sum('jumlah_changed');
    
        $monthlySubtracted = SparepartHistory::where('sparepart_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('action', 'subtract')
            ->sum('jumlah_changed');
        
        $totalChanges = SparepartHistory::where('sparepart_id', $id)
            ->get()
            ->sum(function($history) {
                return $history->action == 'add' ? $history->jumlah_changed : -$history->jumlah_changed;
            });
    
        $totalActionsCount = SparepartHistory::where('sparepart_id', $id)
            ->count();
        
        $totalAdded = SparepartHistory::where('sparepart_id', $id)
            ->where('action', 'add')
            ->sum('jumlah_changed');
    
        $totalSubtracted = SparepartHistory::where('sparepart_id', $id)
            ->where('action', 'subtract')
            ->sum('jumlah_changed');
        
        return view('sparepart.history', compact('sparepart', 'histories', 'todayChanges', 'todayActionsCount', 'todayAdded', 'todaySubtracted', 'monthlyChanges', 'monthlyActionsCount', 'monthlyAdded', 'monthlySubtracted', 'totalChanges', 'totalActionsCount', 'totalAdded', 'totalSubtracted'));
    }
}