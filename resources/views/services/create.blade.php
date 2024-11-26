@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-center">Tambah Servis</h5>
            <form action="{{ route('service.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="vehicle_id">Kendaraan</label>
                    <select class="form-control" id="vehicle_id" name="vehicle_id" required>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_type }} - {{ $vehicle->license_plate }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="service_date">Tanggal Servis</label>
                    <input type="date" class="form-control" id="service_date" name="service_date" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection