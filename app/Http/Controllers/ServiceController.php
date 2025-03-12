<?php

namespace App\Http\Controllers;


use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\ServiceChecklist;
use App\Models\ServiceSparepart;
use App\Models\SparepartHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\isEmpty;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $paymentStatus = $request->get('payment_status', 'all');
        $request->session()->put('payment_status', $paymentStatus);
    
        if (Auth::user()->jurusan == 'General') {
            $servicesQuery = Service::query();
        } else {
            $servicesQuery = Service::query()->where('jurusan', 'like', Auth::user()->jurusan);
        }
    
        if ($paymentStatus !== 'all') {
            $servicesQuery = $servicesQuery->when($paymentStatus === 'paid', function ($query) {
                return $query->where('payment_received', '>=', DB::raw('total_cost'));
            })
            ->where('jurusan', 'like', 'TSM')
            ->when($paymentStatus === 'unpaid', function ($query) {
                return $query->where('payment_received', '<', DB::raw('total_cost'));
            });
        }
    
        // Tambahkan pencarian di beberapa kolom tambahan
        if ($search = $request->get('search')) {
            $servicesQuery = $servicesQuery->where(function ($query) use ($search) {
                $query->whereHas('vehicle', function ($query) use ($search) {
                    $query->where('license_plate', 'like', "%{$search}%");
                })
                ->orWhereHas('vehicle.customer', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhere('complaint', 'like', "%{$search}%")
                ->orWhere('service_type', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('additional_notes', 'like', "%{$search}%")
                ->orWhere('technician_name', 'like', "%{$search}%")
                ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }
    
        if ($date = $request->get('date')) {
            $servicesQuery = $servicesQuery->whereDate('created_at', $date);
        }
    
        if ($year = $request->get('year')) {
            $servicesQuery = $servicesQuery->whereYear('created_at', $year);
        }
    
        if ($dayOfWeek = $request->get('day_of_week')) {
            $servicesQuery = $servicesQuery->whereRaw('DAYOFWEEK(created_at) = ?', [$dayOfWeek]);
        }
    
        $services = $servicesQuery->paginate(10);
    
        return view('service.index', compact('services'));
    }

    
    public function create($vehicle_id)
    {
        $vehicle = Vehicle::find($vehicle_id);
        if (!Gate::allows('isSameJurusan', [$vehicle])) {
            abort(403, 'data tidak ditemukan!!');
        }
        // Admin & kasir
        if (!Gate::allows('isAdminOrEngineer') && !Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }

        $auth = Auth::user()->jurusan;
        $vehicle = Vehicle::findOrFail($vehicle_id);
        $spareparts = Sparepart::all()->where('jurusan', 'like', $auth);
        return view('service.create', compact('vehicle', 'spareparts'));
    }

    public function edit($id)
    {
        $service = Service::find($id);
        if (!Gate::allows('isSameJurusan', [$service])) {
            abort(403, 'data tidak ditemukan!!');
        }

        // Admin & kasir
        if (!Gate::allows('isAdminOrEngineer') && !Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $auth = Auth::user()->jurusan;
        $service = Service::findOrFail($id);
        $spareparts = Sparepart::all()->where('jurusan', 'like', $auth);
        return view('service.edit', compact('service', 'spareparts'));
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'service_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'diskon' => 'nullable|numeric|min:0',
            'payment_received' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,cooperative,administration',
        ], [
            'service_fee.required' => 'Biaya layanan harus diisi.',
            'service_fee.numeric' => 'Biaya layanan harus berupa angka.',
            'total_cost.required' => 'Biaya total harus diisi.',
            'total_cost.numeric' => 'Biaya total harus berupa angka.',
            'payment_received.required' => 'Pembayaran yang diterima harus diisi.',
            'payment_received.numeric' => 'Pembayaran yang diterima harus berupa angka.',
            'payment_received.min' => 'Pembayaran tidak boleh negatif.',
            'diskon.numeric' => 'Diskon harus berupa angka.',
            'diskon.min' => 'Diskon tidak boleh negatif.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ]);

        // Ambil data layanan yang akan diperbarui
        $service = Service::findOrFail($id);

        // Hitung total biaya setelah diskon
        $diskon = $request->diskon ?? 0;
        // $totalCostAfterDiscount = $request->total_cost - ($request->total_cost * ($diskon / 100));

        // Hitung kembalian
        // $change = $request->payment_received - $totalCostAfterDiscount;
        // $change = $request->payment_received;

        // Perbarui hanya data pembayaran tanpa mengubah vehicle_id
        $service->update([
            'service_fee' => $request->service_fee,
            'total_cost' => $request->total_cost,
            'diskon' => $diskon,
            'payment_received' => $request->payment_received,
            'change' => $request->change,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function completeService($id)
    {
        $service = Service::findOrFail($id);

        // Ubah status menjadi selesai
        $service->status = 1;
        $service->save();

        return redirect()->back()->with('success', 'Servis telah diselesaikan!');
    }
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'complaint' => 'required|string|max:255',
            'current_mileage' => 'required|numeric',
            'service_date' => 'required|date',
            'service_type' => 'required|string|in:light,medium,heavy',
            'technician_name' => 'required|string|max:255',
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'jumlah' => 'nullable|array',
            'jurusan' => 'required',
            'jumlah.*' => 'required|numeric|min:1',
            'additional_notes' => 'nullable|string|max:500',
        ], [
            'vehicle_id.required' => 'ID kendaraan harus dipilih.',
            'vehicle_id.exists' => 'Kendaraan tidak ditemukan.',
            'complaint.required' => 'Keluhan harus diisi.',
            'complaint.string' => 'Keluhan harus berupa teks.',
            'complaint.max' => 'Keluhan maksimal 255 karakter.',
            'current_mileage.required' => 'Kilometer kendaraan harus diisi.',
            'current_mileage.numeric' => 'Kilometer kendaraan harus berupa angka.',
            'service_date.required' => 'Tanggal layanan harus diisi.',
            'service_date.date' => 'Tanggal layanan tidak valid.',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        if (!Gate::allows('isSameJurusan', [$vehicle])) {
            return redirect()->back()->withErrors(['vehicle_id' => 'Anda tidak memiliki izin untuk menambahkan layanan ke kendaraan ini.']);
        }


        $service = Service::create([
            'vehicle_id' => $request->vehicle_id,
            'complaint' => $request->complaint,
            'current_mileage' => $request->current_mileage,
            'service_date' => $request->service_date,
            'service_type' => $request->service_type,
            'technician_name' => $request->technician_name,
            'jurusan' => $request->jurusan,
            'additional_notes' => $request->additional_notes,
        ]);

        if ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('payment_proofs', 'public');
            $service->update(['payment_proof' => $paymentProof]);
        }

        $total_keuntungan = 0;
        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);

                if ($sparepart->jumlah >= $request->jumlah[$index]) {
                    $oldSparepart = $sparepart->jumlah;
                    $sparepart->decrement('jumlah', $request->jumlah[$index]);

                    // ini untuk jumlah_before
                    // dd($oldSparepart);

                    // ini untuk jumlah_changed
                    // dd($request->jumlah[$index]); 

                    // ini untuk jumlah_after
                    // dd($sparepart->jumlah;
                    // SparepartHistory::create([
                    //     'sparepart_id' => $sparepart_id,
                    //     'jumlah_changed' => -$request->jumlah[$index],
                    //     'action' => 'subtract',
                    // ]);

                    $keuntungan_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
                    $total_keuntungan += $keuntungan_per_sparepart * $request->jumlah[$index];

                    ServiceSparepart::create([
                        'service_id' => $service->id,
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $request->jumlah[$index],
                    ]);
                } else {
                    return redirect()->back()->withErrors(['sparepart_id' => 'Stok sparepart tidak cukup untuk layanan ini.']);
                }
            }
        }

        return redirect()->route('service.show', $service->id)
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    public function update(Request $request, $id)
{
    $service = Service::with('serviceSpareparts')->findOrFail($id);
    
    // 1. Simpan data lama SEBELUM dihapus
    $oldSpareparts = $service->serviceSpareparts->keyBy('sparepart_id');
    
    // 2. Hapus semua relasi lama SEKALIGUS
    $service->serviceSpareparts()->delete();

    // 3. Update data service
    $service->update($request->except('sparepart_id', 'jumlah', 'payment_proof'));

    // 4. Proses sparepart baru
    $total_keuntungan = 0;
    
    if($request->sparepart_id) {
        foreach($request->sparepart_id as $index => $sparepart_id) {
            $sparepart = Sparepart::findOrFail($sparepart_id);
            $newQuantity = $request->jumlah[$index];
            
            // 5. Cari kuantitas lama
            $oldQuantity = $oldSpareparts->has($sparepart_id) 
                ? $oldSpareparts[$sparepart_id]->quantity 
                : 0;

            // 6. Hitung selisih
            $difference = $newQuantity - $oldQuantity;
            
            // 7. Update stok
            if($sparepart->jumlah + $oldQuantity < $newQuantity) {
                return back()->withErrors(['sparepart_id' => 'Stok tidak cukup untuk '.$sparepart->nama_sparepart]);
            }
            
            $sparepart->decrement('jumlah', $difference);

            // 8. Simpan relasi baru
            ServiceSparepart::create([
                'service_id' => $service->id,
                'sparepart_id' => $sparepart_id,
                'quantity' => $newQuantity
            ]);
        }
    }

    // 9. Kembalikan stok untuk sparepart yang dihapus
    foreach($oldSpareparts as $old) {
        if(!in_array($old->sparepart_id, $request->sparepart_id ?? [])) {
            Sparepart::find($old->sparepart_id)->increment('jumlah', $old->quantity);
        }
    }

    // Handle payment proof
    if ($request->hasFile('payment_proof')) {
        $paymentProof = $request->file('payment_proof')->store('payment_proofs', 'public');
        $service->update(['payment_proof' => $paymentProof]);
    }

    return redirect()->route('service.show', $service->id)
        ->with('success', 'Layanan berhasil diperbarui!');
}

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'required|numeric|min:1',
        ], [
            'sparepart_id.array' => 'ID sparepart harus berupa array.',
            'sparepart_id.*.exists' => 'Salah satu sparepart tidak ditemukan.',
            'jumlah.array' => 'Jumlah sparepart harus berupa array.',
            'jumlah.*.required' => 'Jumlah sparepart harus diisi.',
            'jumlah.*.numeric' => 'Jumlah sparepart harus berupa angka.',
            'jumlah.*.min' => 'Jumlah sparepart minimal 1.',
        ]);

        $service = Service::findOrFail($id);

        foreach ($service->serviceSpareparts as $serviceSparepart) {
            $serviceSparepart->delete();
        }

        $total_keuntungan = 0;
        foreach ($request->input('sparepart_id') as $key => $sparepart_id) {
            $sparepart = Sparepart::find($sparepart_id);

            if ($sparepart && $sparepart->jumlah >= $request->input('jumlah')[$key]) {
                $sparepart->decrement('jumlah', $request->input('jumlah')[$key]);

                $keuntungan_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
                $total_keuntungan += $keuntungan_per_sparepart * $request->input('jumlah')[$key];

                ServiceSparepart::create([
                    'service_id' => $service->id,
                    'sparepart_id' => $sparepart_id,
                    'quantity' => $request->input('jumlah')[$key],
                ]);
            } else {
                return redirect()->back()->withErrors(['sparepart_id' => 'Stok sparepart tidak cukup untuk layanan ini.']);
            }
        }

        $service->update($request->all());

        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $vehicle_id = $service->vehicle_id;
        $service->delete();
        return redirect()->route('vehicle.show', $vehicle_id)
            ->with('success', 'Layanan berhasil dihapus!');
    }

    public function show($id)
    {
        $serviceId = Service::find($id);
        if (!Gate::allows('isSameJurusan', [$serviceId])) {
            abort(403, 'data tidak ditemukan!!');
        }
        $service = Service::with('checklists')->findOrFail($id);

        $serviceSparepart = Service::with('serviceSpareparts')->find($id);
        // $totalSparepart = $serviceSparepart->serviceSpareparts->sum('harga');
        $totalSparepart = 2;
        // return view('your-view', compact('service', 'totalSparepart'));


        return view('service.show', compact('service', 'serviceSparepart', 'totalSparepart'));
    }

    public function addChecklist(Request $request, $id)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        $service = Service::findOrFail($id);
        $service->checklists()->create([
            'task' => $request->task,
            'added_at' => now(), // Menambahkan waktu sekarang
        ]);

        return redirect()->route('service.show', $id)->with('success', 'Checklist added successfully!');
    }

    public function updateChecklistStatus(Request $request, $id)
    {
        $checklist = ServiceChecklist::findOrFail($id);
        $checklist->is_completed = $request->has('is_completed');
        $checklist->save();

        return redirect()->route('service.show', $checklist->service_id)->with('success', 'Checklist updated successfully!');
    }
    public function editChecklist($id)
    {
        // Admin & kasir
        if (!Gate::allows('isAdminOrEngineer') && !Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $checklist = ServiceChecklist::findOrFail($id);
        return view('service.editChecklist', compact('checklist'));
    }
    public function updateChecklistTask(Request $request, $id)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        $checklist = ServiceChecklist::findOrFail($id);
        $checklist->task = $request->task;
        $checklist->save();

        // Redirect back to the service's show page after the update
        return redirect()->route('service.show', $checklist->service_id)->with('success', 'Checklist updated successfully!');
    }

    public function deleteChecklist($id)
    {
        $checklist = ServiceChecklist::findOrFail($id);
        $checklist->delete();
        return redirect()->route('service.show', $checklist->service_id)->with('success', 'Checklist deleted successfully!');
    }

    public function getSparepartNotifications()
    {
        $spareparts = Sparepart::where('jumlah', '>=', 2)->get();

        return response()->json($spareparts);
    }
}
