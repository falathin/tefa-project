<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\ServiceChecklist;
use App\Models\ServiceSparepart;
use App\Models\SparepartHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $paymentStatus = $request->get('payment_status', 'all');
        $request->session()->put('payment_status', $paymentStatus);

        $servicesQuery = Service::query();

        if ($paymentStatus !== 'all') {
            $servicesQuery = $servicesQuery->when($paymentStatus === 'paid', function ($query) {
                return $query->where('payment_received', '>=', DB::raw('total_cost'));
            })->when($paymentStatus === 'unpaid', function ($query) {
                return $query->where('payment_received', '<', DB::raw('total_cost'));
            });
        }

        // Apply search filter across multiple related tables
        if ($search = $request->get('search')) {
            $servicesQuery = $servicesQuery->where(function ($query) use ($search) {
                // Search in 'vehicle' table's 'license_plate' and 'customer' table's 'name'
                $query->whereHas('vehicle', function ($query) use ($search) {
                    $query->where('license_plate', 'like', "%{$search}%");
                })
                    ->orWhereHas('vehicle.customer', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('complaint', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%");
            });
        }

        // Apply date filter
        if ($date = $request->get('date')) {
            $servicesQuery = $servicesQuery->whereDate('created_at', $date);
        }

        // Apply year filter
        if ($year = $request->get('year')) {
            $servicesQuery = $servicesQuery->whereYear('created_at', $year);
        }

        // Apply day of the week filter
        if ($dayOfWeek = $request->get('day_of_week')) {
            $servicesQuery = $servicesQuery->whereRaw('DAYOFWEEK(created_at) = ?', [$dayOfWeek]);
        }

        $services = $servicesQuery->paginate(10);

        return view('service.index', compact('services'));
    }

    public function create($vehicle_id)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $vehicle = Vehicle::findOrFail($vehicle_id);
        $spareparts = Sparepart::all();
        return view('service.create', compact('vehicle', 'spareparts'));
    }

    public function edit($id)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $service = Service::findOrFail($id);
        $spareparts = Sparepart::all(); // Get all spare parts
        return view('service.edit', compact('service', 'spareparts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'complaint' => 'required|string|max:255',
            'current_mileage' => 'required|numeric',
            'service_fee' => 'required|numeric',
            'service_date' => 'required|date',
            'total_cost' => 'required|numeric',
            'payment_received' => 'required|numeric',
            'change' => 'required|numeric',
            'service_type' => 'required|string|in:light,medium,heavy',
            'technician_name' => 'required|string|max:255',
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'jumlah' => 'nullable|array',
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
            'service_fee.required' => 'Biaya layanan harus diisi.',
            'service_fee.numeric' => 'Biaya layanan harus berupa angka.',
            'service_date.required' => 'Tanggal layanan harus diisi.',
            'service_date.date' => 'Tanggal layanan tidak valid.',
            'total_cost.required' => 'Biaya total harus diisi.',
            'total_cost.numeric' => 'Biaya total harus berupa angka.',
            'payment_received.required' => 'Pembayaran yang diterima harus diisi.',
            'payment_received.numeric' => 'Pembayaran yang diterima harus berupa angka.',
            'change.required' => 'Kembalian harus diisi.',
            'change.numeric' => 'Kembalian harus berupa angka.',
            'service_type.required' => 'Jenis layanan harus dipilih.',
            'service_type.in' => 'Jenis layanan tidak valid.',
            'technician_name.required' => 'Nama teknisi harus diisi.',
            'technician_name.string' => 'Nama teknisi harus berupa teks.',
            'technician_name.max' => 'Nama teknisi maksimal 255 karakter.',
            'sparepart_id.array' => 'ID sparepart harus berupa array.',
            'sparepart_id.*.exists' => 'Salah satu sparepart tidak ditemukan.',
            'jumlah.array' => 'Jumlah sparepart harus berupa array.',
            'jumlah.*.required' => 'Jumlah sparepart harus diisi.',
            'jumlah.*.numeric' => 'Jumlah sparepart harus berupa angka.',
            'jumlah.*.min' => 'Jumlah sparepart minimal 1.',
            'additional_notes.max' => 'Catatan tambahan maksimal 500 karakter.',
        ]);

        $service = Service::create($request->except('sparepart_id', 'jumlah', 'payment_proof'));

        if ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('payment_proofs', 'public');
            $service->update(['payment_proof' => $paymentProof]);
        }

        $total_keuntungan = 0;
        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);

                if ($sparepart->jumlah >= $request->jumlah[$index]) {
                    $sparepart->decrement('jumlah', $request->jumlah[$index]);

                    SparepartHistory::create([
                        'sparepart_id' => $sparepart_id,
                        'jumlah_changed' => -$request->jumlah[$index],
                        'action' => 'subtract',
                    ]);

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

        return redirect()->route('vehicle.show', $service->vehicle_id)
            ->with('success', 'Layanan berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'complaint' => 'required|string|max:255',
            'current_mileage' => 'required|numeric',
            'service_fee' => 'required|numeric',
            'service_date' => 'required|date',
            'total_cost' => 'required|numeric',
            'payment_received' => 'required|numeric',
            'change' => 'required|numeric',
            'service_type' => 'required|string|in:light,medium,heavy',
            'technician_name' => 'required|string|max:255',
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'jumlah' => 'nullable|array',
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
            'service_fee.required' => 'Biaya layanan harus diisi.',
            'service_fee.numeric' => 'Biaya layanan harus berupa angka.',
            'service_date.required' => 'Tanggal layanan harus diisi.',
            'service_date.date' => 'Tanggal layanan tidak valid.',
            'total_cost.required' => 'Biaya total harus diisi.',
            'total_cost.numeric' => 'Biaya total harus berupa angka.',
            'payment_received.required' => 'Pembayaran yang diterima harus diisi.',
            'payment_received.numeric' => 'Pembayaran yang diterima harus berupa angka.',
            'change.required' => 'Kembalian harus diisi.',
            'change.numeric' => 'Kembalian harus berupa angka.',
            'service_type.required' => 'Jenis layanan harus dipilih.',
            'service_type.in' => 'Jenis layanan tidak valid.',
            'technician_name.required' => 'Nama teknisi harus diisi.',
            'technician_name.string' => 'Nama teknisi harus berupa teks.',
            'technician_name.max' => 'Nama teknisi maksimal 255 karakter.',
            'sparepart_id.array' => 'ID sparepart harus berupa array.',
            'sparepart_id.*.exists' => 'Salah satu sparepart tidak ditemukan.',
            'jumlah.array' => 'Jumlah sparepart harus berupa array.',
            'jumlah.*.required' => 'Jumlah sparepart harus diisi.',
            'jumlah.*.numeric' => 'Jumlah sparepart harus berupa angka.',
            'jumlah.*.min' => 'Jumlah sparepart minimal 1.',
            'additional_notes.max' => 'Catatan tambahan maksimal 500 karakter.',
        ]);

        $service = Service::findOrFail($id);

        foreach ($service->serviceSpareparts as $serviceSparepart) {
            $sparepart = Sparepart::findOrFail($serviceSparepart->sparepart_id);
            $sparepart->increment('jumlah', $serviceSparepart->quantity);

            SparepartHistory::create([
                'sparepart_id' => $sparepart->id_sparepart,
                'jumlah_changed' => $serviceSparepart->quantity,
                'action' => 'add',
            ]);
        }

        $service->serviceSpareparts()->delete();

        $service->update($request->except('sparepart_id', 'jumlah', 'payment_proof'));

        if ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('payment_proofs', 'public');
            $service->update(['payment_proof' => $paymentProof]);
        }

        $total_keuntungan = 0;
        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);

                if ($sparepart->jumlah >= $request->jumlah[$index]) {
                    $sparepart->decrement('jumlah', $request->jumlah[$index]);

                    SparepartHistory::create([
                        'sparepart_id' => $sparepart_id,
                        'jumlah_changed' => -$request->jumlah[$index],
                        'action' => 'subtract',
                    ]);

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

        return redirect()->route('vehicle.show', $service->vehicle_id)
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
            $sparepart = Sparepart::findOrFail($serviceSparepart->sparepart_id);
            $sparepart->increment('jumlah', $serviceSparepart->quantity);

            SparepartHistory::create([
                'sparepart_id' => $sparepart->id_sparepart,
                'jumlah_changed' => $serviceSparepart->quantity,
                'action' => 'add',
            ]);
        }

        $service->serviceSpareparts()->delete();

        $total_keuntungan = 0;
        foreach ($request->input('sparepart_id') as $key => $sparepart_id) {
            $sparepart = Sparepart::find($sparepart_id);

            if ($sparepart && $sparepart->jumlah >= $request->input('jumlah')[$key]) {
                $sparepart->decrement('jumlah', $request->input('jumlah')[$key]);

                SparepartHistory::create([
                    'sparepart_id' => $sparepart_id,
                    'jumlah_changed' => -$request->input('jumlah')[$key],
                    'action' => 'subtract',
                ]);

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
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $service = Service::with('checklists')->findOrFail($id);

        return view('service.show', compact('service'));
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
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
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
