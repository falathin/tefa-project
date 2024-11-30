@extends('layouts.app') <!-- Pastikan ini sesuai dengan layout utama Anda -->

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg animate__animated animate__fadeIn">
        <div class="card-header bg-warning text-white">
            <h5>Edit Data Kendaraan Milik: {{ $vehicle->customer->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('vehicle.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Gunakan metode PUT untuk update -->

                <!-- Vehicle Type and License Plate -->
                <div class="row mb-4 animate__animated animate__fadeIn animate__delay-1s">
                    <div class="col-md-6">
                        <label for="vehicle_type" class="form-label">Jenis Kendaraan</label>
                        <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type', $vehicle->vehicle_type) }}" required>
                        @error('vehicle_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="license_plate" class="form-label">Nomor Plat Kendaraan</label>
                        <input type="text" class="form-control @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" required>
                        @error('license_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>                   

                <!-- Engine Code, Color, and Year -->
                <div class="row mb-4 animate__animated animate__fadeIn animate__delay-2s">
                    <div class="col-md-6">
                        <label for="engine_code" class="form-label">Kode Mesin</label>
                        <input type="text" class="form-control @error('engine_code') is-invalid @enderror" id="engine_code" name="engine_code" value="{{ old('engine_code', $vehicle->engine_code) }}">
                        @error('engine_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="color" class="form-label">Warna</label>
                        <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $vehicle->color) }}">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Year and Image Upload -->
                <div class="row mb-4 animate__animated animate__fadeIn animate__delay-3s">
                    <div class="col-md-6">
                        <label for="year" class="form-label">Tahun Kendaraan</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', $vehicle->production_year) }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label">Foto Kendaraan (Opsional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($vehicle->image)
                            <small class="text-muted d-block mt-2">Gambar saat ini:</small>
                            <img src="{{ asset('storage/' . $vehicle->image) }}" alt="Gambar Kendaraan" class="img-fluid mt-2" style="max-height: 150px;">
                        @endif
                    </div>
                </div>

                <!-- Submit Button Section -->
                <div class="d-flex justify-content-end animate__animated animate__fadeIn animate__delay-4s">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn btn-outline-secondary ms-2">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection