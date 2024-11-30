<!-- resources/views/service/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Edit Service for Vehicle: {{ $service->vehicle->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('service.update', $service->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Vehicle ID (hidden) -->
                <input type="hidden" name="vehicle_id" value="{{ $service->vehicle->id }}">

                <!-- Complaint -->
                <div class="mb-3">
                    <label for="complaint" class="form-label">Complaint</label>
                    <input type="text" class="form-control" id="complaint" name="complaint" value="{{ old('complaint', $service->complaint) }}" required>
                </div>

                <!-- Current Mileage -->
                <div class="mb-3">
                    <label for="current_mileage" class="form-label">Current Mileage</label>
                    <input type="number" class="form-control" id="current_mileage" name="current_mileage" value="{{ old('current_mileage', $service->current_mileage) }}" required>
                </div>

                <!-- Service Fee -->
                <div class="mb-3">
                    <label for="service_fee" class="form-label">Service Fee</label>
                    <input type="number" class="form-control" id="service_fee" name="service_fee" value="{{ old('service_fee', $service->service_fee) }}" required>
                </div>

                <!-- Service Date -->
                <div class="mb-3">
                    <label for="service_date" class="form-label">Service Date</label>
                    <input type="date" class="form-control" id="service_date" name="service_date" value="{{ old('service_date', \Carbon\Carbon::parse($service->service_date)->format('Y-m-d')) }}" required>
                </div>

                <!-- Total Cost -->
                <div class="mb-3">
                    <label for="total_cost" class="form-label">Total Cost</label>
                    <input type="number" class="form-control" id="total_cost" name="total_cost" value="{{ old('total_cost', $service->total_cost) }}" required>
                </div>

                <!-- Payment Received -->
                <div class="mb-3">
                    <label for="payment_received" class="form-label">Payment Received</label>
                    <input type="number" class="form-control" id="payment_received" name="payment_received" value="{{ old('payment_received', $service->payment_received) }}" required>
                </div>

                <!-- Change -->
                <div class="mb-3">
                    <label for="change" class="form-label">Change</label>
                    <input type="number" class="form-control" id="change" name="change" value="{{ old('change', $service->change) }}" required>
                </div>

                <!-- Service Type -->
                <div class="mb-3">
                    <label for="service_type" class="form-label">Service Type</label>
                    <select class="form-control" id="service_type" name="service_type" required>
                        <option value="light" {{ old('service_type', $service->service_type) == 'light' ? 'selected' : '' }}>Light</option>
                        <option value="medium" {{ old('service_type', $service->service_type) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="heavy" {{ old('service_type', $service->service_type) == 'heavy' ? 'selected' : '' }}>Heavy</option>
                    </select>
                </div>

                <!-- Spareparts Used -->
                <div class="mb-3">
                    <label for="spareparts" class="form-label">Spareparts</label>
                    <select class="form-control" id="spareparts" name="spareparts[]" multiple>
                        @foreach($spareparts as $sparepart)
                            <option value="{{ $sparepart->id }}" 
                                @foreach($service->serviceSpareparts as $serviceSparepart)
                                    @if($serviceSparepart->sparepart_id == $sparepart->id)
                                        selected
                                    @endif
                                @endforeach
                            >
                                {{ $sparepart->name }} - Rp. {{ number_format($sparepart->harga_jual, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Service</button>
            </form>
        </div>
    </div>
</div>
@endsection