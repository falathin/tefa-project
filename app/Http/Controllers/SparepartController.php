<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\SparepartHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
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
    
        SparepartHistory::create([
            'sparepart_id' => $sparepart->id_sparepart,
            'jumlah_changed' => $request->jumlah,
            'action' => 'add',
            'description' => 'Menambah stok sebanyak ' . $request->jumlah . ' unit.',
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
    
        // Simpan nilai lama jumlah
        $old_quantity = $sparepart->jumlah;
        $new_quantity = $request->jumlah;
        $quantity_changed = $new_quantity - $old_quantity;
    
        // Update data sparepart
        $sparepart->update($request->all());
    
        // Jika jumlah berubah, simpan perubahan pada history
        if ($quantity_changed != 0) {
            $action = $quantity_changed > 0 ? 'add' : 'subtract';
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
            $query->where(function($q) use ($request) {
                $q->whereHas('sparepart', function($subQuery) use ($request) {
                    $subQuery->where('nama_sparepart', 'like', '%' . $request->search . '%');
                })
                ->orWhere('action', 'like', '%' . $request->search . '%');
            });
        }
    
        if ($request->filter_date) {
            $query->whereDate('created_at', $request->filter_date);
        }
    
        if ($request->filter_action) {
            $query->where('action', $request->filter_action);
        }
    
        if ($request->filter_day) {
            switch ($request->filter_day) {
                case 'monday':
                    $query->whereRaw('DAYOFWEEK(created_at) = 2'); // Monday
                    break;
                case 'tuesday':
                    $query->whereRaw('DAYOFWEEK(created_at) = 3'); // Tuesday
                    break;
                case 'wednesday':
                    $query->whereRaw('DAYOFWEEK(created_at) = 4'); // Wednesday
                    break;
                case 'thursday':
                    $query->whereRaw('DAYOFWEEK(created_at) = 5'); // Thursday
                    break;
                case 'friday':
                    $query->whereRaw('DAYOFWEEK(created_at) = 6'); // Friday
                    break;
                case 'saturday':
                    $query->whereRaw('DAYOFWEEK(created_at) = 7'); // Saturday
                    break;
                case 'sunday':
                    $query->whereRaw('DAYOFWEEK(created_at) = 1'); // Sunday
                    break;
            }
        }
    
        // Get the filtered histories with pagination
        $histories = $query->orderBy('created_at', 'desc')
            ->with('sparepart')
            ->paginate(5);
    
        // Calculate statistics (total, today, monthly)
        $totalChanges = SparepartHistory::where('sparepart_id', $id)->count();
        $todayChanges = SparepartHistory::where('sparepart_id', $id)
            ->whereDate('created_at', Carbon::today())
            ->count();
        $monthlyChanges = SparepartHistory::where('sparepart_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
    
        return view('sparepart.history', compact('sparepart', 'histories', 'totalChanges', 'todayChanges', 'monthlyChanges'));
    }   
}