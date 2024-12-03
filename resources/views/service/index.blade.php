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
            <form method="GET" action="{{ route('service.index') }}" class="mb-3 row g-3">
                <div class="col-12 col-md-3">
                    <!-- Filter Pembayaran with Icon -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                        <select name="payment_status" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ session('payment_status') === 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="paid" {{ session('payment_status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="unpaid" {{ session('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <!-- Filter Hari with Icon -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                        <select name="day_of_week" class="form-select" onchange="this.form.submit()">
                            <option value="">Pilih Hari</option>
                            <option value="1" {{ request('day_of_week') == '1' ? 'selected' : '' }}>Minggu</option>
                            <option value="2" {{ request('day_of_week') == '2' ? 'selected' : '' }}>Senin</option>
                            <option value="3" {{ request('day_of_week') == '3' ? 'selected' : '' }}>Selasa</option>
                            <option value="4" {{ request('day_of_week') == '4' ? 'selected' : '' }}>Rabu</option>
                            <option value="5" {{ request('day_of_week') == '5' ? 'selected' : '' }}>Kamis</option>
                            <option value="6" {{ request('day_of_week') == '6' ? 'selected' : '' }}>Jumat</option>
                            <option value="7" {{ request('day_of_week') == '7' ? 'selected' : '' }}>Sabtu</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <!-- Filter Tanggal with Icon -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
                    </div>
                </div>
            
                <div class="col-12 col-md-3">
                    <!-- Filter Tahun with Icon -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <select name="year" class="form-select" onchange="this.form.submit()">
                            <option value="">Pilih Tahun</option>
                            @foreach(range(date('Y'), 2000) as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            
                <div class="col-12 col-md-6 d-flex justify-content-center"> <!-- Centered the search box -->
                    <!-- Search Box with Icon -->
                    <div class="input-group w-100">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Kendaraan, Pelanggan, Keluhan, atau Layanan" value="{{ request('search') }}" onchange="this.form.submit()">
                        @if(request('search'))
                            <button class="btn btn-outline-secondary" type="button" onclick="window.location.href='{{ route('service.index') }}'">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        @endif
                    </div>
                </div>
            </form>
            
            @php
                $groupedByDay = $services->groupBy(function($service) {
                    return \Carbon\Carbon::parse($service->created_at)->locale('id')->dayName; // Menggunakan nama hari dalam bahasa Indonesia
                });
            @endphp
        
            @foreach($groupedByDay as $day => $dayServices)
                <h6 class="mt-4 mb-3">{{ $day }}</h6>
        
                <div class="row">
                    @foreach($dayServices as $index => $service)
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3 animate__animated animate__fadeIn animate__delay-{{ ($index + 1) * 5 }}00ms">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-2">{{ $service->vehicle->vehicle_type ?? 'Belum Ada Kendaraan yang Ditugaskan' }}</h5>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($service->created_at)->format('l, d M Y, H:i') }}</small>
                                </div>
                                <p><strong>Keluhan:</strong> {{ $service->complaint ?? 'Tidak ada keluhan' }}</p>
                                <p><strong>Jenis Layanan:</strong> {{ ucfirst($service->service_type) }}</p>
                                <p><strong>Status Pembayaran:</strong> 
                                    @if($service->payment_received >= $service->total_cost)
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning">Belum Lunas</span>
                                    @endif
                                </p>                        
        
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach
        
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links('vendor.pagination.bootstrap-5') }}
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