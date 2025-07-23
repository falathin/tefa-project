@extends('layouts.app')

@section('content')
    <!-- Include Animate.css and SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container mt-4">
        <!-- Card Wrapper -->
        <div class="card shadow-sm rounded">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color: #4B0082; color: white;">
                <h3 class="mb-0">
                    <i class="bi bi-box"></i> Data Sparepart
                </h3>
                @if (!Gate::allows('isBendahara'))
                    <a href="{{ route('sparepart.create') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Sparepart
                    </a>
                @endif
            </div>

            <div class="card-body">
                <!-- Success Alert via SweetAlert2 -->
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

                <!-- Search Form -->
                <form method="GET" action="{{ route('sparepart.index') }}" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari sparepart..."
                            value="{{ request()->search }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('sparepart.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>

                <!-- Export Semua by Date -->
                <form method="GET" action="{{ route('sparepart.export') }}" class="row g-2 mb-4">
                    <div class="col-sm-4">
                        <input type="date" name="from_date" class="form-control" required
                            value="{{ request('from_date') ?? \Carbon\Carbon::today()->format('Y-m-d') }}">
                    </div>
                    <div class="col-sm-4">
                        <input type="date" name="to_date" class="form-control" required
                            value="{{ request('to_date') ?? \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                    </div>
                    <div class="col-sm-4 d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Export Semua
                        </button>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-gear"></i> Nama Sparepart</th>
                                <th><i class="bi bi-gear"></i> Spek</th>
                                <th><i class="bi bi-box"></i> Stok</th>
                                <th><i class="bi bi-cash-stack"></i> Harga Satuan</th>
                                @if (Gate::allows('isBendahara'))
                                    <th><i class="fa-solid fa-wrench"></i> Jurusan</th>
                                @endif
                                <th><i class="bi bi-tools"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spareparts as $sparepart)
                                <tr class="animate__animated animate__fadeInUp">
                                    <td>{{ $sparepart->nama_sparepart }}</td>
                                    <td>{{ $sparepart->spek }}</td>
                                    <td>{{ number_format($sparepart->jumlah, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($sparepart->harga_jual, 2, ',', '.') }}</td>
                                    @if (Gate::allows('isBendahara'))
                                        <td>{{ $sparepart->jurusan }}</td>
                                    @endif
                                    <td>
                                        <a href="{{ route('sparepart.history', $sparepart->id_sparepart) }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="bi bi-clock-history"></i> History
                                        </a>

                                        @if (!Gate::allows('isBendahara'))
                                            <a href="{{ route('sparepart.show', $sparepart->id_sparepart) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('sparepart.edit', $sparepart->id_sparepart) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('sparepart.destroy', $sparepart->id_sparepart) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $spareparts->withQueryString()->links('vendor.pagination.simple-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert delete confirmation
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
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