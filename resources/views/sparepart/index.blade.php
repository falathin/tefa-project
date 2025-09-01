@extends('layouts.app')

@section('content')
<!-- Animate.css & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-box"></i> Data Sparepart</h4>
            @if (!Gate::allows('isBendahara'))
                <a href="{{ route('sparepart.create') }}" class="btn btn-light btn-sm shadow">
                    <i class="bi bi-plus-circle"></i> Tambah Sparepart
                </a>
            @endif
        </div>

        <div class="card-body">

            {{-- Success Alert --}}
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif

            {{-- Export Form --}}
            <div class="container-fluid px-0 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form method="GET" action="{{ route('spareparts.export') }}" class="row g-3 align-items-end">
                            @php
                                $userJurusan = auth()->user()->jurusan;
                            @endphp

                            {{-- Header --}}
                            <div class="col-12 mb-2">
                                <h5 class="mb-0">Export Data Sparepart</h5>
                                <small class="text-muted">Sesuaikan filter sebelum export</small>
                            </div>

                            {{-- Dari Tanggal --}}
                            <div class="col-12 col-md-4">
                                <label for="from_date" class="form-label small fw-semibold">Dari</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        value="{{ request('from_date', '') }}">
                                </div>
                            </div>

                            {{-- Sampai Tanggal --}}
                            <div class="col-12 col-md-4">
                                <label for="to_date" class="form-label small fw-semibold">Sampai</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event-fill"></i></span>
                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        value="{{ request('to_date', '') }}">
                                </div>
                            </div>

                            {{-- Pilih Jurusan --}}
                            @if ($userJurusan === 'General')
                                <div class="col-12 col-md-3">
                                    <label for="jurusan" class="form-label small fw-semibold">Jurusan</label>
                                    <select name="jurusan" id="jurusan" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="TKRO" {{ request('jurusan') === 'TKRO' ? 'selected' : '' }}>TKRO</option>
                                        <option value="TSM" {{ request('jurusan') === 'TSM' ? 'selected' : '' }}>TSM</option>
                                    </select>
                                </div>
                            @else
                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-semibold d-block">Jurusan</label>
                                    <span class="badge bg-primary">{{ $userJurusan }}</span>
                                    <input type="hidden" name="jurusan" value="{{ $userJurusan }}">
                                </div>
                            @endif

                            {{-- Tombol Export --}}
                            <div class="col-12 col-md-1 d-grid">
                                <button type="submit" class="btn btn-success w-100" title="Export ke Excel">
                                    <i class="bi bi-file-earmark-excel"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Nama Sparepart</th>
                            <th>Spek</th>
                            <th>Stok</th>
                            <th>Harga Satuan</th>
                            @if (Gate::allows('isBendahara'))
                                <th>Jurusan</th>
                            @endif
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spareparts as $sparepart)
                            <tr class="animate__animated animate__fadeInUp">
                                <td>{{ $sparepart->nama_sparepart }}</td>
                                <td>{{ $sparepart->spek }}</td>
                                <td class="text-center">{{ number_format($sparepart->jumlah, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($sparepart->harga_jual, 2, ',', '.') }}</td>
                                @if (Gate::allows('isBendahara'))
                                    <td>{{ $sparepart->jurusan }}</td>
                                @endif
                                <td class="text-nowrap">
                                    <a href="{{ route('sparepart.history', $sparepart->id_sparepart) }}"
                                        class="btn btn-secondary btn-sm mb-1">
                                        <i class="bi bi-clock-history"></i> History
                                    </a>
                                    @if (!Gate::allows('isBendahara'))
                                        <a href="{{ route('sparepart.show', $sparepart->id_sparepart) }}"
                                            class="btn btn-info btn-sm mb-1">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('sparepart.edit', $sparepart->id_sparepart) }}"
                                            class="btn btn-warning btn-sm mb-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('sparepart.destroy', $sparepart->id_sparepart) }}"
                                            method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mb-1">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Gate::allows('isBendahara') ? 6 : 5 }}" class="text-center">
                                    Tidak ada data sparepart ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $spareparts->withQueryString()->links('vendor.pagination.simple-bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus sparepart ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection