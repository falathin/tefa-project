<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
    
        $customers = Customer::when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name', 'like', '%' . $searchTerm . '%')
                         ->orWhere('contact', 'like', '%' . $searchTerm . '%')
                         ->orWhere('address', 'like', '%' . $searchTerm . '%');
        })->get(); // Pelanggan aktif
    
        $deletedCustomers = Customer::onlyTrashed()->get(); // Pelanggan yang dihapus
    
        $noData = $customers->isEmpty() && $deletedCustomers->isEmpty();
    
        return view('customer.index', compact('customers', 'deletedCustomers', 'noData', 'searchTerm'));
    }
    

    // Menampilkan halaman create (form input customer)
    public function create()
    {
        return view('customer.create');
    }

    // Menyimpan data pelanggan
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|regex:/^[0-9+\-()\s]*$/|max:255', // Updated regex to allow numbers and basic symbols
            'address' => 'nullable|string',
        ]);

        // Create the new customer
        $customer = Customer::create($request->all());

        // Redirect to the show page of the newly created customer with a success message
        return redirect()->route('customer.show', $customer->id)
                        ->with('success', 'Customer created successfully!');
    }

    // Menampilkan halaman edit customer
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|regex:/^[0-9]*$/|max:255', // Validasi untuk contact
            'address' => 'nullable|string',
        ]);
    
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
    
        // Setelah update, arahkan ke halaman show dan tampilkan alert sukses
        return redirect()->route('customer.show', $customer->id)
                         ->with('success', 'Customer updated successfully');
    }
    
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
    
        // Fallback values for contact and address fields
        $customer->contact = $customer->contact ?: 'Tidak ada data kontak';
        $customer->address = $customer->address ?: 'Tidak ada data alamat';
    
        return view('customer.show', compact('customer'));
    }

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

    public function forceDelete($id)
    {
        $customer = Customer::withTrashed()->find($id);  // Make sure to get the trashed customer
        if ($customer) {
            $customer->forceDelete(); // Permanently delete the customer
            return redirect()->route('customer.index')->with('success', 'Customer deleted permanently.');
        }
        return redirect()->route('customer.index')->with('error', 'Customer not found.');
    }    

}