@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title mb-4">Riwayat Servis</h5>
        
            <!-- Tombol untuk menuju ke Daftar Pelanggan -->
            <a href="{{ route('customer.index') }}" class="btn btn-primary mb-3">
                <i class="fas fa-users"></i> Daftar Pelanggan
            </a>
            <form method="GET" action="{{ route('service.index') }}" class="mb-4 row g-2 align-items-center">
                <div class="col-12 col-md-6">
                    <!-- Search Box with Icon -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari Kendaraan, Pelanggan, Keluhan, atau Layanan" value="{{ request('search') }}" onchange="this.form.submit()">
                        @if(request('search'))
                            <button class="btn btn-outline-secondary" type="button" onclick="window.location.href='{{ route('service.index') }}'">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <!-- Filter Tanggal with Icon -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
                    </div>
                </div>
            </form>
            
            @php
                // Mengelompokkan data servis berdasarkan hari dengan urutan tanggal terbaru
                $groupedByDay = $services->sortByDesc('created_at')->groupBy(function($service) {
                    return \Carbon\Carbon::parse($service->created_at)->locale('id')->dayName;
                });
            @endphp
        
            @if($groupedByDay->isEmpty())
                <div class="alert alert-warning text-center" role="alert">
                    <i class="fas fa-info-circle"></i> Tidak ada data servis yang ditemukan.
                </div>
            @else
                @foreach($groupedByDay as $day => $dayServices)
                    <h6 class="mt-4 mb-3">{{ $day }}</h6>
        
                    <div class="row">
                        @foreach($dayServices as $index => $service)
                        <div class="col-12 col-md-6 mb-3 animate__animated animate__fadeIn animate__delay-{{ ($index + 1) * 5 }}00ms">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-2">{{ $service->vehicle->vehicle_type ?? 'Belum Ada Kendaraan yang Ditugaskan' }}</h5>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($service->created_at)->format('l, d M Y, H:i') }}</small>
                                    </div>
                                    <p><strong>Keluhan:</strong> {{ $service->complaint ?? 'Tidak ada keluhan' }}</p>
                                    <p><strong>Jenis Layanan:</strong> {{ ucfirst($service->service_type) }}</p>
                                    <p><strong>Status Pembayaran:</strong> 
                                        @if($service->payment_received == 0)
                                            <span class="badge bg-danger">Belum Bayar</span>
                                        @elseif($service->payment_received >= $service->total_cost)
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning">Hutang</span>
                                        @endif
                                    </p>
                                    @if (Gate::allows('isBendahara'))
                                    <p>{{ $service->jurusan}}</p>                        
                                    @endif
            
                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        @if (! Gate::allows('isBendahara'))
                                        <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('service.destroy', $service->id) }}" method="POST" style="display:inline;" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus servis ini?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links('vendor.pagination.simple-bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush

@endsection