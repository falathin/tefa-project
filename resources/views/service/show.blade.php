<!-- resources/views/service/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Service Details</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Vehicle: {{ $service->vehicle->license_plate }} ({{ $service->vehicle->vehicle_type }})</h5>
            
            <!-- Vehicle Information -->
            <p><strong>Vehicle Color: </strong>{{ $service->vehicle->color }}</p>
            <p><strong>Production Year: </strong>{{ $service->vehicle->production_year }}</p>
            <p><strong>Engine Code: </strong>{{ $service->vehicle->engine_code }}</p>

            <!-- Service Information -->
            <p><strong>Complaint: </strong>{{ $service->complaint }}</p>
            <p><strong>Current Mileage: </strong>{{ $service->current_mileage }} km</p>
            <p><strong>Service Fee: </strong>Rp. {{ number_format($service->service_fee, 0, ',', '.') }}</p>
            <p><strong>Total Cost: </strong>Rp. {{ number_format($service->total_cost, 0, ',', '.') }}</p>
            <p><strong>Payment Received: </strong>Rp. {{ number_format($service->payment_received, 0, ',', '.') }}</p>
            <p><strong>Change: </strong>Rp. {{ number_format($service->change, 0, ',', '.') }}</p>
            <p><strong>Service Type: </strong>{{ ucfirst($service->service_type) }}</p>
            <p><strong>Service Date: </strong>{{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }}</p>

            <!-- Spareparts Information -->
            <h5>Spareparts Used:</h5>
            @foreach($service->serviceSpareparts as $serviceSparepart)
                <p><strong>{{ $serviceSparepart->sparepart->nama_sparepart }} ({{ $serviceSparepart->quantity }}): </strong>
                    Rp. {{ number_format($serviceSparepart->sparepart->harga_jual, 0, ',', '.') }}
                </p>
            @endforeach

            <!-- Action buttons -->
            <div class="mt-4">
                <a href="{{ route('service.index') }}" class="btn btn-secondary">Back to Services</a>
                <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning">Edit Service</a>

                <!-- Delete Form -->
                <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Service</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection