@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title mb-4">Daftar Servis</h5>
            <a href="{{ route('customer.index') }}" class="btn btn-primary mb-3">
                <i class="fas fa-users"></i> Daftar Pelanggan
            </a>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggan</th> <!-- Changed header to Nama Pelanggan -->
                        <th>Jenis Kendaraan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr class="animate__animated animate__fadeIn">
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->customer->name ?? 'No Customer Assigned' }}</td> <!-- Display customer name -->
                            <td>{{ $service->vehicle->vehicle_type ?? 'No Vehicle Assigned' }}</td>
                            <td>
                                <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('service.destroy', $service->id) }}" method="POST" style="display:inline;" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush

@endsection