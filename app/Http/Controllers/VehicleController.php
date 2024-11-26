<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sparepart;
use App\Models\Service;
use App\Models\ServiceSparepart;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function createWithService(Customer $customer)
    {
        // Retrieve available spare parts with stock greater than 0
        $spareparts = Sparepart::where('jumlah', '>', 0)->get();
        return view('vehicle.create_with_service', compact('customer', 'spareparts'));
    }

    public function storeWithService(Request $request)
    {
        // Validate the vehicle data
        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'engine_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:'.date('Y'),
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicle_images', 'public');
        } else {
            $imagePath = null;
        }

        // Create the vehicle record
        $vehicle = Vehicle::create([
            'license_plate' => $validated['license_plate'],
            'type' => $validated['vehicle_type'],
            'color' => $validated['color'],
            'production_year' => $validated['year'],
            'engine_code' => $validated['engine_code'],
            'customer_id' => $request->customer_id, // Get customer_id from hidden input
            'image' => $imagePath,
        ]);

        // Create the service associated with this vehicle
        $service = Service::create([
            'vehicle_id' => $vehicle->id,
            'complaint' => $request->complaint, // You may need to include this field in the form
            'current_mileage' => $request->current_mileage, // Similarly for this field
            'service_fee' => $request->service_fee,
            'service_date' => now(), // You can set this to now or from the form input
            'total_cost' => $request->total_cost,
            'payment_received' => $request->payment_received,
            'change' => $request->change,
            'service_type' => $request->service_type,
        ]);

        // Handle the spareparts for this service (if any)
        if ($request->has('spareparts')) {
            foreach ($request->spareparts as $sparepartId => $quantity) {
                $sparepart = Sparepart::findOrFail($sparepartId);

                ServiceSparepart::create([
                    'service_id' => $service->id,
                    'sparepart_id' => $sparepart->id,
                    'quantity' => $quantity,
                ]);
            }
        }

        // Redirect or return success response
        return redirect()->route('vehicles.index')->with('success', 'Vehicle and service added successfully!');
    }
    

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.show', compact('customer'));
    }

    // Soft delete a specific vehicle
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // Delete the image file if exists
        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
        }

        // Perform the soft delete
        $vehicle->delete();

        // Redirect back with a success message
        return redirect()->route('customer.show', $vehicle->customer_id)
                         ->with('success', 'Vehicle deleted successfully!');
    }
}