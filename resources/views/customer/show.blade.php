@extends('layouts.app')

@section('content')
<div class="container mt-5 position-relative">
    <!-- Tombol kembali di pojok kiri atas -->
    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary position-absolute top-0 start-0 m-3">
        <i class="bi bi-arrow-left-circle"></i> Kembali
    </a>

    <div class="card shadow-lg border-0 rounded-3 bg-white">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                    <strong><i class="bi bi-check-circle"></i> Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <h5 class="card-title mb-4 text-center text-primary fw-bold animate__animated animate__fadeInUp">
                <i class="bi bi-person-circle"></i> Data Pelanggan: {{ $customer->name }}
            </h5>

            <form>
                <div class="row mb-3">
                    <label for="namaPelanggan" class="col-sm-3 col-form-label">
                        <i class="bi bi-person"></i> Nama Pelanggan
                    </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaPelanggan" value="{{ $customer->name }}" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="noHP" class="col-sm-3 col-form-label">
                        <i class="bi bi-telephone"></i> No HP
                    </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="noHP" value="{{ $customer->contact }}" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="alamat" class="col-sm-3 col-form-label">
                        <i class="bi bi-geo-alt"></i> Alamat
                    </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="alamat" rows="2" disabled>{{ $customer->address }}</textarea>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-lg px-4 py-2">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                </div>
            </form>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="mb-0 text-success">
                    <i class="bi bi-truck"></i> Kendaraan Terdaftar
                </h6>
                <a href="{{ route('vehicle.create', $customer->id) }}" class="btn btn-success rounded-pill px-4 py-2">
                    <i class="bi bi-wrench"></i> Tambah Kendaraan
                </a>
            </div>

            <form method="GET" action="{{ route('customer.show', $customer->id) }}" class="mb-4">
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
                    <div class="alert alert-warning text-center animate__animated animate__fadeInUp">
                        <strong><i class="bi bi-exclamation-circle"></i> Belum ada kendaraan yang dimasukkan!</strong>
                    </div>
                @else
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col"><i class="bi bi-receipt"></i> NO POL</th>
                                <th scope="col"><i class="bi bi-building"></i> Merk</th>
                                <th scope="col"><i class="bi bi-truck"></i> Jenis Kendaraan</th>
                                <th scope="col"><i class="bi bi-gear-fill"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sortedVehicles as $vehicle)
                                <tr class="text-center animate__animated animate__fadeInUp" style="animation-delay: {{ 1.2 + $loop->index * 0.1 }}s;">
                                    <td>{{ $vehicle->license_plate }}</td>
                                    <td>{{ $vehicle->brand }}</td>
                                    <td>{{ $vehicle->vehicle_type }}</td>
                                    <td>
                                        <a href="{{ route('vehicle.show', $vehicle->id) }}" class="btn btn-info rounded-pill px-4 py-2" style="animation-delay: {{ 1.3 + $loop->index * 0.1 }}s;">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="btn btn-warning rounded-pill px-4 py-2" style="animation-delay: {{ 1.4 + $loop->index * 0.1 }}s;">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('vehicle.destroy', $vehicle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-2" style="animation-delay: {{ 1.5 + $loop->index * 0.1 }}s;">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                        <a href="{{ route('service.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-primary rounded-pill px-4 py-2" style="animation-delay: {{ 1.6 + $loop->index * 0.1 }}s;">
                                            <i class="bi bi-gear"></i> Tambah Service
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3 animate__animated animate__fadeInUp" style="animation-delay: 1.7s;">
                        {{ $vehicles->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection