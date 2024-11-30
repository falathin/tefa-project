<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CustomerController extends Controller
{
    // Menampilkan daftar pelanggan dengan paginasi dan pencarian
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $deletedSearch = $request->input('deletedSearch');
    
        // Paginate pelanggan aktif
        $customers = Customer::when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name', 'like', '%' . $searchTerm . '%')
                         ->orWhere('contact', 'like', '%' . $searchTerm . '%')
                         ->orWhere('address', 'like', '%' . $searchTerm . '%');
        })->paginate(5);
    
        // Paginate pelanggan yang dihapus
        $deletedCustomers = Customer::onlyTrashed()
            ->when($deletedSearch, function ($query, $deletedSearch) {
                return $query->where('name', 'like', '%' . $deletedSearch . '%')
                             ->orWhere('contact', 'like', '%' . $deletedSearch . '%')
                             ->orWhere('address', 'like', '%' . $deletedSearch . '%');
            })
            ->paginate(5);
    
        $noData = $customers->isEmpty() && $deletedCustomers->isEmpty();
    
        return view('customer.index', compact('customers', 'deletedCustomers', 'noData', 'searchTerm', 'deletedSearch'));
    }

    // Menampilkan halaman create (form input customer)
    public function create()
    {
        return view('customer.create');
    }

    // Menyimpan data pelanggan
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|regex:/^[0-9+\-()\s]*$/|max:255', // Updated regex to allow numbers and basic symbols
            'address' => 'nullable|string',
            'vehicles.*.vehicle_type' => 'required|string|max:255',
            'vehicles.*.license_plate' => 'required|string|max:255|unique:vehicles,license_plate',
            'vehicles.*.color' => 'nullable|string|max:255',
            'vehicles.*.production_year' => 'nullable|integer|lte:' . Carbon::now()->year, // Validate production year to not exceed current year
            'vehicles.*.engine_code' => 'nullable|string|max:255',
            'vehicles.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Simpan data pelanggan
        $customer = Customer::create($request->only(['name', 'contact', 'address']));

        // Simpan data kendaraan
        foreach ($request->vehicles as $vehicle) {
            $vehicle['customer_id'] = $customer->id;
            if (isset($vehicle['image'])) {
                $vehicle['image'] = $vehicle['image']->store('vehicle_images', 'public');
            }
            Vehicle::create($vehicle);
        }

        return redirect()->route('customer.show', $customer->id)
                         ->with('success', 'Customer and vehicles created successfully!');
    }

    // Menampilkan halaman edit customer
    public function edit($id)
    {
        $customer = Customer::with('vehicles')->findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    // Update data pelanggan
    public function update(Request $request, $id)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|regex:/^[0-9+\-()\s]*$/|max:255', // Updated regex to allow numbers and basic symbols
            'address' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->only(['name', 'contact', 'address']));  // Only update customer fields

        // Return back to the customer details page
        return redirect()->route('customer.show', $customer->id)
                        ->with('success', 'Customer updated successfully!');
    }
    
    // Menampilkan detail customer dan kendaraan
    public function show(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        // Fallback values for contact and address fields   
        $customer->contact = $customer->contact ?: 'Tidak ada data kontak';
        $customer->address = $customer->address ?: 'Tidak ada data alamat';

        // Retrieve the search term from the request
        $searchTerm = $request->input('search');
        
        // Filter the vehicles based on the search term
        $vehicles = $customer->vehicles()
                             ->when($searchTerm, function ($query, $searchTerm) {
                                 return $query->where('license_plate', 'like', '%' . $searchTerm . '%')
                                              ->orWhere('vehicle_type', 'like', '%' . $searchTerm . '%');
                             })
                             ->paginate(3);

        return view('customer.show', compact('customer', 'vehicles', 'searchTerm'));
    }

    // Menghapus customer secara soft delete
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->delete(); // Soft delete the customer
            session(['deleted_customer' => $customer]); // Store the deleted customer for undo
            return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
        }
        return redirect()->route('customer.index')->with('error', 'Customer not found.');
    }

    // Merestore customer yang dihapus
    public function restore($id)
    {
        $customer = Customer::withTrashed()->find($id);
        if ($customer) {
            $customer->restore();
            return redirect()->route('customer.index')->with('success', 'Customer berhasil di-restore.');
        } else {
            return redirect()->route('customer.index')->with('error', 'Customer tidak ditemukan.');
        }
    }

    // Menghapus customer secara permanen
    public function forceDelete($id)
    {
        $customer = Customer::withTrashed()->find($id);  // Make sure to get the trashed customer
        if ($customer) {
            $customer->forceDelete(); // Permanently delete the customer
            return redirect()->route('customer.index')->with('success', 'Customer deleted permanently.');
        }
        return redirect()->route('customer.index')->with('error', 'Customer not found.');
    }

    // Menghapus semua customer yang dihapus secara permanen
    public function forceDeleteAll()
    {
        $deletedCustomers = Customer::onlyTrashed()->get();
        if ($deletedCustomers->isEmpty()) {
            return redirect()->route('customer.index')->with('error', 'Tidak ada pelanggan yang dapat dihapus secara permanen.');
        }

        Customer::onlyTrashed()->forceDelete(); // Hapus semua data pelanggan yang dihapus
        return redirect()->route('customer.index')->with('success', 'Semua pelanggan yang dihapus berhasil dihapus secara permanen.');
    }
}