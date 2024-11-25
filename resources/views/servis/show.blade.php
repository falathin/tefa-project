@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center my-4 animate__animated animate__fadeInDown">
        <h1 class="display-5">
            <i class="bi bi-tools me-2"></i> Laporan Servis
        </h1>
        <p class="text-muted"><i class="bi bi-info-circle-fill me-2"></i> Detail laporan servis dan spareparts</p>
    </div>

    <!-- Informasi Pelanggan -->
    <div class="card mt-4 shadow-sm border-dark animate__animated animate__fadeInUp animate__delay-1s">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i> Informasi Pelanggan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-person-fill me-2"></i> Nama Pelanggan:</strong></div>
                <div class="col-md-8">{{ $servis->kendaraan->pelanggan->nama_pelanggan }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-telephone-fill me-2"></i> Kontak:</strong></div>
                <div class="col-md-8">{{ $servis->kendaraan->pelanggan->kontak }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-house-fill me-2"></i> Alamat:</strong></div>
                <div class="col-md-8">{{ $servis->kendaraan->pelanggan->alamat ?? 'Tidak ada data' }}</div>
            </div>
        </div>
    </div>

    <!-- Informasi Kendaraan -->
    <div class="card mt-4 shadow-sm border-dark animate__animated animate__fadeInUp animate__delay-2s">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-car-front-fill me-2"></i> Informasi Kendaraan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-card-list me-2"></i> Nomor Polisi:</strong></div>
                <div class="col-md-8">{{ $servis->nomor_polisi }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-truck me-2"></i> Jenis Kendaraan:</strong></div>
                <div class="col-md-8">{{ $servis->kendaraan->jenis_kendaraan }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-palette-fill me-2"></i> Warna:</strong></div>
                <div class="col-md-8">{{ $servis->kendaraan->warna ?? 'Tidak ada data' }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-calendar-fill me-2"></i> Tahun Produksi:</strong></div>
                <div class="col-md-8">{{ $servis->kendaraan->tahun_produksi ?? 'Tidak ada data' }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-gear-fill me-2"></i> Kode Mesin:</strong></div>
                <div class="col-md-8">{{ $servis->kendaraan->kode_mesin ?? 'Tidak ada data' }}</div>
            </div>
        </div>
    </div>

    <!-- Detail Servis -->
    <div class="card mt-4 shadow-sm border-dark animate__animated animate__fadeInUp animate__delay-3s">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-file-text-fill me-2"></i> Detail Servis</h5>
            <a class="btn btn-warning btn-sm" href="{{route('servis.edit',$servis->id_servis)}}"> Edit</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-chat-left-dots-fill me-2"></i> Keluhan:</strong></div>
                <div class="col-md-8">{{ $servis->keluhan }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-speedometer2 me-2"></i> Kilometer Saat Ini:</strong></div>
                <div class="col-md-8">{{ $servis->kilometer_saat_ini }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-tools me-2"></i> Jenis Servis:</strong></div>
                <div class="col-md-8">{{ ucfirst($servis->jenis_servis) }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-cash me-2"></i> Total Biaya:</strong></div>
                <div class="col-md-8">Rp{{ number_format($servis->total_biaya, 0, ',', '.') }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="bi bi-calendar-check-fill me-2"></i> Tanggal Servis:</strong></div>
                <div class="col-md-8">{{ $servis->tanggal_servis }}</div>
            </div>
        </div>
    </div>

    <!-- Spareparts Table -->
    <div class="card mt-4 shadow-sm border-dark animate__animated animate__fadeInUp animate__delay-4s">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Spareparts yang Digunakan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th><i class="bi bi-box me-2"></i> Nama Sparepart</th>
                            <th><i class="bi bi-basket-fill me-2"></i> Jumlah</th>
                            <th><i class="bi bi-currency-dollar me-2"></i> Harga Satuan</th>
                            <th><i class="bi bi-cash-stack me-2"></i> Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceSparepart as $item)
                            <tr>
                                <td>{{ $item->sparepart->nama_sparepart }}</td>
                                <td>{{ $item->jumlah ?? 'N/A' }}</td>
                                <td>Rp{{ number_format($item->sparepart->harga_jual, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->sparepart->harga_jual * $item->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Tidak ada spareparts yang digunakan dalam servis ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kembali ke Daftar Servis -->
    <div class="text-center mt-4 animate__animated animate__fadeInUp animate__delay-5s">
        <a href="{{ route('servis.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-circle-fill me-2"></i> Kembali ke Daftar Servis
        </a>
    </div>
</div>
@endsection