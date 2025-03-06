@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        @if (session()->has('message'))
            <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="card border-danger shadow-lg rounded-4 mb-4 animate__animated animate__shakeX">
                <div class="card-header bg-danger text-white d-flex align-items-center rounded-top">
                    <span class="me-2">⚠️</span> <strong>Terjadi Kesalahan</strong>
                </div>
                <div class="card-body rounded-bottom shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li class="text-danger fw-bold">❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        <div class="card shadow-lg animate__animated animate__fadeIn">
            <div class="card-header bg-primary text-white">
                <h5>Tambah Data Kendaraan Milik: {{ $customer->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vehicle.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="jurusan" value="{{ Auth::user()->jurusan }}">

                    @if (Auth::user()->jurusan == 'TKRO' || Auth::user()->jurusan == 'TSM')
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Merk</label>
                                <input type="text" class="form-control" id="brand" name="brand"
                                    value="{{ old('brand') }}"
                                    placeholder="{{ Auth::user()->jurusan == 'TKRO' ? 'Daihatsu' : 'Honda' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="vehicle_type" class="form-label">Tipe Kendaraan</label>
                                <input type="text" class="form-control" id="vehicle_type" name="vehicle_type"
                                    value="{{ old('vehicle_type') }}"
                                    placeholder="{{ Auth::user()->jurusan == 'TKRO' ? 'Xenia 1.5 A/T' : 'CBR 250RR' }}">
                            </div>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="engine_code" class="form-label">Kode Mesin</label>
                            <input type="text" class="form-control" id="engine_code" name="engine_code"
                                value="{{ old('engine_code') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="form-label">Warna</label>
                            <input type="text" class="form-control" id="color" name="color"
                                value="{{ old('color') }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="year" class="form-label">Tahun Kendaraan</label>
                            <input type="number" class="form-control" id="year" name="year"
                                value="{{ old('year') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="license_plate" class="form-label">Nomor Plat Kendaraan</label>
                            <input type="text" class="form-control" id="license_plate" name="license_plate"
                                value="{{ old('license_plate') }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="image" class="form-label">Foto Kendaraan (Opsional)</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="{{ route('customer.show', $customer->id) }}"
                            class="btn btn-outline-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection