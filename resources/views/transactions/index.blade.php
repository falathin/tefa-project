@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header mt-3 rounded bg-primary text-white">
                <h5 class="mb-0">Daftar Transaksi Sparepart</h5>
            </div>
            <div class="card-body">
                <!-- Add Transaction Button -->
                <div class="mb-3">
                    <a href="{{ route('transactions.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Transaksi
                    </a>
                </div>

                @if ($transactions->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle"></i> Tidak ada transaksi sparepart yang tersedia.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sparepart</th>
                                    <th>Jumlah</th>
                                    <th>Total Biaya</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Aksi</th> <!-- Added actions column -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($transaction->sparepart)
                                                {{ $transaction->sparepart->nama_sparepart }}
                                            @else
                                                <span class="text-muted">Sparepart tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->quantity }}</td>
                                        <td>
                                            @if ($transaction->total_price)
                                                {{ number_format($transaction->total_price, 2, ',', '.') }}
                                            @else
                                                <span class="text-muted">Total tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                                        <td>{{ ucfirst($transaction->transaction_type) }}</td>
                                        <td>
                                            <!-- Action Buttons -->
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links('vendor.pagination.simple-bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection