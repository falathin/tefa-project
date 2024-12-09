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
            <h1 class="fw-bold mb-3 mb-md-0">Riwayat Sparepart</h1>
            <div class="d-flex flex-column flex-sm-row align-items-center">
                <button class="btn btn-info mb-2 mb-sm-0 me-sm-2" data-bs-toggle="modal" data-bs-target="#infoModal">
                    <i class="fas fa-info-circle"></i> Informasi
                </button>
                <a href="{{ route('sparepart.index') }}" class="btn btn-secondary ms-0 ms-sm-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        <!-- Search Section (Visible only on mobile devices) -->
        <div class="row mb-3 d-md-none">
            <div class="col-12">
                <!-- Button to open the filter modal -->
                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter
                </button>
                
                <!-- Modal for filter -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Riwayat Sparepart</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="GET" action="{{ route('sparepart.history', $sparepart->id_sparepart) }}">
                                    
                                    <!-- Search by name with icon -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
        
                                    <!-- Filter by date with icon -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="date" name="filter_date" class="form-control" value="{{ request('filter_date') }}">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
        
                                    <!-- Filter by action (add or subtract) with icon -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <select name="filter_action" class="form-select">
                                                    <option value="">Semua Aksi</option>
                                                    <option value="add" {{ request('filter_action') == 'add' ? 'selected' : '' }}>Tambah</option>
                                                    <option value="subtract" {{ request('filter_action') == 'subtract' ? 'selected' : '' }}>Kurang</option>
                                                </select>
                                                <span class="input-group-text"><i class="fas fa-exchange-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
        
                                    <!-- Filter by day of the week (Senin to Minggu) with icon -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="input-group">
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
                                                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Buttons for Search and Reset (Side by Side) -->
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <!-- Search Button -->
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <!-- Reset Button -->
                                            <a href="{{ route('sparepart.history', $sparepart->id_sparepart) }}" class="btn btn-secondary w-100">
                                                <i class="fas fa-undo"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <!-- Search Section (Visible on larger screens) -->
        <div class="row mb-3 d-none d-md-flex"> <!-- Visible on medium to large screens -->
            <div class="col-md-12">
                <form method="GET" action="{{ route('sparepart.history', $sparepart->id_sparepart) }}">
                    <div class="input-group">
                        <!-- Search by name with icon and placeholder -->
                        <input type="text" name="search" class="form-control w-25" value="{{ request('search') }}" placeholder="Cari berdasarkan nama sparepart">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>

                        <!-- Filter by date with icon and placeholder -->
                        <input type="date" name="filter_date" class="form-control" value="{{ request('filter_date') }}" placeholder="Pilih tanggal">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>

                        <!-- Filter by action (add or subtract) with icon and placeholder -->
                        <select name="filter_action" class="form-select">
                            <option value="">Pilih Aksi</option>
                            <option value="add" {{ request('filter_action') == 'add' ? 'selected' : '' }}>Tambah</option>
                            <option value="subtract" {{ request('filter_action') == 'subtract' ? 'selected' : '' }}>Kurang</option>
                        </select>
                        <span class="input-group-text"><i class="fas fa-exchange-alt"></i></span>

                        <!-- Filter by day of the week (Senin to Minggu) with icon and placeholder -->
                        <select name="filter_day" class="form-select">
                            <option value="">Pilih Hari</option>
                            <option value="monday" {{ request('filter_day') == 'monday' ? 'selected' : '' }}>Senin</option>
                            <option value="tuesday" {{ request('filter_day') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                            <option value="wednesday" {{ request('filter_day') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                            <option value="thursday" {{ request('filter_day') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                            <option value="friday" {{ request('filter_day') == 'friday' ? 'selected' : '' }}>Jumat</option>
                            <option value="saturday" {{ request('filter_day') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                            <option value="sunday" {{ request('filter_day') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                        </select>
                        <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>

                        <!-- Search Button -->
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>

                        <!-- Reset Button -->
                        <a href="{{ route('sparepart.history', $sparepart->id_sparepart) }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
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
                                <th>Stok Akhir</th> <!-- Stok Akhir -->
                                <th>Perubahan</th> <!-- Perubahan -->
                                <th>Stok Awal</th> <!-- Stok Awal -->
                                <th>Aksi</th> <!-- Aksi -->
                                <th>Tanggal</th> <!-- Tanggal -->
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentStock = $sparepart->jumlah; // Stok awal sparepart
                            @endphp

                            @forelse($histories as $history)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <!-- Stok Akhir -->
                                    <td>{{ $currentStock }} unit</td>

                                    <!-- Perubahan -->
                                    <td>
                                        @if($history->action == 'add')
                                            <span class="text-success">+{{ $history->jumlah_changed }} unit</span> <!-- Penambahan -->
                                        @elseif($history->action == 'use')
                                            <span class="text-danger">-{{ $history->jumlah_changed }} unit</span> <!-- Pengurangan -->
                                        @endif
                                    </td>

                                    <!-- Stok Awal -->
                                    <td>
                                        @php
                                            // Menghitung stok awal sebelum perubahan
                                            $stockBeforeChange = $currentStock - $history->jumlah_changed;
                                            $currentStock = $stockBeforeChange; // Update stok untuk perhitungan selanjutnya
                                        @endphp
                                        {{ $stockBeforeChange }} unit
                                    </td>

                                    <!-- Aksi -->
                                    <td class="{{ $history->action == 'add' ? 'text-success' : 'text-danger' }}">
                                        {{ ucfirst($history->action) }}
                                    </td>

                                    <!-- Tanggal -->
                                    <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada riwayat ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $histories->links('vendor.pagination.simple-bootstrap-5') }}
                </div>

                <!-- Text and Right-to-Left Arrow with Animation -->
                <div id="stock-history-message" class="mt-4 text-center animate__animated animate__fadeInRight">
                    <p>
                        <span class="bi bi-arrow-left animate__animated animate__bounceInLeft"></span>&nbsp;&nbsp;&nbsp;
                        <span class="text-muted animate__animated animate__fadeInRight">Baca Riwayat Perubahan Stok dari kanan ke kiri</span>
                    </p>
                </div>

                <script>
                    // Set a timeout to fade out the element after 5 seconds
                    setTimeout(function() {
                        // Add the fade-out animation
                        document.getElementById("stock-history-message").classList.add("animate__fadeOutLeft");

                        // After the animation duration (5 seconds), remove the element from the DOM
                        setTimeout(function() {
                            document.getElementById("stock-history-message").style.display = 'none';
                        }, 1000); // Wait for fade-out animation to finish before hiding (1 second)
                    }, 5000); // Trigger the fade-out after 5 seconds
                </script>

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
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Stok Awal</th>
                                        <th>Perubahan</th>
                                        <th>Stok Akhir</th>
                                        <th>Aksi</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentStock = $sparepart->jumlah;  // Get initial stock from sparepart
                                    @endphp
                                    @forelse($histories as $history)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                        
                                            <!-- Stok Awal -->
                                            <td>
                                                @php
                                                    $stockBeforeChange = $currentStock - $history->jumlah_changed;
                                                @endphp
                                                {{ $stockBeforeChange }} unit
                                            </td>
                        
                                            <!-- Perubahan (Change) -->
                                            <td>
                                                @if($history->action == 'add')
                                                    +{{ $history->jumlah_changed }} unit
                                                @elseif($history->action == 'use')
                                                    -{{ $history->jumlah_changed }} unit
                                                @endif
                                            </td>
                        
                                            <!-- Stok Akhir -->
                                            <td>{{ $currentStock }} unit</td>
                        
                                            <!-- Aksi (Action) -->
                                            <td class="{{ $history->action == 'add' ? 'text-success' : 'text-danger' }}">
                                                {{ ucfirst($history->action) }}
                                            </td>
                        
                                            <!-- Tanggal (Date) -->
                                            <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                        
                                        @php
                                            // Update current stock after the action
                                            if ($history->action == 'add') {
                                                $currentStock += $history->jumlah_changed;
                                            } elseif ($history->action == 'use') {
                                                $currentStock -= $history->jumlah_changed;
                                            }
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada riwayat ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                {{ $histories->links('vendor.pagination.simple-bootstrap-5') }}
                            </table>
                        </div>
                        <hr class="border-muted mt-2 mb-3">

                        <div class="text-start">
                            <p><strong>Perubahan Hari Ini:</strong> <span class="text-warning">{{ $todayChanges }} kali</span></p>
                            <p><strong>Perubahan Bulanan:</strong> <span class="text-warning">{{ $monthlyChanges }} kali</span></p>
                            <p><strong>Perubahan Total:</strong> <span class="text-warning">{{ $totalChanges }} kali</span></p>
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