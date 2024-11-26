@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h4 class="text-center mb-4">
                <i class="bi bi-card-list"></i> Detail Kendaraan
            </h4>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="jenisKendaraan" class="form-label">
                        <i class="bi bi-car-front"></i> Jenis Kendaraan
                    </label>
                    <input type="text" class="form-control" id="jenisKendaraan" value="{{ $vehicle->type }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="kodeMesin" class="form-label">
                        <i class="bi bi-gear-wide"></i> Kode Mesin
                    </label>
                    <input type="text" class="form-control" id="kodeMesin" value="{{ $vehicle->engine_code }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="noPolisi" class="form-label">
                        <i class="bi bi-key"></i> No Polisi
                    </label>
                    <input type="text" class="form-control" id="noPolisi" value="{{ $vehicle->license_plate }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="tahunProduksi" class="form-label">
                        <i class="bi bi-calendar-event"></i> Tahun Produksi
                    </label>
                    <input type="text" class="form-control" id="tahunProduksi" value="{{ $vehicle->production_year }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="warna" class="form-label">
                        <i class="bi bi-palette"></i> Warna
                    </label>
                    <input type="text" class="form-control" id="warna" value="{{ $vehicle->color }}" disabled>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="text-center mb-4"><i class="bi bi-clock-history"></i> Riwayat Service</h5>

            <div class="row text-center">
                @foreach($vehicle->services as $service)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light shadow-sm p-3 hover-effect">
                            <h6 class="text-muted"><i class="bi bi-tools"></i> {{ $service->type }}</h6>
                            <p class="text-muted">Total biaya: {{ $service->total_cost }}</p>
                            <p class="fw-bold"><i class="bi bi-calendar-check"></i> Date: {{ $service->service_date }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4 hover-effect">
                <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn btn-dark">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>
        </div>
    </div>
@endsection