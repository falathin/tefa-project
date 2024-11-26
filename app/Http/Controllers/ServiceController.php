<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Menampilkan halaman index (daftar service)
    public function index()
    {
        $services = Service::all();
        return view('service.index', compact('services'));
    }

    // Menampilkan halaman create (form input service)
    public function create($vehicle_id)
    {
        $vehicle = Vehicle::findOrFail($vehicle_id);
        return view('service.create', compact('vehicle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|regex:/^\d+$/|max:255', // Only digits allowed for contact
            'address' => 'nullable|string|max:255', // Allow address to be nullable
        ], [
            'contact.regex' => 'No HP harus berupa angka tanpa huruf atau karakter lain.',
            'address.nullable' => 'Alamat bersifat opsional, tetapi jika kosong, akan ada pesan "gada data alamat".'
        ]);
    
        // Create the new customer
        $customer = Customer::create($request->all());
    
        // Redirect to the show page of the newly created customer
        return redirect()->route('customer.show', $customer->id)
                         ->with('success', 'Customer created successfully!');
    }
    

    // Menampilkan halaman edit service
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('service.edit', compact('service'));
    }

    // Update data service
    public function update(Request $request, $id)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'complaint' => 'required|string',
            'service_fee' => 'required|numeric',
            'service_date' => 'required|date',
            'total_cost' => 'required|numeric',
            'payment_received' => 'required|numeric',
            'change' => 'required|numeric',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('service.index')->with('success', 'Service updated successfully');
    }

    // Menampilkan detail service
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('service.show', compact('service'));
    }

    // Menghapus service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('service.index')->with('success', 'Service deleted successfully');
    }
}