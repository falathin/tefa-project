@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Daftar Kendaraan</h5>
            <a href="{{ route('vehicle.create') }}" class="btn btn-primary mb-3">Tambah Kendaraan</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jenis Kendaraan</th>
                        <th>No Polisi</th>
                        <th>Customer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->id }}</td>
                            <td>{{ $vehicle->vehicle_type }}</td>
                            <td>{{ $vehicle->license_plate }}</td>
                            <td>{{ $vehicle->customer->name }}</td>
                            <td>
                                <a href="{{ route('vehicle.show', $vehicle->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('vehicle.destroy', $vehicle->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection