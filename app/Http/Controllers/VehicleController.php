<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function create($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        if (! Gate::allows('isSameJurusan', [$customer])) {
            abort(403, 'Data tidak ditemukan!!');
        }
        return view('vehicle.create', compact('customer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles',
            'vehicle_type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'engine_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'customer_id' => 'required|exists:customers,id',
            'jurusan' => 'required'
        ], [
            'license_plate.required' => 'Plat nomor kendaraan wajib diisi.',
            'license_plate.unique' => 'Plat nomor kendaraan sudah terdaftar.',
            'vehicle_type.required' => 'Nama kendaraan wajib diisi.',
            'brand.required' => 'Merek kendaraan wajib diisi.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicle_images', 'public');
        }

        Vehicle::create([
            'license_plate' => $request->license_plate,
            'vehicle_type' => $request->vehicle_type,
            'brand' => $request->brand,
            'engine_code' => $request->engine_code,
            'color' => $request->color,
            'production_year' => $request->year,
            'image' => $imagePath,
            'customer_id' => $request->customer_id,
            'jurusan' => $request->jurusan
        ]);

        return redirect()->route('customer.show', $request->customer_id)
            ->with('success', 'Data kendaraan berhasil ditambahkan!');
    }

    public function show($id, Request $request)
    {
        $vehicle = Vehicle::find($id);
        if (! Gate::allows('isSameJurusan', [$vehicle])) {
            abort(403, 'Data tidak ditemukan!!');
        }
        if (! Gate::allows('isAdminOrEngineer') && ! Gate::allows('isKasir')) {
            abort(403, 'Butuh level Admin & Kasir');
        }
        $vehicle = Vehicle::findOrFail($id);
        
        $services = Service::where('vehicle_id', $id)
                            ->when($request->search, function($query) use ($request) {
                                return $query->where('service_type', 'like', '%' . $request->search . '%');
                            })
                            ->orderBy('created_at', 'desc')
                            ->paginate(2);
    
        return view('vehicle.show', compact('vehicle', 'services'));
    }    

    public function edit($id)
    {
        $vehicleGate = Vehicle::find($id);
        if (! Gate::allows('isSameJurusan', [$vehicleGate])) {
            abort(403, 'Data tidak ditemukan!!');
        }
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicle.edit', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate,' . $vehicle->id,
            'vehicle_type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'engine_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'license_plate.required' => 'Plat nomor kendaraan wajib diisi.',
            'license_plate.unique' => 'Plat nomor kendaraan sudah terdaftar.',
            'vehicle_type.required' => 'Nama kendaraan wajib diisi.',
            'brand.required' => 'Merek kendaraan wajib diisi.',
        ]);

        if ($request->hasFile('image')) {
            if ($vehicle->image) {
                Storage::disk('public')->delete($vehicle->image);
            }
            $vehicle->image = $request->file('image')->store('vehicle_images', 'public');
        }

        $vehicle->update([
            'license_plate' => $request->license_plate,
            'vehicle_type' => $request->vehicle_type,
            'brand' => $request->brand,
            'engine_code' => $request->engine_code,
            'color' => $request->color,
            'production_year' => $request->year,
        ]);

        return redirect()->route('customer.show', $vehicle->customer_id)
            ->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
        }

        $vehicle->delete();

        return redirect()->route('customer.show', $vehicle->customer_id)
            ->with('success', 'Data kendaraan berhasil dihapus!');
    }
}