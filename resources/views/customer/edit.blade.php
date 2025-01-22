@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-center">Edit Pelanggan</h5>

            <!-- Menampilkan alert sukses jika ada -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Pelanggan -->
                <div class="form-group">
                    <label for="name">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                </div>

                <!-- No HP (contact) -->
                <div class="form-group">
                    <label for="contact">No HP</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact', $customer->contact) }}">
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea class="form-control" id="address" name="address" required>{{ old('address', $customer->address) }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning mt-3">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection