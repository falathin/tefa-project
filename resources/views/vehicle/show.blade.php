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
                    <label for="brand" class="form-label fw-bold text-primary">
                        <i class="bi bi-tags"></i> Merek Kendaraan
                    </label>
                    <input type="text" class="form-control bg-light border-primary rounded-3 shadow-sm" 
                           id="brand" value="{{ $vehicle->brand }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="jenisKendaraan" class="form-label fw-bold text-success">
                        <i class="bi bi-car-front"></i> Tipe Kendaraan
                    </label>
                    <input type="text" class="form-control bg-light border-success rounded-3 shadow-sm" 
                           id="jenisKendaraan" value="{{ $vehicle->vehicle_type }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="kodeMesin" class="form-label fw-bold text-danger">
                        <i class="bi bi-gear-wide"></i> Kode Mesin
                    </label>
                    <input type="text" class="form-control bg-light border-danger rounded-3 shadow-sm" 
                           id="kodeMesin" value="{{ $vehicle->engine_code }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="noPolisi" class="form-label fw-bold text-warning">
                        <i class="bi bi-key"></i> No Polisi
                    </label>
                    <input type="text" class="form-control bg-light border-warning rounded-3 shadow-sm" 
                           id="noPolisi" value="{{ $vehicle->license_plate }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="tahunProduksi" class="form-label fw-bold text-info">
                        <i class="bi bi-calendar-event"></i> Tahun Produksi
                    </label>
                    <input type="text" class="form-control bg-light border-info rounded-3 shadow-sm" 
                           id="tahunProduksi" value="{{ $vehicle->production_year }}" disabled>
                </div>
                <div class="col-md-4 animate__animated animate__fadeIn">
                    <label for="warna" class="form-label fw-bold text-secondary">
                        <i class="bi bi-palette"></i> Warna
                    </label>
                    <input type="text" class="form-control bg-light border-secondary rounded-3 shadow-sm" 
                           id="warna" value="{{ $vehicle->color }}" disabled>
                </div>
                <div class="col-md-12 animate__animated animate__zoomIn text-center">
                    <label for="image" class="form-label fw-bold text-dark">
                        <i class="bi bi-image"></i> Gambar Kendaraan
                    </label>
                    <br>
                    @if ($vehicle->image)
                        <button type="button" class="btn btn-gradient-primary shadow-lg" data-bs-toggle="modal"
                                data-bs-target="#vehicleImageModal">
                            <i class="bi bi-image"></i> Lihat Gambar
                        </button>
                    @else
                        <p class="text-muted">No image available.</p>
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
                <div class="row row-cols-1 row-cols-md-2 g-4 text-center">
                    @forelse($services->sortByDesc('created_at') as $index => $service)
                        <div class="col animate__animated animate__fadeInUp animate__delay-{{ $index + 1 }}s">
                            <div class="card shadow-lg border-0 rounded-4 overflow-hidden position-relative">
                                <!-- Background Gambar Kendaraan -->
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
                                <div class="position-absolute top-0 start-0 w-100 h-100" 
                                     style="background: url('{{ asset('storage/' . ($vehicle->image ?? 'default.jpg')) }}') center/cover no-repeat;">
                                </div>
                
                                <div class="card-body position-relative text-white">
                                    <!-- Jenis Service -->
                                    <h6 class="fw-bold">
                                        <i class="bi bi-tools"></i> {{ ucfirst($service->service_type) }}
                                        <span class="badge bg-light text-dark ms-2">
                                            @if ($service->service_type == 'light')
                                                10.000 KM (Ringan)
                                            @elseif ($service->service_type == 'medium')
                                                10.000 KM (Sedang)
                                            @elseif ($service->service_type == 'heavy')
                                                10.000 KM (Berat)
                                            @endif
                                        </span>
                                    </h6>
                
                                    <!-- Status Servis -->
                                    <p class="mt-2">
                                        @if ($service->status == 0)
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Belum Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Selesai
                                            </span>
                                        @endif
                                    </p>
                
                                    <!-- Total Biaya -->
                                    <p class="fw-bold text-warning fs-5">
                                        <i class="bi bi-cash-coin"></i> Rp. {{ number_format($service->total_cost, 2, ',', '.') }}
                                    </p>
                
                                    <!-- Tanggal Service -->
                                    <p class="fw-light">
                                        <i class="bi bi-calendar-check"></i> 
                                        {{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }} - 
                                        {{ $service->created_at->format('d-m-Y H:i') }}
                                    </p>
                
                                    <!-- Tombol Aksi -->
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm w-100">
                                            <i class="bi bi-info-circle"></i> Detail
                                        </a>
                                        <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm w-100">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm w-100" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $service->id }}').submit();">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                        <form id="delete-form-{{ $service->id }}" action="{{ route('service.destroy', $service->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 animate__animated animate__fadeInUp">
                            <p class="text-danger fw-bold fs-6 mt-3">Tidak ada riwayat servis untuk kendaraan ini.</p>
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