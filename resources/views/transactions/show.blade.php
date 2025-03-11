@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">
            <i class="fas fa-tools"></i> Detail Transaksi Sparepart {{ $transaction->name}}
        </h2>

        <div class="row mt-4 g-3">
            <div class="col-md-6 col-lg-3">
                <div class="card text-white bg-success shadow-sm p-3 text-center">
                    <h5 class="fw-bold mb-2"><i class="fas fa-shopping-cart"></i> Total Harga</h5>
                    <p class="fs-4 fw-bold mb-0">Rp {{ number_format($subtotalBeforeDiscount) }}</p>
                </div>
            </div>
        
            <div class="col-md-6 col-lg-3">
                <div class="card text-white bg-info shadow-sm p-3 text-center">
                    <h5 class="fw-bold mb-2"><i class="fas fa-percent"></i> Diskon</h5>
                    <p class="fs-4 fw-bold mb-0">Rp {{ number_format($transaction->discount) }}</p>
                </div>
            </div>
        
            <div class="col-md-6 col-lg-3">
                <div class="card text-white bg-secondary shadow-sm p-3 text-center">
                    <h5 class="fw-bold mb-2"><i class="fas fa-calculator"></i> Total Setelah Diskon</h5>
                    <p class="fs-4 fw-bold mb-0">Rp {{ number_format($totalPrice - $transaction->discount) }}</p>
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
                <div class="card text-white shadow-sm p-3 text-center 
                    {{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'bg-success' : 'bg-danger' }}">
                    <h5 class="fw-bold mb-2">
                        <i class="fas {{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'fa-money-bill-wave' : 'fa-exclamation-circle' }}"></i> 
                        {{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'Kembalian' : 'Hutang' }}
                    </h5>
                    <p class="fs-4 fw-bold mb-0">Rp {{ number_format(abs($transaction->purchase_price - ($totalPrice - $transaction->discount))) }}</p>
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
                            <td>{{ $sparepart->sparepart->nama_sparepart }}</td>
                            <td>{{ $sparepart->sparepart->spek }}</td>
                            <td class="fw-bold text-center">{{ $sparepart->quantity }}</td>
                            <td>Rp {{ number_format($sparepart->sparepart->harga_beli) }}</td>
                            <td class="text-success">Rp {{ number_format($sparepart->sparepart->harga_jual) }}</td>
                            <td class="text-warning">Rp {{ number_format($sparepart->sparepart->keuntungan) }}</td>
                            <td class="text-danger fw-bold">Rp {{ number_format($sparepart->quantity * $sparepart->sparepart->harga_jual) }}</td>
                            <td>
                                <button class="btn btn-sm btn-secondary copy-btn" data-text="{{ $sparepart->sparepart->nama_sparepart }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button class="btn btn-warning" id="copyAll">
                <i class="fas fa-clipboard"></i> Salin Semua
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("tr").forEach((row, index) => {
            setTimeout(() => {
                row.classList.add("animate__animated", "animate__fadeInUp");
            }, index * 100);
        });

        document.querySelectorAll(".copy-btn").forEach(button => {
            button.addEventListener("click", function() {
                const text = this.dataset.text;
                navigator.clipboard.writeText(text).then(() => {
                    showToast(`📋 Teks disalin! ✅`);
                });
            });
        });

        document.getElementById("copyAll").addEventListener("click", function() {
            let text = "📌 **Laporan Transaksi Sparepart**\n";
            text += "===================================\n\n";
            
            text += `🧾 **Total Harga**: Rp {{ number_format($totalPrice) }}\n`;
            text += `💵 **Uang Diterima**: Rp {{ number_format($transaction->purchase_price) }}\n`;
            text += `💰 **{{ $transaction->purchase_price >= ($totalPrice - $transaction->discount) ? 'Kembalian' : 'Hutang' }}**: Rp {{ number_format(abs($transaction->purchase_price - ($totalPrice - $transaction->discount))) }}\n`;
            text += "===================================\n\n";

            @foreach($transaction->transactionSpareparts as $index => $trans)
            text += `🛠️ **Transaksi #{{ $index + 1 }}**\n`;
            text += `📌 Nama Sparepart: {{ $trans->sparepart->name }}\n`;
            text += `🔍 Spesifikasi: {{ $trans->sparepart->specification }}\n`;
            text += `📦 Jumlah: {{ $trans->quantity }} unit\n`;
            text += `💰 Harga Beli: Rp {{ number_format($trans->sparepart->harga_beli) }}\n`;
            text += `💵 Harga Jual: Rp {{ number_format($trans->sparepart->harga_jual) }}\n`;
            text += `📈 Keuntungan: Rp {{ number_format($trans->sparepart->keuntungan) }}\n`;
            text += `🧮 Subtotal: Rp {{ number_format($trans->subtotal) }}\n`;
            text += "-----------------------------------\n\n";
            @endforeach

            navigator.clipboard.writeText(text).then(() => {
                showToast("🚀 Laporan transaksi telah disalin! ✅");
            });
        });

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
</style>

@endsection