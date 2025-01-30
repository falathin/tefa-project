@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Card for Form -->
    <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
            <h3>Formulir Data Pelanggan & Kendaraan</h3>
        </div>
        <div class="card-body">
            <!-- Form starts here -->
            <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="text" value="{{ Auth::user()->jurusan }}" name="jurusan" id="jurusan" hidden>

                <!-- Customer Data Section -->
                <h4 class="mb-4"><i class="fas fa-user"></i> Data Pelanggan</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" placeholder="Masukkan nama pelanggan" required>
                        @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="contact" name="contact" 
                               value="{{ old('contact') }}" placeholder="Masukkan nomor HP">
                        @error('contact')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat pelanggan">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="mt-4 mb-4"><i class="fas fa-car"></i> Data
                @if (Auth::user()->jurusan == 'TKRO')
                Mobil
                @elseif (Auth::user()->jurusan == 'TSM')
                    Motor
                @endif
                </h4>
                <div id="vehicle-fields">
                    <div class="vehicle-group mb-3" id="vehicle-0">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Merk dan model kendaraan</label>
                                <input type="text" name="vehicles[0][vehicle_type]" class="form-control mb-2" 
                                       value="{{ old('vehicles.0.vehicle_type') }}" 
                                       placeholder=" Contoh : @if (Auth::user()->jurusan == 'TSM')Honda CBR 250RR
                                       @elseif (Auth::user()->jurusan == 'TKRO') Daihatsu Rocky 1.2 M CVT @endif" required>
                                @error('vehicles.0.vehicle_type')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Nomor Plat</label>
                                <input type="text" name="vehicles[0][license_plate]" class="form-control mb-2" 
                                       value="{{ old('vehicles.0.license_plate') }}" placeholder="Masukkan nomor plat" required>
                                @error('vehicles.0.license_plate')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Warna</label>
                                <input type="text" name="vehicles[0][color]" class="form-control mb-2" 
                                       value="{{ old('vehicles.0.color') }}" placeholder="Masukkan warna kendaraan">
                                @error('vehicles.0.color')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tahun Produksi</label>
                                <input type="number" name="vehicles[0][production_year]" class="form-control mb-2" 
                                       value="{{ old('vehicles.0.production_year') }}" placeholder="Masukkan tahun produksi">
                                @error('vehicles.0.production_year')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Kode Mesin</label>
                                <input type="text" name="vehicles[0][engine_code]" class="form-control mb-2" 
                                       value="{{ old('vehicles.0.engine_code') }}" placeholder="Masukkan kode mesin">
                                @error('vehicles.0.engine_code')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Foto Kendaraan</label>
                                <input type="file" name="vehicles[0][image]" class="form-control mb-2">
                                @if(old('vehicles.0.image'))
                                    <img src="{{ asset('storage/' . old('vehicles.0.image')) }}" alt="Preview Image" class="mt-2" width="100">
                                @endif
                                @error('vehicles.0.image')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteVehicle(0)"><i class="fas fa-trash"></i> Hapus Kendaraan</button>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="add-vehicle" class="btn btn-secondary"><i class="fas fa-plus-circle"></i> Tambah Kendaraan</button>
                    <div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{{ route('customer.index') }}" class="btn btn-warning ml-2"><i class="fas fa-arrow-left"></i> Kembali</a> 
                    </div>
                </div>               
            </form>
        </div>
    </div>
</div>

<script>
    let vehicleIndex = 1;
    
    // Add new vehicle fields with slide-up animation
    document.getElementById('add-vehicle').addEventListener('click', function () {
        const vehicleFields = document.getElementById('vehicle-fields');
        const newVehicleGroup = document.createElement('div');
        newVehicleGroup.classList.add('vehicle-group', 'mb-3', 'card', 'shadow-sm', 'p-3');
        newVehicleGroup.id = `vehicle-${vehicleIndex}`;
        
        // Use template literals to dynamically populate the fields
        newVehicleGroup.innerHTML = `
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Jenis Kendaraan</label>
                    <input type="text" name="vehicles[${vehicleIndex}][vehicle_type]" class="form-control mb-2" 
                           value="{{ old('vehicles.${vehicleIndex}.vehicle_type') }}" placeholder="Masukkan jenis kendaraan" required>
                    @error('vehicles.${vehicleIndex}.vehicle_type')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nomor Plat</label>
                    <input type="text" name="vehicles[${vehicleIndex}][license_plate]" class="form-control mb-2" 
                           value="{{ old('vehicles.${vehicleIndex}.license_plate') }}" placeholder="Masukkan nomor plat" required>
                    @error('vehicles.${vehicleIndex}.license_plate')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Warna</label>
                    <input type="text" name="vehicles[${vehicleIndex}][color]" class="form-control mb-2" 
                           value="{{ old('vehicles.${vehicleIndex}.color') }}" placeholder="Masukkan warna kendaraan">
                    @error('vehicles.${vehicleIndex}.color')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tahun Produksi</label>
                    <input type="number" name="vehicles[${vehicleIndex}][production_year]" class="form-control mb-2" 
                           value="{{ old('vehicles.${vehicleIndex}.production_year') }}" placeholder="Masukkan tahun produksi">
                    @error('vehicles.${vehicleIndex}.production_year')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Kode Mesin</label>
                    <input type="text" name="vehicles[${vehicleIndex}][engine_code]" class="form-control mb-2" 
                           value="{{ old('vehicles.${vehicleIndex}.engine_code') }}" placeholder="Masukkan kode mesin">
                    @error('vehicles.${vehicleIndex}.engine_code')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Foto Kendaraan</label>
                    <input type="file" name="vehicles[${vehicleIndex}][image]" class="form-control mb-2">
                    @if(old('vehicles.${vehicleIndex}.image'))
                        <img src="{{ asset('storage/' . old('vehicles.${vehicleIndex}.image')) }}" alt="Preview Image" class="mt-2" width="100">
                    @endif
                    @error('vehicles.${vehicleIndex}.image')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteVehicle(${vehicleIndex})"><i class="fas fa-trash"></i> Hapus Kendaraan</button>
        `;
        
        vehicleFields.appendChild(newVehicleGroup);
        
        newVehicleGroup.classList.add('animate__animated', 'animate__fadeInUp');
        
        vehicleIndex++;
    });
    
    function deleteVehicle(index) {
        const vehicleGroup = document.getElementById(`vehicle-${index}`);
        vehicleGroup.classList.add('animate__animated', 'animate__fadeOutDown');
        
        setTimeout(() => {
            vehicleGroup.remove();
        }, 500); // Duration of animation
    }
</script>
@endsection