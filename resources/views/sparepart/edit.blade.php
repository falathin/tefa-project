@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center"><i class="bi bi-pencil-square"></i> Edit Sparepart</h1>

    <!-- Card Container -->
    <div class="card shadow-lg p-4">
        <div class="card-body">
            <form action="{{ route('sparepart.update', $sparepart->id_sparepart) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Sparepart -->
                <div class="form-group mb-3">
                    <label for="nama_sparepart" class="form-label">
                        <i class="bi bi-cogs"></i> Nama Sparepart:
                    </label>
                    <input type="text" name="nama_sparepart" id="nama_sparepart" class="form-control" value="{{ $sparepart->nama_sparepart }}" required>
                </div>

                <!-- Jumlah -->
                <div class="form-group mb-3">
                    <label for="jumlah" class="form-label">
                        <i class="bi bi-hash"></i> Jumlah:
                    </label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $sparepart->jumlah }}" required min="1">
                </div>

                <!-- Harga Beli -->
                <div class="form-group mb-3">
                    <label for="harga_beli" class="form-label">
                        <i class="bi bi-cash"></i> Harga Beli:
                    </label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control" value="{{ $sparepart->harga_beli }}" required step="0.01">
                </div>

                <!-- Harga Jual -->
                <div class="form-group mb-3">
                    <label for="harga_jual" class="form-label">
                        <i class="bi bi-cash-stack"></i> Harga Jual:
                    </label>
                    <input type="number" name="harga_jual" id="harga_jual" class="form-control" value="{{ $sparepart->harga_jual }}" required step="0.01">
                </div>

                <!-- Tanggal Masuk -->
                <div class="form-group mb-3">
                    <label for="tanggal_masuk" class="form-label">
                        <i class="bi bi-calendar-plus"></i> Tanggal Masuk:
                    </label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ $sparepart->tanggal_masuk }}" required>
                </div>

                <!-- Tanggal Keluar -->
                <div class="form-group mb-3">
                    <label for="tanggal_keluar" class="form-label">
                        <i class="bi bi-calendar-x"></i> Tanggal Keluar:
                    </label>
                    <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control" value="{{ $sparepart->tanggal_keluar }}">
                </div>

                <!-- Deskripsi -->
                <div class="form-group mb-3">
                    <label for="deskripsi" class="form-label">
                        <i class="bi bi-info-circle"></i> Deskripsi:
                    </label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control">{{ $sparepart->deskripsi }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('sparepart.index') }}" class="btn btn-secondary mt-3">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <div>
                        <button type="reset" class="btn btn-warning mt-3">
                            <i class="bi bi-x-circle"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-success mt-3">
                            <i class="bi bi-check-circle"></i> Perbarui Sparepart
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
