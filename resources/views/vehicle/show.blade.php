@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h4 class="text-center mb-4">
                <i class="bi bi-card-list"></i> Detail Kendaraan
            </h4>

            <!-- Vehicle Info -->
            <div class="row g-3">
                <div class="col-md-6 animate__animated animate__slideInUp">
                    <label for="jenisKendaraan" class="form-label">
                        <i class="bi bi-car-front"></i> Jenis Kendaraan
                    </label>
                    <input type="text" class="form-control" id="jenisKendaraan" value="{{ $vehicle->vehicle_type }}" disabled>
                </div>
                <div class="col-md-6 animate__animated animate__slideInUp">
                    <label for="kodeMesin" class="form-label">
                        <i class="bi bi-gear-wide"></i> Kode Mesin
                    </label>
                    <input type="text" class="form-control" id="kodeMesin" value="{{ $vehicle->engine_code }}" disabled>
                </div>
                <div class="col-md-6 animate__animated animate__slideInUp">
                    <label for="noPolisi" class="form-label">
                        <i class="bi bi-key"></i> No Polisi
                    </label>
                    <input type="text" class="form-control" id="noPolisi" value="{{ $vehicle->license_plate }}" disabled>
                </div>
                <div class="col-md-6 animate__animated animate__slideInUp">
                    <label for="tahunProduksi" class="form-label">
                        <i class="bi bi-calendar-event"></i> Tahun Produksi
                    </label>
                    <input type="text" class="form-control" id="tahunProduksi" value="{{ $vehicle->production_year }}" disabled>
                </div>
                <div class="col-md-6 animate__animated animate__slideInUp">
                    <label for="warna" class="form-label">
                        <i class="bi bi-palette"></i> Warna
                    </label>
                    <input type="text" class="form-control" id="warna" value="{{ $vehicle->color }}" disabled>
                </div>
                <div class="col-md-6 animate__animated animate__slideInUp">
                    <label for="image" class="form-label">
                        <i class="bi bi-image"></i> Gambar Kendaraan
                    </label>
                    @if($vehicle->image)
                        <!-- Trigger Button for Image Modal -->
                        <button class="btn btn-info animate__animated animate__slideInUp" data-bs-toggle="modal" data-bs-target="#imageModal">
                            Lihat Gambar
                        </button>
                    @else
                        <p class="text-muted">No image available</p>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <!-- Service History Section -->
            <h5 class="text-center mb-4">
                <i class="bi bi-clock-history"></i> Riwayat Service
            </h5>

            <div class="row text-center">
                <!-- Example of a service history card -->
                <div class="col-md-6 mb-3">
                    <div class="card bg-light shadow-sm p-3 hover-effect animate__animated animate__slideInUp">
                        <h6 class="text-muted"><i class="bi bi-tools"></i> Berkala</h6>
                        <p class="text-muted">Total biaya</p>
                        <p class="fw-bold"><i class="bi bi-calendar-check"></i> Date: 24-10-24</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-light shadow-sm p-3 hover-effect animate__animated animate__slideInUp">
                        <h6 class="text-muted"><i class="bi bi-tools"></i> Berkala</h6>
                        <p class="text-muted">Total biaya</p>
                        <p class="fw-bold"><i class="bi bi-calendar-check"></i> Date: 24-11-24</p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn btn-dark animate__animated animate__slideInUp">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content animate__animated animate__slideInUp">
                <div class="modal-header text-dark">
                    <h5 class="modal-title" id="imageModalLabel">Gambar Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    @if($vehicle->image)
                        <img src="{{ asset('storage/' . $vehicle->image) }}" class="img-fluid rounded shadow-lg" alt="Vehicle Image" style="max-height: 80vh; object-fit: contain;">
                    @else
                        <p class="text-muted fs-5">No image available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection