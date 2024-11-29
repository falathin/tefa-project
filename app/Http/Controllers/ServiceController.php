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
    // Display all services with pagination
    public function index()
    {
        $services = Service::paginate(4); // Paginate services, 4 per page
        return view('service.index', compact('services'));
    }    

    // Display create page for a new service based on vehicle_id
    public function create($vehicle_id)
    {
        $vehicle = Vehicle::findOrFail($vehicle_id);
        $spareparts = Sparepart::all();
        return view('service.create', compact('vehicle', 'spareparts'));
    }    

    // Display edit page for the existing service
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $spareparts = Sparepart::all(); // Get all spare parts
        return view('service.edit', compact('service', 'spareparts'));
    }

    // Store a new service record
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
            'spareparts' => 'nullable|array',
            'spareparts.*' => 'exists:spareparts,id',
            'quantities' => 'nullable|array',
            'quantities.*' => 'required|numeric|min:1',
        ]);

        // Save service data
        $service = Service::create($request->except('spareparts', 'quantities'));

        // Calculate total profit from spareparts
        $total_keuntungan = 0;
        if ($request->spareparts) {
            foreach ($request->spareparts as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);

                // Check if sparepart stock is sufficient
                if ($sparepart->jumlah >= $request->quantities[$index]) {
                    // Decrement sparepart stock
                    $sparepart->decrement('jumlah', $request->quantities[$index]);

                    // Calculate profit
                    $keuntungan_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
                    $total_keuntungan += $keuntungan_per_sparepart * $request->quantities[$index];

                    // Save service sparepart data
                    ServiceSparepart::create([
                        'service_id' => $service->id,
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $request->quantities[$index],
                    ]);
                } else {
                    // If sparepart stock is insufficient
                    return redirect()->back()->withErrors(['spareparts' => 'Insufficient sparepart stock for this service.']);
                }
            }
        }

        // Redirect to vehicle/show page
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
            'service_type' => 'required|string|max:255',
            'spareparts' => 'nullable|array',
            'spareparts.*' => 'exists:spareparts,id',
            'quantities' => 'nullable|array',
            'quantities.*' => 'required|numeric|min:1',
        ]);
    
        $service = Service::findOrFail($id);
        $service->update($request->except('spareparts', 'quantities'));
    
        // Remove old spareparts and save the new ones
        $service->serviceSpareparts()->delete();
    
        if ($request->has('spareparts') && $request->has('quantities')) {
            foreach ($request->spareparts as $index => $sparepart_id) {
                $sparepart = Sparepart::findOrFail($sparepart_id);
    
                // Check if sparepart stock is sufficient
                if ($sparepart->jumlah >= $request->quantities[$index]) {
                    // Decrement sparepart stock
                    $sparepart->decrement('jumlah', $request->quantities[$index]);
    
                    // Save service sparepart data
                    ServiceSparepart::create([
                        'service_id' => $service->id,
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $request->quantities[$index],
                    ]);
                } else {
                    // If sparepart stock is insufficient
                    return redirect()->back()->withErrors(['spareparts' => 'Insufficient sparepart stock for this service.']);
                }
            }
        }
    
        // Redirect to vehicle/show page after update
        return redirect()->route('vehicle.show', $service->vehicle_id)
                         ->with('success', 'Service updated successfully');
    }
    
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $vehicle_id = $service->vehicle_id;  // Save vehicle_id to redirect later
    
        // Optionally, handle deletion of related spareparts or serviceSparepart entries
        $service->delete();
    
        // Redirect to vehicle/show page after deletion
        return redirect()->route('vehicle.show', $vehicle_id)
                         ->with('success', 'Service deleted successfully');
    }
    

    // Display the service details
    public function show($id)
    {
        $service = Service::with('serviceSpareparts.sparepart')->findOrFail($id);
        return view('service.show', compact('service'));
    }

}