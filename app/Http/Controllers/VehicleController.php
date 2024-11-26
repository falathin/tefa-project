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
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'customer_id' => 'required|exists:customers,id', // Ensure customer ID exists
            'complaint' => 'nullable|string|max:255', // Add any necessary validations for service fields
            'current_mileage' => 'nullable|integer|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'payment_received' => 'nullable|numeric|min:0',
            'change' => 'nullable|numeric|min:0',
            'service_type' => 'nullable|string|max:255',
            'spareparts' => 'nullable|array', // Ensure spare parts are passed as an array
            'spareparts.*' => 'exists:spareparts,id|numeric|min:1', // Ensure spare parts IDs are valid
        ]);

        // Handle image upload if exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicle_images', 'public');
        }

        // Create the vehicle and associate it with the customer
        $vehicle = Vehicle::create([
            'license_plate' => $validated['license_plate'],
            'type' => $validated['vehicle_type'],
            'color' => $validated['color'],
            'production_year' => $validated['year'],
            'engine_code' => $validated['engine_code'],
            'customer_id' => $validated['customer_id'], // Use the validated customer ID
            'image' => $imagePath,
        ]);

        // Create the service associated with this vehicle
        $service = Service::create([
            'vehicle_id' => $vehicle->id,
            'complaint' => $validated['complaint'],
            'current_mileage' => $validated['current_mileage'],
            'service_fee' => $validated['service_fee'],
            'service_date' => now(), // Set the current time for service date
            'total_cost' => $validated['total_cost'],
            'payment_received' => $validated['payment_received'],
            'change' => $validated['change'],
            'service_type' => $validated['service_type'],
        ]);

        // Handle the spareparts for this service (if any)
        if (!empty($validated['spareparts'])) {
            foreach ($validated['spareparts'] as $sparepartId => $quantity) {
                $sparepart = Sparepart::findOrFail($sparepartId);

                // Check if enough stock is available
                if ($sparepart->jumlah < $quantity) {
                    return back()->with('error', 'Not enough stock for sparepart: ' . $sparepart->name);
                }

                // Update spare part stock
                $sparepart->decrement('jumlah', $quantity);

                // Record the spare part usage
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