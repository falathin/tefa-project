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
                    <input type="text" class="form-control" id="jenisKendaraan" placeholder="Ninja" disabled>
                </div>
                <div class="col-md-6">
                    <label for="kodeMesin" class="form-label">
                        <i class="bi bi-gear-wide"></i> Kode Mesin
                    </label>
                    <input type="text" class="form-control" id="kodeMesin" placeholder="23672675" disabled>
                </div>
                <div class="col-md-6">
                    <label for="noPolisi" class="form-label">
                        <i class="bi bi-key"></i> No Polisi
                    </label>
                    <input type="text" class="form-control" id="noPolisi" placeholder="T 1234 BA" disabled>
                </div>
                <div class="col-md-6">
                    <label for="tahunProduksi" class="form-label">
                        <i class="bi bi-calendar-event"></i> Tahun Produksi
                    </label>
                    <input type="text" class="form-control" id="tahunProduksi" placeholder="2023" disabled>
                </div>
                <div class="col-md-6">
                    <label for="warna" class="form-label">
                        <i class="bi bi-palette"></i> Warna
                    </label>
                    <input type="text" class="form-control" id="warna" placeholder="Hitam" disabled>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="text-center mb-4"><i class="bi bi-clock-history"></i> Riwayat Service</h5>

            <div class="row text-center">
                <div class="col-md-6 mb-3">
                    <div class="card bg-light shadow-sm p-3 hover-effect">
                        <h6 class="text-muted"><i class="bi bi-tools"></i> Berkala</h6>
                        <p class="text-muted">Total biaya</p>
                        <p class="fw-bold"><i class="bi bi-calendar-check"></i> Date: 24-10-24</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-light shadow-sm p-3 hover-effect">
                        <h6 class="text-muted"><i class="bi bi-tools"></i> Berkala</h6>
                        <p class="text-muted">Total biaya</p>
                        <p class="fw-bold"><i class="bi bi-calendar-check"></i> Date: 24-11-24</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 hover-effect">
                <button type="button" class="btn btn-dark">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </button>
            </div>
        </div>
    </div>

@endsection