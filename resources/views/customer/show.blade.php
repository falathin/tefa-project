@extends('layouts.app')

@section('content')

    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" style="animation-delay: 0.5s;" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Customer Info -->
                <h5 class="card-title mb-4 text-center text-primary fw-bold animate__animated animate__fadeInUp" style="animation-delay: 1s;">
                    Data Pelanggan: {{ $customer->name }}
                </h5>

                <form>
                    <div class="row mb-3">
                        <label for="namaPelanggan" class="col-sm-3 col-form-label">Nama Pelanggan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control animate__animated animate__fadeInUp" id="namaPelanggan" value="{{ $customer->name }}" disabled style="animation-delay: 1.3s;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="noHP" class="col-sm-3 col-form-label">No HP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control animate__animated animate__fadeInUp" id="noHP" value="{{ $customer->contact }}" disabled style="animation-delay: 1.6s;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea class="form-control animate__animated animate__fadeInUp" id="alamat" rows="2" disabled style="animation-delay: 1.9s;">{{ $customer->address }}</textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-lg px-4 py-2 animate__animated animate__fadeInUp" style="animation-delay: 2.2s;">
                            <i class="bi bi-arrow-left-circle"></i> Kembali
                        </a>
                    </div>
                </form>

                <hr class="my-4 animate__animated animate__fadeInUp" style="animation-delay: 2.5s;">

                <!-- Vehicle Section -->
                <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInUp" style="animation-delay: 2.8s;">
                    <h6 class="mb-0 text-success">Kendaraan Terdaftar</h6>
                    <a href="{{ route('vehicle.create', $customer->id) }}" class="btn btn-success rounded-pill px-4 py-2 animate__animated animate__fadeInUp" style="animation-delay: 3.1s;">
                        <i class="bi bi-wrench"></i> Tambah Kendaraan
                    </a>                    
                </div>

                <!-- Search Form -->
                <form method="GET" action="{{ route('customer.show', $customer->id) }}" class="mb-4 animate__animated animate__fadeInUp" style="animation-delay: 3.4s;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan No Pol atau Jenis Kendaraan" value="{{ $searchTerm }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        @if ($searchTerm)
                            <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
                @php
                $sortedVehicles = $vehicles->sortByDesc('created_at');
            @endphp
            
            <div class="table-responsive mt-3">
                @if ($sortedVehicles->isEmpty())
                    <div class="alert alert-warning text-center animate__animated animate__fadeInUp" style="animation-delay: 3.7s;">
                        <strong>Belum ada kendaraan yang dimasukkan!</strong>
                    </div>
                @else
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">NO POL</th>
                                <th scope="col">Jenis Kendaraan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sortedVehicles as $vehicle)
                                <tr class="text-center animate__animated animate__fadeInUp" style="transition: transform 0.3s ease-in-out; animation-delay: {{ 3.7 + $loop->index * 0.3 }}s;">
                                    <td>{{ $vehicle->license_plate }}</td>
                                    <td>{{ $vehicle->vehicle_type }}</td>
                                    <td>
                                        <!-- View Vehicle -->
                                        <a href="{{ route('vehicle.show', $vehicle->id) }}" class="btn btn-info rounded-pill px-4 py-2 animate__animated animate__fadeInUp" style="animation-delay: {{ 4 + $loop->index * 0.3 }}s;">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
            
                                        <!-- Edit Vehicle -->
                                        <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="btn btn-warning rounded-pill px-4 py-2 animate__animated animate__fadeInUp" style="animation-delay: {{ 4.3 + $loop->index * 0.3 }}s;">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
            
                                        <!-- Delete Vehicle -->
                                        <form action="{{ route('vehicle.destroy', $vehicle->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 animate__animated animate__fadeInUp" style="animation-delay: {{ 4.6 + $loop->index * 0.3 }}s;">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
            
                                        <a href="{{ route('service.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-primary rounded-pill px-4 py-2 animate__animated animate__fadeInUp" style="animation-delay: {{ 4.9 + $loop->index * 0.3 }}s;">
                                            <i class="bi bi-gear"></i> Tambah Service
                                        </a>                                            
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-3 animate__animated animate__fadeInUp" style="animation-delay: 5.2s;">
                        {{ $vehicles->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5') }}
                    </div>
                @endif
            </div>
            
            </div>
        </div>
    </div>
@endsection