@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="card shadow-lg">
            <div class="card-body">
                <h5 class="card-title mb-4">Riwayat Servis</h5>

                <!-- Tombol untuk menuju ke Daftar Pelanggan -->
                <a href="{{ route('customer.index') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-users"></i> Daftar Pelanggan
                </a>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('service.index') }}" class="mb-4 row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <!-- Search Box with Icon -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari Kendaraan, Pelanggan, Keluhan, atau Layanan"
                                value="{{ request('search') }}" onchange="this.form.submit()">
                            @if (request('search'))
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="window.location.href='{{ route('service.index') }}'">
                                    <i class="fas fa-times"></i> Reset
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <!-- Date Filter using Date Input -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}"
                                onchange="this.form.submit()">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <!-- Payment Status Filter -->
                        <select name="payment_status" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('payment_status', 'all') == 'all' ? 'selected' : '' }}>
                                Semua Pembayaran
                            </option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                Lunas
                            </option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                Hutang
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <!-- Service Status Filter -->
                        <select name="service_status" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('service_status', 'all') == 'all' ? 'selected' : '' }}>
                                Semua Status Servis
                            </option>
                            <option value="completed" {{ request('service_status') == 'completed' ? 'selected' : '' }}>
                                Selesai
                            </option>
                            <option value="not_completed"
                                {{ request('service_status') == 'not_completed' ? 'selected' : '' }}>
                                Belum Selesai
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <!-- Pagination Per Page Filter -->
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                                10 per halaman
                            </option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>
                                20 per halaman
                            </option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
                                50 per halaman
                            </option>
                        </select>
                    </div>
                </form>

                <!-- Tampilan Grid Service (row-cols-1 sm, row-cols-md-2, row-cols-lg-3) -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @forelse ($services as $service)
                        <div class="col">
                            <div class="card shadow-sm h-100 animate__animated animate__fadeIn">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-2">
                                            {{ $service->vehicle->vehicle_type ?? 'Belum Ada Kendaraan yang Ditugaskan' }}
                                        </h5>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($service->created_at)->format('d M Y, H:i') }}
                                        </small>
                                    </div>
                                    <p><strong>Keluhan:</strong> {{ $service->complaint ?? 'Tidak ada keluhan' }}</p>
                                    <p><strong>Jenis Layanan:</strong> {{ ucfirst($service->service_type) }}</p>
                                    <p><strong>Status Pembayaran:</strong>
                                        @if ($service->change < 0)
                                            <span class="badge bg-warning">Hutang</span>
                                        @else
                                            <span class="badge bg-success">Lunas</span>
                                        @endif
                                    </p>
                                    <p><strong>Status Servis:</strong>
                                        @if ($service->status == true)
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge" style="background-color: #FBA518">Belum Selesai</span>
                                        @endif
                                    </p>
                                    @if (Gate::allows('isBendahara'))
                                        <p>{{ $service->jurusan }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between mt-3">
                                        @if (Gate::allows('isSA'))
                                        <a href="{{ route('services.exportPkb', $service->id) }}" class="btn btn-success btn-sm flex-grow-1" target="_blank">
                                            <i class="bi bi-file-earmark-excel"></i> PKB Kerja Excel
                                        </a>
                                        @else
                                            <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @endif
                                        @if (!Gate::allows('isBendahara'))
                                            @if ($service->status == false)
                                                <a href="{{ route('service.edit', $service->id) }}"
                                                    class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @endif
                                            <form action="{{ route('service.destroy', $service->id) }}" method="POST"
                                                style="display:inline;" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus servis ini?')"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center" role="alert">
                                <i class="fas fa-info-circle"></i> Tidak ada data servis yang ditemukan.
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $services->appends(request()->all())->links('vendor.pagination.simple-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endsection
