<!-- resources/views/sparepart/transaction/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Detail Transaksi Suku Cadang</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sparepart: {{ $transaction->sparepart->nama_sparepart }}</h5>
                <p><strong>Jumlah:</strong> {{ $transaction->jumlah }}</p>
                <p><strong>Harga Beli:</strong> {{ number_format($transaction->harga_beli, 2) }}</p>
                <p><strong>Total Harga:</strong> {{ number_format($transaction->total_harga, 2) }}</p>
                <p><strong>Tanggal Transaksi:</strong> {{ $transaction->tanggal_transaksi }}</p>
                <p><strong>Jenis Transaksi:</strong> {{ ucfirst($transaction->jenis_transaksi) }}</p>
            </div>
        </div>

        <a href="{{ route('sparepart.transaction.index') }}" class="btn btn-secondary mt-4">Kembali ke Daftar Transaksi</a>
    </div>
@endsection