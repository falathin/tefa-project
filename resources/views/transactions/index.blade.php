@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header mt-3 rounded" style="background-color: #4B0082; color: white;">
                <h5 class="mb-0">Daftar Transaksi Sparepart</h5>
            </div>
            <div class="card-body">
                <!-- Success Alert -->
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Search Form -->
                <div class="mb-3">
                    <form action="{{ route('transactions.index') }}" method="GET" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Sparepart..."
                                value="{{ request()->search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary hover-effect">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                                <!-- Cancel Button -->
                                <a href="{{ route('transactions.index') }}" class="btn btn-secondary ml-2 hover-effect">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Add Transaction Button -->
                <div class="mb-3">
                    <a href="{{ route('transactions.create') }}" class="btn btn-success hover-effect">
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
                                    <th>Aksi</th>
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
                                        <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            @if ($transaction->transaction_type == 'sale')
                                                Penjualan
                                            @elseif($transaction->transaction_type == 'purchase')
                                                Pembelian
                                            @else
                                                <span class="text-muted">Jenis tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction->id) }}"
                                                class="btn btn-info btn-sm hover-effect">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('transactions.edit', $transaction->id) }}"
                                                class="btn btn-warning btn-sm hover-effect">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm hover-effect"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->appends(request()->input())->links('vendor.pagination.simple-bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection