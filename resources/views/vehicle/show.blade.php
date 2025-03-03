\@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h4 class="text-center mb-4">
                <i class="bi bi-card-list"></i> Detail Kendaraan
            </h4>

            <div class="text-start">
                <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn-sm btn btn-dark">
                    <i class="bi bi-arrow-left-circle"></i> Kembali ke Customer
                </a>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="brand" class="form-label">
                        <i class="bi bi-tags"></i> Merek Kendaraan
                    </label>
                    <input type="text" class="form-control" id="brand" value="{{ $vehicle->brand }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="jenisKendaraan" class="form-label">
                        <i class="bi bi-car-front"></i> Tipe Kendaraan
                    </label>
                    <input type="text" class="form-control" id="jenisKendaraan" value="{{ $vehicle->vehicle_type }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="kodeMesin" class="form-label">
                        <i class="bi bi-gear-wide"></i> Kode Mesin
                    </label>
                    <input type="text" class="form-control" id="kodeMesin" value="{{ $vehicle->engine_code }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="noPolisi" class="form-label">
                        <i class="bi bi-key"></i> No Polisi
                    </label>
                    <input type="text" class="form-control" id="noPolisi" value="{{ $vehicle->license_plate }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="tahunProduksi" class="form-label">
                        <i class="bi bi-calendar-event"></i> Tahun Produksi
                    </label>
                    <input type="text" class="form-control" id="tahunProduksi" value="{{ $vehicle->production_year }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="warna" class="form-label">
                        <i class="bi bi-palette"></i> Warna
                    </label>
                    <input type="text" class="form-control" id="warna" value="{{ $vehicle->color }}" disabled>
                </div>
                <div class="col-md-12 animate__animated animate__zoomIn text-center">
                    <label for="image" class="form-label">
                        <i class="bi bi-image"></i> Gambar Kendaraan
                    </label>
                    <br>
                    @if ($vehicle->image)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#vehicleImageModal">
                            <i class="bi bi-image"></i> Lihat Gambar
                        </button>
                    @else
                        <p>No image available.</p>
                    @endif
                </div>
            </div>
            
            <!-- Service History Section -->
            <h5 class="mt-5 animate__animated animate__slideInUp">Riwayat Service</h5>
            <!-- Search Form -->
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-center">
                    <form action="{{ route('vehicle.show', $vehicle->id) }}" method="GET"
                        class="d-flex align-items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" placeholder="Search services..."
                            aria-label="Search services">
                        <button type="submit" class="btn btn-outline-primary btn-sm ms-2">
                            <i class="bi bi-search"></i> Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('vehicle.show', $vehicle->id) }}" class="btn btn-outline-danger btn-sm ms-2"
                                aria-label="Close">
                                <i class="bi bi-x-circle"></i> Close
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <div class="text-center mt-3">
                <a href="{{ route('service.create', $vehicle->id) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Service
                </a>
            </div>
            <br>
            <div class="container mt-3">
                <!-- Services List -->
                <div class="row text-center">
                    @forelse($services->sortByDesc('created_at') as $index => $service)
                        <div
                            class="col-md-6 mb-3 animate__animated animate__slideInUp animate__delay-{{ $index + 1 }}s">
                            <div class="card bg-light shadow-sm p-3 hover-effect"
                                style="background-image: url('{{ asset('storage/' . $vehicle->image ?? '') }}'); background-size: cover; background-position: center;">
                                <!-- Service Type (e.g., Berkala) -->
                                <div class="card-body text-white">
                                    <h6 class="text-muted text-light">
                                        <i class="bi bi-tools"></i> 
                                        {{ $service->service_type }}
                                    
                                        @if ($service->service_type == 'light')
                                            <span class="fw-bold">10.000 KM (Ringan)</span>
                                        @elseif ($service->service_type == 'medium')
                                            <span class="fw-bold">10.000 KM (Sedang)</span>
                                        @elseif ($service->service_type == 'heavy')
                                            <span class="fw-bold">10.000 KM (Berat)</span>
                                        @endif
                                    </h6>

                                    <!-- Service Status with Icons -->
                                    @if ($service->status == 0)
                                        <p class="text-light">
                                            <i class="bi bi-x-circle text-danger"></i> Status servis: Belum selesai
                                        </p>
                                    @else
                                        <p class="text-light">
                                            <i class="bi bi-check-circle text-success"></i> Status servis: Selesai
                                        </p>
                                    @endif

                                    <p class="text-muted text-light">Total Biaya</p>
                                    <p class="fw-bold">Rp. {{ number_format($service->total_cost, 0, ',', '.') }}</p>

                                    <!-- Service Date -->
                                    <p class="fw-bold">
                                        <i class="bi bi-calendar-check"></i> Tanggal:
                                        {{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }} -
                                        {{ $service->created_at->format('d-m-Y H:i') }}
                                    </p>

                                    <!-- Actions (Detail, Edit, Delete) -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="{{ route('service.show', $service->id) }}" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-info-circle"></i> Detail
                                        </a>
                                        <a href="{{ route('service.edit', $service->id) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 animate__animated animate__slideInUp">
                            <p class="text-center text-danger font-weight-bold bounce-animation fs-6 mt-3">Tidak ada
                                riwayat service untuk kendaraan ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination links -->
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Service Pagination">
                        {{ $services->links('vendor.pagination.simple-bootstrap-5') }}
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Vehicle Image Modal -->
    @if ($vehicle->image)
        <div class="modal fade" id="vehicleImageModal" tabindex="-1" aria-labelledby="vehicleImageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vehicleImageModalLabel">Gambar Kendaraan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('storage/' . $vehicle->image) }}" class="img-fluid" alt="Vehicle Image">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .bounce-animation {
            display: inline-block;
            animation: bounce 1s ease infinite;
            color: red;
            font-weight: bold;
            font-size: 1rem;
        }

        /* Hover effect */
        .hover-effect:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }

        .card-body {
            background-color: rgba(0, 0, 0, 0.5) !important;
            border-radius: 0.5rem;
        }
    </style>
@endsection