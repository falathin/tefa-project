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
        $spareparts = Sparepart::where('jumlah', '>', 0)->get();
        return view('vehicle.create_with_service', compact('customer', 'spareparts'));
    }

    public function storeWithService(Request $request)
    {
        // Validasi input form
        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'engine_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'customer_id' => 'required|exists:customers,id',
            'complaint' => 'nullable|string|max:255',
            'current_mileage' => 'nullable|integer|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'payment_received' => 'nullable|numeric|min:0',
            'change' => 'nullable|numeric|min:0',
            'service_type' => 'nullable|string|max:255',
            'spareparts' => 'nullable|array', // Spareparts should be an array
            'spareparts.*' => 'exists:spareparts,id|numeric|min:1', // Validate spareparts IDs
        ]);
    
        // Menyimpan gambar kendaraan jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicle_images', 'public');
        }
    
        // Menyimpan data kendaraan
        $vehicle = Vehicle::create([
            'license_plate' => $validated['license_plate'],
            'type' => $validated['vehicle_type'],
            'color' => $validated['color'],
            'production_year' => $validated['year'],
            'engine_code' => $validated['engine_code'],
            'customer_id' => $validated['customer_id'], // ID pelanggan yang tervalidasi
            'image' => $imagePath,
        ]);
    
        // Menyimpan data service
        $service = Service::create([
            'vehicle_id' => $vehicle->id,
            'complaint' => $validated['complaint'],
            'current_mileage' => $validated['current_mileage'],
            'service_fee' => $validated['service_fee'],
            'service_date' => now(), // Tanggal service
            'total_cost' => $validated['total_cost'],
            'payment_received' => $validated['payment_received'],
            'change' => $validated['change'],
            'service_type' => $validated['service_type'],
        ]);
    
        // Memproses spare parts jika ada
        if (!empty($validated['spareparts'])) {
            foreach ($validated['spareparts'] as $sparepartId => $quantity) {
                $sparepart = Sparepart::find($sparepartId);
                if ($sparepart && $sparepart->jumlah >= $quantity) {
                    $sparepart->decrement('jumlah', $quantity); // Mengurangi jumlah sparepart
    
                    // Menyimpan data sparepart yang digunakan dalam service
                    ServiceSparepart::create([
                        'service_id' => $service->id,
                        'sparepart_id' => $sparepartId,
                        'jumlah' => $quantity,
                        'harga_satuan' => $sparepart->harga_jual,
                        'subtotal' => $sparepart->harga_jual * $quantity,
                    ]);
                } else {
                    return redirect()->back()->withErrors([
                        'spareparts' => "Stok sparepart dengan ID {$sparepartId} tidak mencukupi."
                    ]);
                }
            }
        }
    
        // Mengarahkan ke halaman daftar kendaraan dengan pesan sukses
        return redirect()->route('vehicles.index')->with('success', 'Vehicle and service added successfully!');
    }
    

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.show', compact('customer'));
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
        }

        $vehicle->delete();

        return redirect()->route('customer.show', $vehicle->customer_id)
                         ->with('success', 'Vehicle deleted successfully!');
    }
}