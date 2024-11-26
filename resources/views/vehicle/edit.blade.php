@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-center">Edit Kendaraan</h5>
            <form action="{{ route('vehicle.update', $vehicle->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="customer_id" value="{{ $vehicle->customer_id }}">

                <div class="form-group">
                    <label for="vehicle_type">Jenis Kendaraan</label>
                    <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" value="{{ $vehicle->vehicle_type }}" required>
                </div>
                <div class="form-group">
                    <label for="license_plate">No Polisi</label>
                    <input type="text" class="form-control" id="license_plate" name="license_plate" value="{{ $vehicle->license_plate }}" required>
                </div>
                <div class="form-group">
                    <label for="engine_code">Kode Mesin</label>
                    <input type="text" class="form-control" id="engine_code" name="engine_code" value="{{ $vehicle->engine_code }}" required>
                </div>
                <div class="form-group">
                    <label for="color">Warna</label>
                    <input type="text" class="form-control" id="color" name="color" value="{{ $vehicle->color }}" required>
                </div>
                <div class="form-group">
                    <label for="year">Tahun</label>
                    <input type="number" class="form-control" id="year" name="year" value="{{ $vehicle->year }}" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update</button>
                <a href="{{ route('customer.show', $vehicle->customer_id) }}" class="btn btn-secondary mt-3">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection