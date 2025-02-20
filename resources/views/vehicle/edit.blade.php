@extends('layouts.app') <!-- Ensure this matches your main layout -->

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg animate__animated animate__fadeIn">
            <div class="card-header bg-warning text-white">
                <h5><i class="fas fa-edit"></i> Edit Data Kendaraan Milik : {{ $vehicle->customer->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vehicle.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="customer_id" value="{{ $vehicle->customer_id }}">
                    <input type="hidden" name="jurusan" value="{{ Auth::user()->jurusan }}">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="brand" class="form-label"><i class="fas fa-tag"></i> Merk</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand"
                                name="brand" value="{{ old('brand', $vehicle->brand) }}" placeholder="Masukkan merk kendaraan">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vehicle_type" class="form-label"><i class="fas fa-car-side"></i> Jenis Kendaraan</label>
                            <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror"
                                id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type', $vehicle->vehicle_type) }}" 
                                placeholder="Masukkan jenis kendaraan" required>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="engine_code" class="form-label"><i class="fas fa-cogs"></i> Kode Mesin</label>
                            <input type="text" class="form-control @error('engine_code') is-invalid @enderror"
                                id="engine_code" name="engine_code" value="{{ old('engine_code', $vehicle->engine_code) }}" 
                                placeholder="Masukkan kode mesin">
                            @error('engine_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="form-label"><i class="fas fa-palette"></i> Warna</label>
                            <input type="text" class="form-control @error('color') is-invalid @enderror" id="color"
                                name="color" value="{{ old('color', $vehicle->color) }}" placeholder="Masukkan warna kendaraan">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="year" class="form-label"><i class="fas fa-calendar-alt"></i> Tahun Kendaraan</label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror" id="year"
                                name="year" value="{{ old('year', $vehicle->year) }}" placeholder="Masukkan tahun kendaraan">
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="license_plate" class="form-label"><i class="fas fa-id-card"></i> Nomor Plat Kendaraan</label>
                            <input type="text" class="form-control @error('license_plate') is-invalid @enderror"
                                id="license_plate" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" 
                                placeholder="Masukkan nomor plat kendaraan" required>
                            @error('license_plate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="image" class="form-label"><i class="fas fa-image"></i> Foto Kendaraan (Opsional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection