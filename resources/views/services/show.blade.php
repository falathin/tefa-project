@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Detail Servis</h5>
            <p><strong>Kendaraan:</strong> {{ $service->vehicle->vehicle_type }} - {{ $service->vehicle->license_plate }}</p>
            <p><strong>Tanggal Servis:</strong> {{ $service->service_date }}</p>
            <a href="{{ route('service.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
        </div>
    </div>
</div>
@endsection