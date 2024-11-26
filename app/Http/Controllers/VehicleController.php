<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    // Display the form to create a new vehicle
    public function create($customerId)
    {
        $customer = Customer::findOrFail($customerId); // Get the customer by ID
        return view('vehicle.create', compact('customer'));
    }

    // Store a newly created vehicle in the database
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id', // Ensure customer exists
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:15',
            'engine_code' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:9999',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Image validation
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicle_images', 'public');
        }

        // Create the vehicle
        $vehicle = Vehicle::create([
            'license_plate' => $request->license_plate,
            'type' => $request->vehicle_type,
            'color' => $request->color,
            'production_year' => $request->year,
            'engine_code' => $request->engine_code,
            'customer_id' => $request->customer_id,
            'image' => $imagePath, // Store the image path
        ]);

        // Redirect to the vehicle's detail page with a success message
        return redirect()->route('vehicle.show', $vehicle->id)
                         ->with('success', 'Vehicle added successfully!');
    }

    // Display the form for editing a specific vehicle
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $customers = Customer::all(); // Get all customers
        return view('vehicle.edit', compact('vehicle', 'customers'));
    }

    // Update the specified vehicle in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:15',
            'engine_code' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:9999',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Image validation
        ]);

        $vehicle = Vehicle::findOrFail($id);

        // Handle image upload
        $imagePath = $vehicle->image;
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($vehicle->image) {
                Storage::disk('public')->delete($vehicle->image);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('vehicle_images', 'public');
        }

        // Update the vehicle data
        $vehicle->update([
            'license_plate' => $request->license_plate,
            'type' => $request->vehicle_type,
            'color' => $request->color,
            'production_year' => $request->year,
            'engine_code' => $request->engine_code,
            'customer_id' => $request->customer_id,
            'image' => $imagePath, // Update image path
        ]);

        // Redirect to the show page of the vehicle with a success message
        return redirect()->route('vehicle.show', $vehicle->id)
                         ->with('success', 'Vehicle updated successfully!');
    }

    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicle.show', compact('vehicle'));
    }    

    // Soft delete a specific vehicle
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        if ($vehicle->image) {
            // Delete the image file if exists
            Storage::disk('public')->delete($vehicle->image);
        }
        $vehicle->delete();

        // Redirect back with success message
        return redirect()->route('customer.show', $vehicle->customer_id)
                         ->with('success', 'Vehicle deleted successfully!');
    }
}