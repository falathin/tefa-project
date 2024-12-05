<!-- resources/views/sparepart/transaction/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Transaksi Suku Cadang</h1>

        <form action="{{ route('sparepart.transaction.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="sparepart_id">Sparepart</label>
                <select name="sparepart_id" id="sparepart_id" class="form-control" required>
                    <option value="">Pilih Sparepart</option>
                    @foreach ($spareparts as $sparepart)
                        <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->nama_sparepart }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label for="harga_beli">Harga Beli</label>
                <input type="number" name="harga_beli" id="harga_beli" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label for="tanggal_transaksi">Tanggal Transaksi</label>
                <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label for="jenis_transaksi">Jenis Transaksi</label>
                <select name="jenis_transaksi" id="jenis_transaksi" class="form-control" required>
                    <option value="purchase">Pembelian</option>
                    <option value="sale">Penjualan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Simpan Transaksi</button>
        </form>
    </div>
@endsection