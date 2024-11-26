@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-center">Tambah Pelanggan</h5>
            <form action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Pelanggan" required>
                    @error('name')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contact">No HP (Optional)</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact') }}" placeholder="Masukkan No HP">
                    @error('contact')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Alamat (Optional)</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Masukkan Alamat">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    @if (old('address') === '')
                        <div class="alert alert-info mt-2">Gada data alamat yang ditambahkan</div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection