@extends('layouts.app') <!-- Ensure this matches your main layout -->

@section('content')
    <div class="container mt-5">

        <!-- Add animation class to the container or card element -->
        <div class="card shadow-lg animate__animated animate__fadeIn">
            <div class="card-header bg-primary text-white">
                <h5>Tambah Data Kendaraan Milik : {{ $customer->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vehicle.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden field for Customer ID -->
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="jurusan" value="{{ Auth::user()->jurusan }}">

                    <!-- Vehicle Type and License Plate -->
                    <div class="row mb-4 animate__animated animate__fadeIn animate__delay-1s">
                        @if (Auth::user()->jurusan == 'TKRO')
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Merk</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                    id="brand" name="brand" value="{{ old('brand') }}" placeholder="Daihatsu">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="vehicle_type" class="form-label">Tipe Kendaraan</label>
                                <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror"
                                    id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" required
                                    placeholder="Xenia 1.5 A/T">
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @elseif (Auth::user()->jurusan == 'TSM')
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Merk</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                    id="brand" name="brand" value="{{ old('brand') }}" placeholder="Honda">
                                     @error('brand') <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="vehicle_type" class="form-label">Tipe Kendaraan</label>
                                <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror"
                                    id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" required
                                    placeholder= "CBR 250RR">
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                    @endif
            </div>

            <!-- Engine Code, Color, and Year with animation -->
            <div class="row mb-4 animate__animated animate__fadeIn animate__delay-2s">
                <div class="col-md-6">
                    <label for="engine_code" class="form-label">Kode Mesin</label>
                    <input type="text" class="form-control @error('engine_code') is-invalid @enderror" id="engine_code"
                        name="engine_code" value="{{ old('engine_code') }}">
                    @error('engine_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="color" class="form-label">Warna</label>
                    <input type="text" class="form-control @error('color') is-invalid @enderror" id="color"
                        name="color" value="{{ old('color') }}">
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Year and Image Upload with animation -->
            <div class="row mb-4 animate__animated animate__fadeIn animate__delay-3s">
                <div class="col-md-6">
                    <label for="year" class="form-label">Tahun Kendaraan</label>
                    <input type="number" class="form-control @error('year') is-invalid @enderror" id="year"
                        name="year" value="{{ old('year') }}">
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="license_plate" class="form-label">Nomor Plat Kendaraan</label>
                    <input type="text" class="form-control @error('license_plate') is-invalid @enderror"
                        id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required>
                    @error('license_plate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Year and Image Upload with animation -->
            <div class="row mb-4 animate__animated animate__fadeIn animate__delay-3s">
                <div class="col-md-12">
                    <label for="image" class="form-label">Foto Kendaraan (Opsional)</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                        name="image">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Button Section -->
            <div class="d-flex justify-content-end animate__animated animate__fadeIn animate__delay-4s">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-outline-secondary ms-2">Kembali</a>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection