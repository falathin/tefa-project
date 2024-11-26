@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4 text-center">Data Pelanggan Amelya</h5>
                
                <form>
                    <div class="row mb-3">
                        <label for="namaPelanggan" class="col-sm-3 col-form-label">Nama Pelanggan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaPelanggan" placeholder="Amelya" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="noHP" class="col-sm-3 col-form-label">No HP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="noHP" placeholder="088765653086" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="alamat" rows="2" placeholder="Karawang" disabled></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Kembali
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Kendaraan Terdaftar</h6>
                    <button type="button" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Kendaraan
                    </button>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">NO POL</th>
                                <th scope="col">Jenis Kendaraan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>T 1234 BA</td>
                                <td>Ninja</td>
                                <td>
                                    <button type="button" class="btn btn-primary">
                                        <i class="bi bi-wrench"></i> Tambah Service
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
