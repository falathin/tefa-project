@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Tambahkan kelas "printable" pada card agar kontennya bisa diambil untuk print -->
    <div class="card shadow-lg border-0 rounded-3 printable">
        <div class="card-body">
            <h2 class="text-center mb-4">
                <i class="fas fa-tools"></i> Detail Transaksi Sparepart: <strong>{{ $transaction->name }}</strong>
            </h2>

            <div class="row mt-4 g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-success shadow-sm p-3 text-center">
                        <h5 class="fw-bold mb-2"><i class="fas fa-shopping-cart"></i> Total Harga</h5>
                        <p class="fs-4 fw-bold mb-0">Rp {{ number_format($subtotalBeforeDiscount) }}</p>
                    </div>
                </div>
            
                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-secondary shadow-sm p-3 text-center">
                        <h5 class="fw-bold mb-2"><i class="fas fa-calculator"></i> Total Setelah Diskon</h5>
                        <p class="fs-4 fw-bold mb-0">Rp {{ number_format($totalPrice) }}</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-info shadow-sm p-3 text-center">
                        <h5 class="fw-bold mb-2"><i class="fas fa-percent"></i> Diskon</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $transaction->discount }}</p>
                    </div>
                </div>
            
                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-primary shadow-sm p-3 text-center">
                        <h5 class="fw-bold mb-2"><i class="fas fa-wallet"></i> Uang Diterima</h5>
                        <p class="fs-4 fw-bold mb-0">Rp {{ number_format($transaction->purchase_price) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-6">
                    <div class="card text-white shadow-sm p-3 text-center {{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'bg-success' : 'bg-danger' }}">
                        <h5 class="fw-bold mb-2">
                            <i class="fas {{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'fa-money-bill-wave' : 'fa-exclamation-circle' }}"></i> 
                            {{ $transaction->purchase_price >= ($totalPrice * $transaction->discount / 100) ? 'Kembalian' : 'Hutang' }}
                        </h5>
                        <p class="fs-4 fw-bold mb-0">Rp {{ number_format($transaction->purchase_price - $totalPrice) }}</p>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="card text-white bg-dark shadow-sm p-3 text-center">
                        <h5 class="fw-bold mb-2"><i class="fas fa-credit-card"></i> Metode Pembayaran</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $transaction->payment_method }}</p>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Sparepart</th>
                            <th>Spesifikasi</th>
                            <th>Jumlah</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Keuntungan</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->transactionSpareparts as $sparepart)
                            <tr>
                                <td>{{ $sparepart->nama_sparepart }}</td>
                                <td>{{ $sparepart->spek }}</td>
                                <td class="fw-bold text-center">{{ $sparepart->quantity }}</td>
                                <td>Rp {{ number_format($sparepart->harga_beli) }}</td>
                                <td class="text-success">Rp {{ number_format($sparepart->harga_jual) }}</td>
                                <td class="text-warning">Rp {{ number_format($sparepart->keuntungan) }}</td>
                                <td class="text-danger fw-bold">Rp {{ number_format($sparepart->quantity * $sparepart->harga_jual) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary copy-btn" data-text="{{ $sparepart->nama_sparepart }}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Tombol Navigasi: Kembali, Edit, Salin, dan Print -->
            <div class="d-flex justify-content-between mt-3">
                <div>
                    <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                {{-- <div>
                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-success">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div> --}}
                <div>
                    <button class="btn btn-warning" id="copyAll">
                        <i class="fas fa-clipboard"></i> Salin Semua
                    </button>
                </div>
                <div>
                    <button class="btn btn-info" id="printBtn">
                        <i class="fas fa-print"></i> Print Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk animasi, copy, dan print menggunakan hidden iframe -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Animasi baris tabel
    document.querySelectorAll("tr").forEach((row, index) => {
        setTimeout(() => {
            row.classList.add("animate__animated", "animate__fadeInUp");
        }, index * 100);
    });

    // Copy individual
    document.querySelectorAll(".copy-btn").forEach(button => {
        button.addEventListener("click", function() {
            const text = this.dataset.text;
            navigator.clipboard.writeText(text).then(() => {
                showToast("📋 Teks disalin! ✅");
            });
        });
    });

    // Copy semua data
    document.getElementById("copyAll").addEventListener("click", function() {
        let text = "📌 **Laporan Transaksi Sparepart**\n";
        text += "===================================\n\n";
        text += `🧾 **Total Harga**: Rp {{ number_format($totalPrice) }}\n`;
        text += `💵 **Uang Diterima**: Rp {{ number_format($transaction->purchase_price) }}\n`;
        text += `💰 **${{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'Kembalian' : 'Hutang' }}**: Rp {{ number_format(abs($transaction->purchase_price - ($totalPrice - $transaction->discount))) }}\n`;
        text += "===================================\n\n";
        @foreach($transaction->transactionSpareparts as $index => $trans)
            text += `🛠️ **Transaksi #{{ $index + 1 }}**\n`;
            text += `📌 Nama Sparepart: {{ $trans->nama_sparepart }}\n`;
            text += `🔍 Spesifikasi: {{ $trans->spek }}\n`;
            text += `📦 Jumlah: {{ $trans->quantity }} unit\n`;
            text += `💰 Harga Beli: Rp {{ number_format($trans->harga_beli) }}\n`;
            text += `💵 Harga Jual: Rp {{ number_format($trans->harga_jual) }}\n`;
            text += `📈 Keuntungan: Rp {{ number_format($trans->keuntungan) }}\n`;
            text += `🧮 Subtotal: Rp {{ number_format($trans->subtotal) }}\n`;
            text += "-----------------------------------\n\n";
        @endforeach
        navigator.clipboard.writeText(text).then(() => {
            showToast("🚀 Laporan transaksi telah disalin! ✅");
        });
    });

    // Fungsi untuk menampilkan toast
    function showToast(message) {
        let toast = document.createElement("div");
        toast.className = "toast-message";
        toast.innerHTML = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add("show");
        }, 100);
        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }


});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("printBtn").addEventListener("click", function() {
            // Template invoice dengan Bootstrap 4 dan Font Awesome, dengan tambahan ikon, penyesuaian margin,
            // dan invoice-card ditengahkan secara horizontal & vertikal
            const template = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Struk Transaksi Sparepart</title>
                <!-- Bootstrap 4 CDN -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
                <!-- Font Awesome CDN -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
                <style>
                    html, body {
                        min-height: 100vh;
                        height: 100%;
                        margin: 0;
                        font-family: 'Arial', sans-serif;
                        font-size: 12px;
                        line-height: 1.4;
                        color: #333;
                        background-color: #f7f7f7;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                    .invoice-card {
                        width: 100%;
                        max-width: 650px;
                        padding: 15px;
                        background-color: #fff;
                        border: 1px solid #ccc;
                        border-radius: 8px;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                    }
                    .invoice-header {
                        text-align: center;
                        padding-bottom: 10px;
                        border-bottom: 2px solid #007bff;
                        margin-bottom: 15px;
                    }
                    .invoice-header h1 {
                        font-size: 22px;
                        margin: 0;
                        color: #007bff;
                    }
                    .invoice-header p {
                        font-size: 12px;
                        margin: 0;
                    }
                    .invoice-table {
                        margin-top: 10px;
                    }
                    .invoice-table table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .invoice-table th, .invoice-table td {
                        border: 1px solid #ddd;
                        padding: 6px;
                        text-align: center;
                    }
                    .invoice-table th {
                        background-color: #007bff;
                        color: #fff;
                        font-size: 11px;
                    }
                    .invoice-table td {
                        font-size: 10px;
                    }
                    .invoice-info {
                        margin-top: 10px;
                        padding: 10px;
                        background-color: #f1f1f1;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        font-size: 11px;
                    }
                    .invoice-info .row > div {
                        margin-bottom: 5px;
                    }
                    .invoice-footer {
                        text-align: center;
                        margin-top: 10px;
                        padding-top: 8px;
                        border-top: 1px solid #ddd;
                        font-weight: bold;
                        font-size: 12px;
                        color: #007bff;
                    }
                    .invoice-footer small {
                        display: block;
                        font-size: 10px;
                        margin-top: 4px;
                        color: #666;
                    }
                    @media print {
                        html, body { margin: 0; height: auto; display: block; }
                        .invoice-card { width: 80%; margin: auto; padding: 5px; }
                    }
                </style>
            </head>
            <body>
                <div class="invoice-card">
                    <div class="invoice-header">
                        <img width="40px" src="{{ asset('assets/images/logo-mini.svg') }}" alt="Logo" class="mb-1">
                        <h1><i class="fas fa-tools"></i> Struk Transaksi Sparepart</h1>
                        <p><i class="fas fa-info-circle"></i> Detail: {{ $transaction->name }}</p>
                    </div>
                    <div class="invoice-table">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-cog"></i> Nama Sparepart</th>
                                    <th><i class="fas fa-info"></i> Spesifikasi</th>
                                    <th><i class="fas fa-sort-numeric-up"></i> Jumlah</th>
                                    <th><i class="fas fa-dollar-sign"></i> Harga Beli</th>
                                    <th><i class="fas fa-dollar-sign"></i> Harga Jual</th>
                                    <th><i class="fas fa-chart-line"></i> Keuntungan</th>
                                    <th><i class="fas fa-calculator"></i> Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->transactionSpareparts as $sparepart)
                                <tr>
                                    <td><i class="fas fa-toolbox"></i> {{ $sparepart->nama_sparepart }}</td>
                                    <td><i class="fas fa-info-circle"></i> {{ $sparepart->spek }}</td>
                                    <td>{{ $sparepart->quantity }}</td>
                                    <td>Rp {{ number_format($sparepart->harga_beli) }}</td>
                                    <td>Rp {{ number_format($sparepart->harga_jual) }}</td>
                                    <td>Rp {{ number_format($sparepart->keuntungan) }}</td>
                                    <td>Rp {{ number_format($sparepart->quantity * $sparepart->harga_jual) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="invoice-info">
                        <div class="row">
                            <div class="col-6">
                                <strong><i class="fas fa-shopping-cart"></i> Total Harga:</strong><br>
                                Rp {{ number_format($subtotalBeforeDiscount) }}
                            </div>
                            <div class="col-6">
                                <strong><i class="fas fa-percent"></i> Diskon:</strong><br>
                                Rp {{ number_format($transaction->discount) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong><i class="fas fa-calculator"></i> Total Setelah Diskon:</strong><br>
                                Rp {{ number_format($totalPrice - $transaction->discount) }}
                            </div>
                            <div class="col-6">
                                <strong><i class="fas fa-wallet"></i> Uang Diterima:</strong><br>
                                Rp {{ number_format($transaction->purchase_price) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong><i class="fas fa-hand-holding-usd"></i> Status:</strong><br>
                                {{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'Kembalian' : 'Hutang' }}<br>
                                (Rp {{ number_format(abs($transaction->purchase_price - ($totalPrice - $transaction->discount))) }})
                            </div>
                            <div class="col-6">
                                <strong><i class="fas fa-credit-card"></i> Pembayaran:</strong><br>
                                {{ $transaction->payment_method }}
                            </div>
                        </div>
                    </div>
                    <div class="invoice-footer">
                        Terima kasih atas kepercayaan Anda!
                        @if (Auth::user()->jurusan == 'TSM')
                        <small>Hubungi kami: +62 857-1546-7500</small>
                        @elseif(Auth::user()->jurusan == 'TKRO')
                        <small>Hubungi kami: +62 858-8353-3001</small>
                        @endif
                    </div>
                </div>
            </body>
            </html>
            `;
                    
            // Buat iframe tersembunyi untuk cetak
            const iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            document.body.appendChild(iframe);
                    
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(template);
            doc.close();
                    
            // Tunggu hingga iframe termuat, lalu print dan hapus iframe
            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                setTimeout(() => {
                    document.body.removeChild(iframe);
                }, 1000);
            };
        });
    });
    </script>
    

<style>
.toast-message {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #28a745;
    color: white;
    padding: 12px 18px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    font-weight: bold;
    font-size: 1rem;
    text-align: center;
}
.toast-message.show {
    opacity: 1;
    transform: translateX(-50%) translateY(-10px);
}
@media print {
    body * {
        visibility: hidden;
    }
    .printable, .printable * {
        visibility: visible;
    }
    .printable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none;
    }
}
</style>
@endsection