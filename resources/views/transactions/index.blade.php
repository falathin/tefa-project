@extends('layouts.app')

@section('content')
    <!-- Include Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <div class="container mt-4">
        <div class="card shadow-lg">
            <!-- Header -->
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color: #4B0082; color: white;">
                <h5 class="mb-0">
                    <i class="bi bi-receipt"></i> Laporan Transaksi Sparepart
                </h5>
                @if (!Gate::allows('isBendahara'))
                    <a href="{{ route('transactions.create') }}" class="btn btn-light btn-sm shadow-sm hover-effect">
                        <i class="bi bi-plus-circle"></i> Tambah Transaksi
                    </a>
                @endif
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Success Alert with Close Button -->
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn"
                        role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Search Form -->
                <form action="{{ route('transactions.index') }}" method="GET" class="mb-3">
                    <div class="input-group shadow-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari Sparepart..."
                            value="{{ request()->search }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>

                <!-- Table -->
                @if ($transactions->isEmpty())
                    <div class="alert alert-warning text-center animate__animated animate__fadeIn">
                        <i class="bi bi-info-circle"></i> Tidak ada transaksi yang tersedia.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th><i class="bi bi-gear"></i> Nama Sparepart</th>
                                    <th><i class="bi bi-box"></i> Jumlah</th>
                                    <th><i class="bi bi-cash"></i> Total Biaya</th>
                                    <th><i class="bi bi-calendar"></i> Tanggal</th>
                                    <th><i class="bi bi-tag"></i> Jenis</th>
                                    @if (Gate::allows('isBendahara'))
                                        <th><i class="fa-solid fa-wrench"></i>Jurusan</th>
                                    @endif
                                    <th><i class="bi bi-tools"></i> Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $index => $transaction)
                                    <tr class="animate__animated animate__fadeInUp animate__delay-{{ $index + 1 }}s">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($transaction->sparepart)
                                                <i class="bi bi-check-circle text-success"></i>
                                                {{ $transaction->sparepart->nama_sparepart }}
                                            @else
                                                <span class="text-muted">Tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->quantity }}</td>
                                        <td>{{ number_format($transaction->total_price, 2, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            @if ($transaction->transaction_type == 'sale')
                                                <span class="badge bg-success">Penjualan</span>
                                            @elseif($transaction->transaction_type == 'purchase')
                                                <span class="badge bg-warning">Pembelian</span>
                                            @else
                                                <span class="text-muted">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        @if (Gate::allows('isBendahara'))
                                            <td> {{ $transaction->jurusan }} </td>
                                        @endif
                                        <td>
                                            <!-- Action Buttons -->
                                            <a href="{{ route('transactions.show', $transaction->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if (Gate::allows('isKasir') xor Gate::allows('isAdminOrEngineer'))
                                                <a href="{{ route('transactions.edit', $transaction->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin hapus transaksi ini?')">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
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
