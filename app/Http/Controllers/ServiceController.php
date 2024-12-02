<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\ServiceSparepart;
use App\Models\Sparepart;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $paymentStatus = $request->get('payment_status', 'all');
        $request->session()->put('payment_status', $paymentStatus);
    
        // Start with the base query
        $servicesQuery = Service::query();
    
        // Apply payment status filter
        if ($paymentStatus !== 'all') {
            $servicesQuery = $servicesQuery->when($paymentStatus === 'paid', function ($query) {
                return $query->where('payment_received', '>=', DB::raw('total_cost'));
            })->when($paymentStatus === 'unpaid', function ($query) {
                return $query->where('payment_received', '<', DB::raw('total_cost'));
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
    
        // Apply time of day filter
        if ($timeOfDay = $request->get('time_of_day')) {
            $servicesQuery = $servicesQuery->whereBetween('created_at', function ($query) use ($timeOfDay) {
                switch ($timeOfDay) {
                    case 'morning':
                        return $query->whereTime('created_at', '>=', '06:00:00')->whereTime('created_at', '<', '12:00:00');
                    case 'afternoon':
                        return $query->whereTime('created_at', '>=', '12:00:00')->whereTime('created_at', '<', '18:00:00');
                    case 'evening':
                        return $query->whereTime('created_at', '>=', '18:00:00')->whereTime('created_at', '<', '23:59:59');
                    default:
                        return $query;
                }
            });
        }
    
        // Apply day of the week filter
        if ($dayOfWeek = $request->get('day_of_week')) {
            $servicesQuery = $servicesQuery->whereRaw('DAYOFWEEK(created_at) = ?', [$dayOfWeek]);
        }
    
        // Paginate the results
        $services = $servicesQuery->paginate(10);
    
        return view('service.index', compact('services'));
    }
     
    public function create($vehicle_id)
    {
        $vehicle = Vehicle::findOrFail($vehicle_id);
        $spareparts = Sparepart::all();
        return view('service.create', compact('vehicle', 'spareparts'));
    }    

    public function edit($id)
    {
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
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'required|numeric|min:1',
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
            'sparepart_id.array' => 'ID sparepart harus berupa array.',
            'sparepart_id.*.exists' => 'Salah satu sparepart tidak ditemukan.',
            'jumlah.array' => 'Jumlah sparepart harus berupa array.',
            'jumlah.*.required' => 'Jumlah sparepart harus diisi.',
            'jumlah.*.numeric' => 'Jumlah sparepart harus berupa angka.',
            'jumlah.*.min' => 'Jumlah sparepart minimal 1.',
        ]);

        $service = Service::create($request->except('sparepart_id', 'jumlah'));

        $total_keuntungan = 0;
        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);

                if ($sparepart->jumlah >= $request->jumlah[$index]) {
                    $sparepart->decrement('jumlah', $request->jumlah[$index]);

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
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'exists:spareparts,id_sparepart',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'required|numeric|min:1',
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
        }
    
        $service->serviceSpareparts()->delete();
    
        $service->update($request->except('sparepart_id', 'jumlah'));
    
        $total_keuntungan = 0;
        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);
    
                if ($sparepart->jumlah >= $request->jumlah[$index]) {
                    $sparepart->decrement('jumlah', $request->jumlah[$index]);
    
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
        $service = Service::findOrFail($id);
        
        // Update spareparts yang diambil dari service
        $service->spareparts()->detach(); // Hapus semua sparepart yang terkait
        
        // Add back the removed spareparts to stock
        foreach ($request->input('sparepart_id') as $key => $sparepart_id) {
            $sparepart = Sparepart::find($sparepart_id);
            $sparepart->increment('stok', $request->input('jumlah')[$key]); // Menambahkan kembali ke stok
        }
        
        // Update layanan lainnya...
        $service->update($validatedData);

        return redirect()->route('services.index');
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
        $service = Service::findOrFail($id);
        $serviceSpareparts = $service->serviceSpareparts;

        return view('service.show', compact('service', 'serviceSpareparts'));
    }

    public function getSparepartNotifications()
    {
        // Fetch spare parts where the quantity is 2 or more
        $spareparts = Sparepart::where('jumlah', '>=', 2)->get();

        // Return the data as JSON
        return response()->json($spareparts);
    }

}