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
            <h1 class="fw-bold mb-3 mb-md-0">Riwayat Sparepart {{ $sparepart->nama_sparepart }} spek {{ $sparepart->spek }}
            </h1>
            <div class="d-flex flex-column flex-sm-row align-items-center">
                <button class="btn btn-info mb-2 mb-sm-0 me-sm-2" data-bs-toggle="modal" data-bs-target="#infoModal">
                    <i class="fas fa-info-circle"></i> Informasi
                </button>
                <a href="{{ route('sparepart.index') }}" class="btn btn-secondary ms-0 ms-sm-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
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
                                <th>Stok Awal</th> <!-- Stok Awal -->
                                <th>Perubahan</th> <!-- Perubahan -->
                                <th>Stok Akhir</th> <!-- Stok Akhir -->
                                <th>Tanggal dan Waktu</th> <!-- Tanggal -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $history)
                                <tr>
                                    <!-- Initial Stock -->
                                    <td>
                                        {{-- @dd($query) --}}
                                        {{ $history->old_value }} unit
                                    </td>

                                    <!-- Change -->
                                    <td>
                                        @if ($history->old_value - $history->new_value < 0)
                                            <span class="text-success">+{{ $history->jumlah_changed }} unit 
                                                {{ $history->new_value - $history->old_value }} (Penambahan)
                                            </span>
                                        @elseif($history->old_value - $history->new_value > 0)
                                            <span class="text-danger">-{{ $history->jumlah_changed }}
                                                {{ $history->old_value - $history->new_value }} unit (Pemakaian)</span>
                                        @endif
                                    </td>

                                    <!-- Final Stock -->
                                    <td>{{ $history->new_value }} unit</td>

                                    <!-- Date -->
                                    <td>{{ $history->created_at->format('d-m-Y H:i:s') }}</td>
                                </tr>

                                {{-- @php
                                    $currentStock = $stockBeforeChange; // Update the stock for next iteration
                                @endphp --}}
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada histori perubahan stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $data->links('vendor.pagination.simple-bootstrap-5') }}
                </div>

                <!-- Text and Right-to-Left Arrow with Animation -->
                <div id="stock-history-message" class="mt-4 text-center animate_animated animate_fadeInLeft">
                    <p>
                        <span class="text-muted animate_animated animate_fadeInLeft">Baca Riwayat Perubahan Stok dari
                            kiri ke kanan</span>
                        &nbsp;&nbsp;&nbsp;
                        <span class="bi bi-arrow-right animate_animated animate_bounceInLeft"></span>
                    </p>
                </div>

                <script>
                    setTimeout(function() {
                        document.getElementById("stock-history-message").classList.add("animate__fadeOutRight");

                        setTimeout(function() {
                            document.getElementById("stock-history-message").style.display = 'none';
                        }, 1000);
                    }, 5000);
                </script>

            </div>

        </div>

        <!-- Modal Informasi -->
        <div class="modal fade animate_animated animate_fadeIn" id="infoModal" tabindex="-1"
            aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow-sm">
                    <div class="modal-header text-dark rounded-top p-3">
                        <h5 class="modal-title fs-5 fw-semibold" id="infoModalLabel">
                            <i class="fas fa-info-circle me-2"></i> Informasi Halaman
                        </h5>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body fs-6" style="line-height: 1.6;">
                        <div class="text-center mb-4">
                            <h6 class="text-primary mb-2">Riwayat Perubahan Stok</h6>
                            <p class="text-muted" style="font-size: 0.85rem;">Halaman ini menampilkan riwayat perubahan
                                stok untuk sparepart tertentu. Berikut adalah detail perubahan yang telah terjadi:</p>
                        </div>
                        {{-- <hr class="border-muted mt-2 mb-3"> --}}

                        {{-- <div class="text-start">
                            <p><strong>Perubahan Hari Ini:</strong> <span class="text-warning">{{ $todayChanges }}
                                    unit</span> ({{ $todayActionsCount }} kali)</p>
                            <p><strong>Ditambah Hari Ini:</strong> <span class="text-success">{{ $todayAdded }}
                                    unit</span></p>
                            <p><strong>Dikurangi Hari Ini:</strong> <span class="text-danger">{{ $todaySubtracted }}
                                    unit</span></p>

                            <p><strong>Perubahan Bulanan:</strong> <span class="text-warning">{{ $monthlyChanges }}
                                    unit</span> ({{ $monthlyActionsCount }} kali)</p>
                            <p><strong>Ditambah Bulan Ini:</strong> <span class="text-success">{{ $monthlyAdded }}
                                    unit</span></p>
                            <p><strong>Dikurangi Bulan Ini:</strong> <span class="text-danger">{{ $monthlySubtracted }}
                                    unit</span></p>

                            <p><strong>Perubahan Total:</strong> <span class="text-warning">{{ $totalChanges }}
                                    unit</span> ({{ $totalActionsCount }} kali)</p>
                            <p><strong>Ditambah Total:</strong> <span class="text-success">{{ $totalAdded }} unit</span>
                            </p>
                            <p><strong>Dikurangi Total:</strong> <span class="text-danger">{{ $totalSubtracted }}
                                    unit</span></p>
                        </div> --}}
                    </div>
                    <div class="modal-footer p-3">
                        <button type="button" class="btn btn-outline-primary fw-semibold rounded-3 px-4 py-2"
                            data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection