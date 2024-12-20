<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
// At the top of your VehicleController.php (or any relevant controller)
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    // Show the form to add a new vehicle
    public function create($customerId)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $customer = Customer::findOrFail($customerId);
        return view('vehicle.create', compact('customer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles',
            'vehicle_type' => 'required|string|max:255',
            'engine_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicle_images', 'public');
        }

        Vehicle::create([
            'license_plate' => $request->license_plate,
            'vehicle_type' => $request->vehicle_type,
            'engine_code' => $request->engine_code,
            'color' => $request->color,
            'production_year' => $request->year,
            'image' => $imagePath,
            'customer_id' => $request->customer_id,
        ]);

        return redirect()->route('customer.show', $request->customer_id)
            ->with('success', 'Data kendaraan berhasil ditambahkan!');
    }

    public function show($id, Request $request)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $vehicle = Vehicle::findOrFail($id);
        
        $services = Service::where('vehicle_id', $id)
                            ->when($request->search, function($query) use ($request) {
                                return $query->where('service_type', 'like', '%' . $request->search . '%');
                            })
                            ->paginate(2);
    
        return view('vehicle.show', compact('vehicle', 'services'));
    }
    

    // Show the form to edit the vehicle data
    public function edit($id)
    {
        // Admin & kasir
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicle.edit', compact('vehicle'));
    }

    // Update vehicle data
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate,' . $vehicle->id,
            'vehicle_type' => 'required|string|max:255',
            'engine_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($vehicle->image) {
                Storage::disk('public')->delete($vehicle->image);
            }
            $vehicle->image = $request->file('image')->store('vehicle_images', 'public');
        }

        // Update the vehicle data
        $vehicle->update([
            'license_plate' => $request->license_plate,
            'vehicle_type' => $request->vehicle_type,
            'engine_code' => $request->engine_code,
            'color' => $request->color,
            'production_year' => $request->year,
        ]);

        return redirect()->route('customer.show', $vehicle->customer_id)
            ->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    // Delete vehicle data
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // Delete image if exists
        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
        }

        $vehicle->delete();

        return redirect()->route('customer.show', $vehicle->customer_id)
            ->with('success', 'Data kendaraan berhasil dihapus!');
    }
}