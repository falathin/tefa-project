<!-- resources/views/transactions/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Daftar Transaksi Sparepart</h1>

    <a href="{{ route('sparepart.transaction.create') }}" class="btn btn-primary">Tambah Transaksi</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Sparepart</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Total Harga</th>
                <th>Tanggal Transaksi</th>
                <th>Jenis Transaksi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->sparepart->nama_sparepart }}</td>
                    <td>{{ $transaction->jumlah }}</td>
                    <td>{{ number_format($transaction->harga_beli, 2) }}</td>
                    <td>{{ number_format($transaction->total_harga, 2) }}</td>
                    <td>{{ $transaction->tanggal_transaksi }}</td>
                    <td>{{ ucfirst($transaction->jenis_transaksi) }}</td>
                    <td>
                        <a href="{{ route('sparepart.transaction.show', $transaction->id) }}" class="btn btn-info btn-sm">Lihat</a>
                        <a href="{{ route('sparepart.transaction.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('sparepart.transaction.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    {{ $transactions->links('vendor.pagination.simple-bootstrap-5') }}
@endsection