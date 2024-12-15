@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header mt-3 rounded bg-danger text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> &nbsp; Detail Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaction_date"><i class="bi bi-calendar-event"></i> Tanggal Transaksi</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="transaction_type"><i class="bi bi-arrow-up-down"></i> Jenis Transaksi</label>
                            <input type="text" class="form-control" value="{{ $transaction->transaction_type == 'purchase' ? 'Pembelian' : 'Penjualan' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="total_cost"><i class="bi bi-wallet2"></i> Total Biaya</label>
                            <input type="text" class="form-control" value="{{ number_format($transaction->total_cost, 2) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="payment_received"><i class="bi bi-credit-card"></i> Uang Masuk</label>
                            <input type="text" class="form-control" value="{{ number_format($transaction->payment_received, 2) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="change"><i class="bi bi-cash-coin"></i> Kembalian</label>
                            <input type="text" class="form-control" value="{{ number_format($transaction->change, 2) }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @if ($transaction->spareparts->isNotEmpty())
                            <h5><i class="fas fa-cogs"></i> Spareparts</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Sparepart</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->spareparts as $sparepart)
                                        @if ($sparepart->pivot)
                                            <tr>
                                                <td>{{ $sparepart->nama_sparepart }}</td>
                                                <td>{{ number_format($sparepart->harga_jual, 2) }}</td>
                                                <td>{{ $sparepart->pivot->quantity ?? 0 }}</td>
                                                <td>{{ number_format($sparepart->pivot->subtotal ?? 0, 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-circle"></i> Tidak ada sparepart yang terlibat dalam transaksi ini.
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
@endsection