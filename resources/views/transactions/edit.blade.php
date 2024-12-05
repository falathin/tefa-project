<!-- resources/views/sparepart/transaction/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Transaksi Suku Cadang</h1>

        <form action="{{ route('sparepart.transaction.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="sparepart_id">Sparepart</label>
                <select name="sparepart_id" id="sparepart_id" class="form-control" required>
                    <option value="">Pilih Sparepart</option>
                    @foreach ($spareparts as $sparepart)
                        <option value="{{ $sparepart->id_sparepart }}" 
                            {{ $transaction->sparepart_id == $sparepart->id_sparepart ? 'selected' : '' }}>
                            {{ $sparepart->nama_sparepart }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $transaction->jumlah }}" required>
            </div>

            <div class="form-group mt-3">
                <label for="harga_beli">Harga Beli</label>
                <input type="number" name="harga_beli" id="harga_beli" class="form-control" value="{{ $transaction->harga_beli }}" required>
            </div>

            <div class="form-group mt-3">
                <label for="tanggal_transaksi">Tanggal Transaksi</label>
                <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control" value="{{ $transaction->tanggal_transaksi }}" required>
            </div>

            <div class="form-group mt-3">
                <label for="jenis_transaksi">Jenis Transaksi</label>
                <select name="jenis_transaksi" id="jenis_transaksi" class="form-control" required>
                    <option value="purchase" {{ $transaction->jenis_transaksi == 'purchase' ? 'selected' : '' }}>Pembelian</option>
                    <option value="sale" {{ $transaction->jenis_transaksi == 'sale' ? 'selected' : '' }}>Penjualan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update Transaksi</button>
        </form>
    </div>
@endsection