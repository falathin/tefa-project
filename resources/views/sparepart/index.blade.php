@extends('layouts.app')

@section('content')
    <!-- Include Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <div class="container mt-4">
        <!-- Card Wrapper -->
        <div class="card shadow-sm rounded">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-box"></i> Data Sparepart
                </h3>
                <a href="{{ route('sparepart.create') }}" class="btn btn-success btn-sm shadow-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Sparepart
                </a>
            </div>

            <div class="card-body">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Search Form -->
                <form method="GET" action="{{ route('sparepart.index') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari sparepart..." value="{{ request()->search }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('sparepart.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-gear"></i> Nama Sparepart</th>
                                <th><i class="bi bi-box"></i> Stok</th>
                                <th><i class="bi bi-cash-stack"></i> Harga Satuan</th>
                                <th><i class="bi bi-tools"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spareparts as $sparepart)
                                <tr class="animate__animated animate__fadeInUp">
                                    <td>{{ $sparepart->nama_sparepart }}</td>
                                    <td>{{ number_format($sparepart->jumlah, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($sparepart->harga_jual, 2, ',', '.') }}</td>
                                    <td>
                                        <!-- Detail Button -->
                                        <a href="{{ route('sparepart.show', $sparepart->id_sparepart) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('sparepart.edit', $sparepart->id_sparepart) }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>

                                        <!-- History Button -->
                                        <a href="{{ route('sparepart.history', $sparepart->id_sparepart) }}" class="btn btn-secondary btn-sm">
                                            <i class="bi bi-clock-history"></i> History
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('sparepart.destroy', $sparepart->id_sparepart) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data sparepart ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $spareparts->links('vendor.pagination.simple-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Apakah Anda yakin ingin menghapus sparepart ini?");
        }
    </script>
@endsection