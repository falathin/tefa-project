@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3 animate__animated animate__fadeIn">
            <div class="card-body">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" style="animation-delay: 0.5s;" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Customer Info -->
                <h5 class="card-title mb-4 text-center text-primary fw-bold animate__animated animate__fadeIn" style="animation-delay: 0.7s;">{{ $customer->name }}'s Data</h5>
                
                <form>
                    <div class="row mb-3">
                        <label for="namaPelanggan" class="col-sm-3 col-form-label">Nama Pelanggan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control animate__animated animate__fadeIn" id="namaPelanggan" value="{{ $customer->name }}" disabled style="animation-delay: 1s;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="noHP" class="col-sm-3 col-form-label">No HP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control animate__animated animate__fadeIn" id="noHP" value="{{ $customer->contact }}" disabled style="animation-delay: 1.3s;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea class="form-control animate__animated animate__fadeIn" id="alamat" rows="2" disabled style="animation-delay: 1.6s;">{{ $customer->address }}</textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-lg px-4 py-2 animate__animated animate__fadeIn" style="animation-delay: 1.9s;">
                            <i class="bi bi-arrow-left-circle"></i> Kembali
                        </a>
                    </div>
                </form>

                <hr class="my-4 animate__animated animate__fadeIn" style="animation-delay: 2.2s;">

                <!-- Vehicle Section -->
                <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn" style="animation-delay: 2.5s;">
                    <h6 class="mb-0 text-success">Kendaraan Terdaftar</h6>
                    <a href="{{ route('vehicle.create_with_service', $customer->id) }}" class="btn btn-success rounded-pill px-4 py-2 animate__animated animate__fadeIn" style="animation-delay: 3.3s;">
                        <i class="bi bi-wrench"></i> Tambah Kendaraan Dan Servis
                    </a>                    
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">NO POL</th>
                                <th scope="col">Jenis Kendaraan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer->vehicles as $vehicle)
                                <tr class="text-center animate__animated animate__fadeIn" style="transition: transform 0.3s ease-in-out; animation-delay: {{ 3 + $loop->index * 0.3 }}s;">
                                    <td>{{ $vehicle->license_plate }}</td>
                                    <td>{{ $vehicle->vehicle_type }}</td>
                                    <td>
                                        <a href="{{ route('service.create', $vehicle->id) }}" class="btn btn-primary rounded-pill px-4 py-2 animate__animated animate__fadeIn" style="animation-delay: {{ 3.3 + $loop->index * 0.3 }}s;">
                                            <i class="bi bi-wrench"></i> Tambah Service
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection