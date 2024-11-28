@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3 animate__animated animate__fadeIn">
            <div class="card-body">
                <h5 class="card-title text-center text-primary fw-bold mb-4">
                    Edit Data Kendaraan: {{ $vehicle->license_plate }}
                </h5>

                <form action="{{ route('vehicle.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- No Polisi -->
                    <div class="mb-3">
                        <label for="license_plate" class="form-label">No Polisi</label>
                        <input type="text" class="form-control @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" required>
                        @error('license_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Kendaraan -->
                    <div class="mb-3">
                        <label for="vehicle_type" class="form-label">Jenis Kendaraan</label>
                        <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type', $vehicle->vehicle_type) }}" required>
                        @error('vehicle_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kode Mesin -->
                    <div class="mb-3">
                        <label for="engine_code" class="form-label">Kode Mesin</label>
                        <input type="text" class="form-control @error('engine_code') is-invalid @enderror" id="engine_code" name="engine_code" value="{{ old('engine_code', $vehicle->engine_code) }}">
                        @error('engine_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Warna -->
                    <div class="mb-3">
                        <label for="color" class="form-label">Warna</label>
                        <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $vehicle->color) }}">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tahun Produksi -->
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun Produksi</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', $vehicle->production_year) }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gambar Kendaraan -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Kendaraan</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">Simpan Perubahan</button>
                        <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 ms-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection