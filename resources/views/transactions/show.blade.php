@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detail Transaksi</h1>
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #7abaff; color: white;">
                <span class="font-weight-bold">Transaksi #{{ $transaction->id }}</span>
                <div class="btn-group">
                    <a href="{{ route('transactions.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button id="printBtn" class="btn btn-dark btn-sm">
                        <i class="fas fa-print"></i> Print
                    </button>
                    @if (! Gate::allows('isBendahara'))    
                    {{-- <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a> --}}
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $transaction->id }})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                    @endif
                </div>
            </div>
        
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-cogs"></i> Sparepart</h5>
                        <p class="mb-1">{{ $transaction->sparepart->nama_sparepart }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-exchange-alt"></i> Jenis Transaksi</h5>
                        <p class="mb-1">
                            @if($transaction->transaction_type == 'sale')
                                Penjualan
                            @elseif($transaction->transaction_type == 'purchase')
                                Pembelian
                            @else
                                <span class="text-muted">Jenis tidak tersedia</span>
                            @endif
                        </p>
                    </div>
                </div>
        
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-sort-numeric-up"></i> Jumlah</h5>
                        <p class="mb-1">{{ $transaction->quantity }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-calendar-alt"></i> Tanggal Transaksi</h5>
                        <p class="mb-1">{{ $transaction->transaction_date->format('d-m-Y') }}</p>
                    </div>
                </div>
        
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-money-bill-wave"></i> Uang Masuk</h5>
                        <p class="mb-1" id="amountPaidText">Rp. {{ number_format($transaction->purchase_price, 2, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-dollar-sign"></i> Total Harga</h5>
                        <p class="mb-1" data-total-price="true">Rp. {{ number_format($totalPrice, 2, ',', '.') }}</p>
                    </div>
                </div>
        
                <!-- Subtotal Calculation -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-calculator"></i> Subtotal</h5>
                        <p class="mb-1">Rp. {{ number_format($subtotal, 2, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-2"><i class="fas fa-credit-card"></i> Kembalian</h5>
                        <p class="mb-1" id="changeAmount">Rp. 0,00</p>
                        <p class="mb-1" id="changeAmount">{{ $transaction->change}}</p>
                        
                    </div>
                </div>
        
            </div>
        
            <div class="card-footer text-muted text-center">
                Terima kasih telah bertransaksi dengan kami!
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                calculateChange();  // Calculate change when the page loads
            });
        
            function calculateChange() {
                // Get the amount paid from the predefined value (Uang Masuk)
                var amountPaid = parseFloat('{{ $transaction->purchase_price }}');
                var totalPrice = parseFloat('{{ $totalPrice }}');
        
                var change = amountPaid - totalPrice;
        
                var changeAmountElement = document.getElementById('changeAmount');
        
                if (change < 0) {
                    // If change is negative, show debt and red text
                    changeAmountElement.innerHTML = 'Rp. <span class="text-danger">' + formatCurrency(Math.abs(change)) + '</span> (Hutang)';
                } else {
                    // If change is positive or zero, show "Lunas" and green text
                    changeAmountElement.innerHTML = 'Rp. <span class="text-success">' + formatCurrency(change) + '</span> (Lunas)';
                }
            }
        
            // Format number as currency with Rp.
            function formatCurrency(amount) {
                return amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).replace('IDR', '').trim();
            }
        </script>                            
    </div>
    
    
    <script>
        function confirmDelete(transactionId) {
            if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                var formAction = '{{ route('transactions.destroy', ':id') }}'.replace(':id', transactionId);
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = formAction;
    
                var csrfToken = '{{ csrf_token() }}';
                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
    
                var csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = csrfToken;
    
                form.appendChild(methodField);
                form.appendChild(csrfField);
    
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
<script>
    document.getElementById('printBtn').addEventListener('click', function() {
        const technicianName = prompt("Masukkan nama teknisi yang mencetak:"); // Prompt untuk mendapatkan nama teknisi
        const printContent = document.querySelector('.container').innerHTML; // Ambil konten yang ingin dicetak
        const newWindow = window.open('', '', 'width=300,height=600'); // Buka jendela baru

        newWindow.document.write(`
            <html>
                <head>
                    <title>Struk Transaksi</title>
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
                    <style>
                        body {
                            font-family: 'Courier New', monospace;
                            font-size: 12px;
                            line-height: 1.5;
                            margin: 0;
                            padding: 0;
                            color: #333;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            text-align: center;
                        }
                        h1 {
                            font-size: 18px;
                            font-weight: bold;
                            text-align: center;
                            margin: 0;
                            padding: 10px 0;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                            color: #007bff;
                        }
                        h5 {
                            font-size: 16px;
                            text-align: center;
                            font-weight: normal;
                            margin: 10px 0;
                            color: #555;
                        }
                        .container {
                            width: 100%;
                            max-width: 350px;
                            padding: 20px;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            border-radius: 10px;
                            background-color: #fff;
                            margin: 0 auto;
                            text-align: left;
                        }
                        .card-header {
                            font-weight: bold;
                            font-size: 14px;
                            border-bottom: 2px solid #007bff;
                            padding: 6px 0;
                            color: #007bff;
                            text-transform: uppercase;
                        }
                        .list-group-item {
                            padding: 8px 0;
                            border-bottom: 1px dashed #ddd;
                            font-size: 12px;
                            display: flex;
                            justify-content: space-between;
                        }
                        .list-group-item strong {
                            font-weight: bold;
                            text-transform: uppercase;
                        }
                        .footer {
                            font-size: 10px;
                            text-align: center;
                            margin-top: 15px;
                            color: #888;
                        }
                        .icon {
                            margin-right: 5px;
                        }
                        .card-body {
                            padding: 10px;
                        }
                        @media print {
                            body {
                                padding: 0;
                                margin: 0;
                            }
                            .container {
                                width: 100%;
                                margin: 0;
                                padding: 10px;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1><i class="bi bi-cash-coin icon"></i> Transaksi #{{ $transaction->id }}</h1>
                        <h5><i class="bi bi-info-circle icon"></i> Detail Transaksi</h5>
                        <div class="card">
                            <div class="card-header">
                                Informasi Transaksi
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <span><i class="bi bi-hash icon"></i><strong>Sparepart:</strong></span>
                                        <span>{{ $transaction->sparepart->nama_sparepart }}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span><i class="bi bi-box icon"></i><strong>Jumlah:</strong></span>
                                        <span>{{ number_format($transaction->quantity, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span><i class="bi bi-file-earmark-ear icon"></i><strong>Jenis Transaksi:</strong></span>
                                        <span>{{ ucfirst($transaction->transaction_type) }}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span><i class="bi bi-currency-dollar icon"></i><strong>Harga Beli:</strong></span>
                                        <span>Rp. {{ number_format($transaction->purchase_price, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span><i class="bi bi-currency-dollar icon"></i><strong>Total Harga:</strong></span>
                                        <span>Rp. {{ number_format($transaction->total_price, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span><i class="bi bi-calendar-event icon"></i><strong>Tanggal Transaksi:</strong></span>
                                        <span>{{ $transaction->transaction_date->format('d-m-Y') }}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span><i class="bi bi-box-arrow-in-down icon"></i><strong>Subtotal:</strong></span>
                                        <span>Rp. {{ number_format($transaction->purchase_price * $transaction->quantity, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span><i class="bi bi-cash-coin icon"></i><strong>Kembalian:</strong></span>
                                        <span>Rp. 
                                            @php
                                                $subtotal = $transaction->purchase_price * $transaction->quantity;
                                                $change = $transaction->purchase_price - $subtotal;
                                            @endphp
                                            {{ number_format($change, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <p><i class="bi bi-emoji-smile icon"></i> Terima kasih atas kunjungan Anda!</p>
                            <p><strong>Teknisi: </strong>${technicianName}</p> <!-- Menampilkan nama teknisi -->
                        </div>
                    </div>
                </body>
            </html>
        `);

        newWindow.document.close();
        newWindow.print();
    });
</script>

@endsection