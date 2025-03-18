<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // if (! Gate::allows('isAdmin') && ! Gate::allows('isKasir')) {
        //     abort(403, 'Butuh level Admin & Kasir');
        // }
        if (Gate::allows('isBendahara')) {
            abort(403, 'Butuh level Admin | Kasir | Service advisor');
        }
        
        $jurusan = Auth::user()->jurusan;
        $searchTerm = $request->input('search');
        $deletedSearch = $request->input('deletedSearch');
        
        $customers = Customer::when($searchTerm, function ($query, $searchTerm) {
                return $query->where('name', 'like', '%' . $searchTerm . '%')
                             ->orWhere('contact', 'like', '%' . $searchTerm . '%')
                             ->orWhere('address', 'like', '%' . $searchTerm . '%');
            })
            ->where('jurusan', 'like', $jurusan)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        
        $deletedCustomers = Customer::onlyTrashed()
            ->when($deletedSearch, function ($query, $deletedSearch) {
                return $query->where('name', 'like', '%' . $deletedSearch . '%')
                             ->orWhere('contact', 'like', '%' . $deletedSearch . '%')
                             ->orWhere('address', 'like', '%' . $deletedSearch . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        
        $noData = $customers->isEmpty() && $deletedCustomers->isEmpty();
        
        return view('customer.index', compact('customers', 'deletedCustomers', 'noData', 'searchTerm', 'deletedSearch'));
    }     

    public function create()
    {
        // Admin & kasir
        if (Gate::allows('isBendahara')) {
            abort(403, 'Butuh level Admin | Kasir | Bendahara');
        }
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                // Validasi: jika diisi, nomor harus diawali dengan 08 dan hanya mengandung angka, spasi, tanda kurung, dan strip.
                'contact' => 'nullable|regex:/^08[0-9\-\s()]*$/|max:255',
                'address' => 'nullable|string',
                'vehicles.*.vehicle_type' => 'string|max:255',
                'vehicles.*.brand' => 'string|max:255',
                'vehicles.*.license_plate' => 'nullable|string|max:255|unique:vehicles,license_plate',
                'vehicles.*.color' => 'nullable|string|max:255',
                'vehicles.*.production_year' => 'nullable|integer|lte:' . Carbon::now()->year,
                'vehicles.*.engine_code' => 'nullable|string|max:255',
                'vehicles.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'jurusan' => 'required'
            ],
            [
                'name.required' => 'Nama pelanggan wajib diisi.',
                'name.string' => 'Nama pelanggan harus berupa teks.',
                'name.max' => 'Nama pelanggan tidak boleh lebih dari 255 karakter.',
                'contact.regex' => 'Nomor telepon harus diawali dengan 08 dan hanya mengandung angka, spasi, tanda kurung, dan strip.',
                'contact.max' => 'Nomor telepon tidak boleh lebih dari 255 karakter.',
                'address.string' => 'Alamat harus berupa teks.',
                'vehicles.*.vehicle_type.string' => 'Tipe kendaraan harus berupa teks.',
                'vehicles.*.vehicle_type.max' => 'Tipe kendaraan tidak boleh lebih dari 255 karakter.',
                'vehicles.*.brand.string' => 'Merk kendaraan harus berupa teks.',
                'vehicles.*.brand.max' => 'Merk kendaraan tidak boleh lebih dari 255 karakter.',
                'vehicles.*.license_plate.unique' => 'Nomor plat sudah digunakan.',
                'vehicles.*.license_plate.max' => 'Nomor plat tidak boleh lebih dari 255 karakter.',
                'vehicles.*.color.string' => 'Warna harus berupa teks.',
                'vehicles.*.color.max' => 'Warna tidak boleh lebih dari 255 karakter.',
                'vehicles.*.production_year.integer' => 'Tahun produksi harus berupa angka.',
                'vehicles.*.production_year.lte' => 'Tahun produksi tidak boleh melebihi tahun sekarang.',
                'vehicles.*.engine_code.string' => 'Kode mesin harus berupa teks.',
                'vehicles.*.engine_code.max' => 'Kode mesin tidak boleh lebih dari 255 karakter.',
                'vehicles.*.image.image' => 'File harus berupa gambar.',
                'vehicles.*.image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'vehicles.*.image.max' => 'Ukuran gambar tidak boleh melebihi 2MB.',
                'jurusan.required' => 'Jurusan wajib diisi.'
            ]
        );

        $customer = Customer::create($request->only(['name', 'contact', 'address', 'jurusan']));

        if ($request->has('vehicles')) {
            foreach ($request->vehicles as $vehicle) {
                if (!empty($vehicle['vehicle_type']) || !empty($vehicle['license_plate'])) {
                    $vehicle['customer_id'] = $customer->id;
                    if (isset($vehicle['image'])) {
                        $vehicle['image'] = $vehicle['image']->store('vehicle_images', 'public');
                    }
                    $vehicle['jurusan'] = $customer->jurusan;
                    Vehicle::create($vehicle);
                }
            }
        }

        return redirect()->route('customer.show', $customer->id)
                        ->with('success', 'Customer dan kendaraan berhasil dibuat!');
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        if (! Gate::allows('isSameJurusan', [$customer])) {
            abort(403, 'Data tidak ditemukan!');
        }
        // Admin & kasir
        // if (! Gate::allows('isAdmin') && ! Gate::allows('isKasir')) {
        //     abort(403, 'Butuh level Admin & Kasir');
        // }
        if (Gate::allows('isBendahara')) {
            abort(403, 'Butuh level Admin | Kasir | Service advisor');
        }

        $customer = Customer::with('vehicles')->findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                // Validasi nomor telepon harus diawali dengan 08
                'contact' => 'nullable|regex:/^08[0-9\-\s()]*$/|max:255',
                'address' => 'nullable|string',
            ],
            [
                'name.required' => 'Nama pelanggan wajib diisi.',
                'name.string' => 'Nama pelanggan harus berupa teks.',
                'name.max' => 'Nama pelanggan tidak boleh lebih dari 255 karakter.',
                'contact.regex' => 'Nomor telepon harus diawali dengan 08 dan hanya mengandung angka, spasi, tanda kurung, dan strip.',
                'contact.max' => 'Nomor telepon tidak boleh lebih dari 255 karakter.',
                'address.string' => 'Alamat harus berupa teks.'
            ]
        );

        $customer = Customer::findOrFail($id);
        $customer->update($request->only(['name', 'contact', 'address']));

        return redirect()->route('customer.show', $customer->id)
                        ->with('success', 'Customer berhasil diperbarui!');
    }

    public function show(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (! Gate::allows('isSameJurusan', [$customer])) {
            abort(403, 'Data tidak ditemukan!');
        }

        // Admin & kasir
        // if (! Gate::allows('isAdmin') && ! Gate::allows('isKasir')) {
        //     abort(403, 'Butuh level Admin & Kasir');
        // }
        if (Gate::allows('isBendahara')) {
            abort(403, 'Butuh level Admin | Kasir | Bendahara');
        }

        $customer = Customer::findOrFail($id);

        $customer->contact = $customer->contact ?: 'Tidak ada data kontak';
        $customer->address = $customer->address ?: 'Tidak ada data alamat';

        $searchTerm = $request->input('search');
        
        $vehicles = $customer->vehicles()
                             ->when($searchTerm, function ($query, $searchTerm) {
                                 return $query->where('license_plate', 'like', '%' . $searchTerm . '%')
                                              ->orWhere('vehicle_type', 'like', '%' . $searchTerm . '%');
                             })
                             ->paginate(3);

        return view('customer.show', compact('customer', 'vehicles', 'searchTerm'));
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->delete();
            session(['deleted_customer' => $customer]);
            return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus.');
        }
        return redirect()->route('customer.index')->with('error', 'Customer tidak ditemukan.');
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
        $customer = Customer::withTrashed()->find($id);
        if ($customer) {
            $customer->forceDelete();
            return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus secara permanen.');
        }
        return redirect()->route('customer.index')->with('error', 'Customer tidak ditemukan.');
    }

    public function forceDeleteAll()
    {
        $deletedCustomers = Customer::onlyTrashed()->get();
        if ($deletedCustomers->isEmpty()) {
            return redirect()->route('customer.index')->with('error', 'Tidak ada pelanggan yang dapat dihapus secara permanen.');
        }

        Customer::onlyTrashed()->forceDelete();
        return redirect()->route('customer.index')->with('success', 'Semua pelanggan yang dihapus berhasil dihapus secara permanen.');
    }
}