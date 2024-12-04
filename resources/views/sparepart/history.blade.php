@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sparepart.index') }}">Sparepart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat Sparepart</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">Riwayat Sparepart</h1>
            <div class="d-flex flex-column flex-md-row align-items-center mt-3 mt-md-0">
                <button class="btn btn-info mb-2 mb-md-0" data-bs-toggle="modal" data-bs-target="#infoModal">
                    <i class="fas fa-info-circle"></i> Informasi
                </button>
                <a href="{{ route('sparepart.index') }}" class="btn btn-secondary ms-0 ms-md-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>        

        <!-- Search Section -->
        <div class="row mb-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('sparepart.history', $sparepart->id_sparepart) }}">
                    <div class="input-group">
                        <!-- Search by name -->
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama sparepart atau aksi" value="{{ request('search') }}">

                        <!-- Filter by date -->
                        <input type="date" name="filter_date" class="form-control" value="{{ request('filter_date') }}">

                        <!-- Filter by action (add or subtract) -->
                        <select name="filter_action" class="form-select">
                            <option value="">Semua Aksi</option>
                            <option value="add" {{ request('filter_action') == 'add' ? 'selected' : '' }}>Tambah</option>
                            <option value="subtract" {{ request('filter_action') == 'subtract' ? 'selected' : '' }}>Kurang</option>
                        </select>

                        <!-- Filter by day of the week (Senin to Minggu) -->
                        <select name="filter_day" class="form-select">
                            <option value="">Semua Hari</option>
                            <option value="monday" {{ request('filter_day') == 'monday' ? 'selected' : '' }}>Senin</option>
                            <option value="tuesday" {{ request('filter_day') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                            <option value="wednesday" {{ request('filter_day') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                            <option value="thursday" {{ request('filter_day') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                            <option value="friday" {{ request('filter_day') == 'friday' ? 'selected' : '' }}>Jumat</option>
                            <option value="saturday" {{ request('filter_day') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                            <option value="sunday" {{ request('filter_day') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                        </select>

                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>                
            </div>
        </div>

        <!-- Tabel Riwayat Sparepart -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Riwayat Perubahan Stok</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Perubahan Stok</th>
                                <th>Sisa Stok</th>
                                <th>Aksi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $history)
                                <tr class="animate__animated animate__fadeIn animate__delay-1s">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $history->jumlah_changed }} unit</td>
                                    <td>{{ $history->sparepart->jumlah }} unit</td>
                                    <td class="fw-bold {{ $history->action == 'add' ? 'text-success' : 'text-danger' }}">
                                        {{ ucfirst($history->action) }}
                                    </td>
                                    <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada riwayat ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $histories->links('vendor.pagination.simple-bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- Modal Informasi -->
        <div class="modal fade animate__animated animate__fadeIn" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow-sm">
                    <div class="modal-header text-dark rounded-top p-3">
                        <h5 class="modal-title fs-5 fw-semibold" id="infoModalLabel">
                            <i class="fas fa-info-circle me-2"></i> Informasi Halaman
                        </h5>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body fs-6" style="line-height: 1.6;">
                        <div class="text-center mb-4">
                            <h6 class="text-primary mb-2">Riwayat Perubahan Stok</h6>
                            <p class="text-muted" style="font-size: 0.85rem;">Halaman ini menampilkan riwayat perubahan stok untuk sparepart tertentu. Berikut adalah detail perubahan yang telah terjadi:</p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">Tanggal</th>
                                        <th class="text-start">Aksi</th>
                                        <th class="text-start">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($histories as $history)
                                        <tr>
                                            <td class="text-muted fs-7">{{ $history->created_at->format('d M Y H:i') }}</td>
                                            <td class="fw-bold {{ $history->action == 'add' ? 'text-success' : 'text-danger' }}">
                                                {{ $history->action == 'add' ? 'Tambah' : 'Kurang' }}
                                            </td>
                                            <td class="text-info">{{ $history->jumlah_changed }} unit</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr class="border-muted mt-2 mb-3">

                        <div class="text-start">
                            <p><strong>Perubahan Hari Ini:</strong> <span class="text-warning">{{ $todayChanges }} kali</span></p>
                            <p><strong>Perubahan Bulanan:</strong> <span class="text-warning">{{ $monthlyChanges }} kali</span></p>
                            <p><strong>Perubahan Total:</strong> <span class="text-warning">{{ $totalChanges }} kali</span></p>
                        </div>

                        <div class="pagination-container text-center mt-3">
                            {{ $histories->links('vendor.pagination.simple-bootstrap-5') }}
                        </div>
                    </div>
                    <div class="modal-footer p-3">
                        <button type="button" class="btn btn-outline-primary fw-semibold rounded-3 px-4 py-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection