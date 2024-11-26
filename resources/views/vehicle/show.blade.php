@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success mb-4 text-center">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0"><i class="bi bi-card-list"></i> Detail Kendaraan</h4>
        </div>

        <div class="card-body">
            @if($vehicle->image)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $vehicle->image) }}" alt="Vehicle Image" class="img-fluid img-thumbnail" style="max-width: 300px;">
                </div>
            @endif

            <div class="row g-4">
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
                    <input type="text" class="form-control" id="kodeMesin" value="{{ $vehicle->engine_code ?? '-' }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="noPolisi" class="form-label">
                        <i class="bi bi-key"></i> No Polisi
                    </label>
                    <input type="text" class="form-control" id="noPolisi" value="{{ $vehicle->license_plate ?? '-' }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="tahunProduksi" class="form-label">
                        <i class="bi bi-calendar-event"></i> Tahun Produksi
                    </label>
                    <input type="text" class="form-control" id="tahunProduksi" value="{{ $vehicle->production_year ?? '-' }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="warna" class="form-label">
                        <i class="bi bi-palette"></i> Warna
                    </label>
                    <input type="text" class="form-control" id="warna" value="{{ $vehicle->color ?? '-' }}" disabled>
                </div>
            </div>

            <hr class="my-5">

            <h5 class="text-center mb-4"><i class="bi bi-clock-history"></i> Riwayat Service</h5>

            <div class="row g-4">
                @foreach($vehicle->services as $service)
                    <div class="col-md-6">
                        <div class="card bg-light shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted"><i class="bi bi-tools"></i> {{ $service->type }}</h6>
                                <p class="text-muted mb-1">Total biaya: {{ $service->total_cost }}</p>
                                <p class="fw-bold"><i class="bi bi-calendar-check"></i> Tanggal: {{ $service->service_date }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn btn-dark">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection