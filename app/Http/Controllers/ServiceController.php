<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\ServiceSparepart;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(4);
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
                    return redirect()->back()->withErrors(['sparepart_id' => 'Insufficient sparepart stock for this service.']);
                }
            }
        }

        return redirect()->route('vehicle.show', $service->vehicle_id)
                         ->with('success', 'Service created successfully!');
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
                    return redirect()->back()->withErrors(['sparepart_id' => 'Insufficient sparepart stock for this service.']);
                }
            }
        }
    
        return redirect()->route('vehicle.show', $service->vehicle_id)
                         ->with('success', 'Service updated successfully!');
    }
    
    
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $vehicle_id = $service->vehicle_id;
    
        $service->delete();
    
        return redirect()->route('vehicle.show', $vehicle_id)
                         ->with('success', 'Service deleted successfully');
    }
    

    public function show($id)
    {
        $service = Service::with('serviceSpareparts.sparepart')->findOrFail($id);
        return view('service.show', compact('service'));
    }

}