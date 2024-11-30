@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Daftar Servis</h5>
            <a href="{{ route('customer.index') }}" class="btn btn-primary mb-3">Daftar Pelanggan</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Jenis Kendaraan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->vehicle->customer->name }}</td>
                            <td>{{ $service->vehicle->vehicle_type }}</td>
                            <td>
                                <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('service.destroy', $service->id) }}" method="POST" style="display:inline;">
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
