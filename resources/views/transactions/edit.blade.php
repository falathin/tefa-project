@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="card shadow">
            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" id="transactionForm">
                @method('PUT')
                @csrf

                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-wrench text-warning"></i> &nbsp; Edit Informasi Sparepart
                    </h5>
                    <small class="text-white"><b>*</b> Hapus jika tidak diperlukan</small>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill text-success"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle-fill text-danger"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (empty($spareparts) || count($spareparts) == 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill text-warning"></i> Tidak ada sparepart yang tersedia.
                        </div>
                    @else
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered" id="sparepartTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center">Nama Sparepart</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactionDetails as $detail)
                                        <tr>
                                            <td>
                                                <select name="sparepart_id[]" class="form-control sparepart_id" required>
                                                    @foreach ($spareparts as $sparepart)
                                                        <option value="{{ $sparepart->id_sparepart }}"
                                                            {{ $sparepart->id_sparepart == $detail['sparepart_id'] ? 'selected' : '' }}>
                                                            {{ $sparepart->nama_sparepart }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control harga"
                                                    value="{{ number_format($detail['harga_jual']) }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="quantity[]" class="form-control jumlah"
                                                    value="{{ $detail['quantity'] }}" min="1" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control subtotal"
                                                    value="{{ number_format($detail['subtotal']) }}" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger remove-row">
                                                    <i class="bi bi-trash-fill"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right mb-3">
                            <button type="button" class="btn btn-primary" id="addRow">
                                <i class="bi bi-plus-circle-fill"></i> Tambah Sparepart
                            </button>
                        </div>
                    @endif

                    <!-- Grid Form 2 kolom -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_date">
                                    <i class="bi bi-calendar-event-fill text-info"></i> Tanggal Transaksi
                                </label>
                                <input type="date" name="transaction_date" id="transaction_date" class="form-control"
                                    value="{{ old('transaction_date', \Carbon\Carbon::parse($transaction->transaction_date)->toDateString()) }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_type">
                                    <i class="bi bi-arrow-up-down text-info"></i> Jenis Transaksi
                                </label>
                                <select name="transaction_type" id="transaction_type" class="form-control" required>
                                    <option value="purchase"
                                        {{ old('transaction_type', $transaction->transaction_type) == 'purchase' ? 'selected' : '' }}>
                                        Pembelian
                                    </option>
                                    <option value="sale"
                                        {{ old('transaction_type', $transaction->transaction_type) == 'sale' ? 'selected' : '' }}>
                                        Penjualan
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method">
                                    <i class="bi bi-credit-card-2-front text-info"></i> Metode Pembayaran
                                </label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="cash"
                                        {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>
                                        Tunai
                                    </option>
                                    <option value="cooperative"
                                        {{ old('payment_method', $transaction->payment_method) == 'cooperative' ? 'selected' : '' }}>
                                        Kooperasi
                                    </option>
                                    <option value="administration"
                                        {{ old('payment_method', $transaction->payment_method) == 'administration' ? 'selected' : '' }}>
                                        Tata Usaha
                                    </option>
                                    <option value="transfer"
                                        {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'selected' : '' }}>
                                        Transfer
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subtotal_before_discount">
                                    <i class="bi bi-cash-stack text-info"></i> Subtotal Sebelum Diskon
                                </label>
                                <input type="text" id="subtotal_before_discount" class="form-control"
                                    value="Rp {{ number_format($subtotalBeforeDiscount) }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount">
                                    <i class="bi bi-tags-fill text-info"></i> Diskon
                                </label>
                                <input type="text" id="discount" class="form-control"
                                    value="Rp {{ number_format($transaction->discount) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_price">
                                    <i class="bi bi-wallet2 text-info"></i> Total Biaya Setelah Diskon
                                </label>
                                @php
                                    $totalSetelahDiskon = $subtotalBeforeDiscount - $transaction->discount;
                                @endphp
                                <input type="text" id="total_price" class="form-control"
                                    value="Rp {{ number_format($totalSetelahDiskon) }}" readonly>
                                <input type="hidden" id="total_price_asli" name="total_price" value="{{ $totalSetelahDiskon }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_price">
                                    <i class="bi bi-credit-card text-info"></i> Uang Masuk
                                </label>
                                <input type="text" id="purchase_price" class="form-control" min="0"
                                    value="Rp {{ number_format($transaction->purchase_price) }}">
                                <input type="hidden" id="purchase_price_asli" name="purchase_price">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="change">
                                    <i class="bi bi-cash-coin text-info"></i> Kembalian / Hutang
                                </label>
                                @php
                                    $kembalian = $transaction->purchase_price - $totalSetelahDiskon;
                                @endphp
                                <input type="text" id="change" class="form-control"
                                    value="Rp {{ number_format(abs($kembalian)) }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle-fill"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success" onclick="return confirmSubmit()">
                            <i class="bi bi-save-fill"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script untuk perhitungan dan validasi sparepart -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function formatRibuan(angka) {
                return new Intl.NumberFormat("id-ID").format(angka);
            }

            function formatRupiah(angka) {
                return "Rp " + formatRibuan(angka);
            }

            function unformat(angka) {
                return parseInt(angka.replace(/\D/g, "")) || 0;
            }

            // Perhitungan subtotal untuk baris sparepart
            function calculateSubtotal(row) {
                const price = parseFloat(unformat(row.querySelector('.harga').value)) || 0;
                const quantity = parseInt(row.querySelector('.jumlah').value) || 0;
                const subtotal = price * quantity;
                row.querySelector('.subtotal').value = formatRupiah(subtotal);
                updateTotalCost();
            }

            // Update total biaya
            function updateTotalCost() {
                let totalSparepart = 0;
                document.querySelectorAll('#sparepartTable tbody tr').forEach(row => {
                    const price = parseFloat(unformat(row.querySelector('.harga').value)) || 0;
                    const quantity = parseInt(row.querySelector('.jumlah').value) || 0;
                    totalSparepart += price * quantity;
                });
                document.getElementById('total_price').value = formatRupiah(totalSparepart);
                document.getElementById('total_price_asli').value = totalSparepart;
                updateChange();
            }

            // Update kembalian/hutang
            function updateChange() {
                const paymentReceived = parseFloat(unformat(document.getElementById('purchase_price_asli').value)) || 0;
                const totalCost = parseFloat(unformat(document.getElementById('total_price').value)) || 0;
                const change = paymentReceived - totalCost;
                document.getElementById('change').value = formatRupiah(change);
            }

            // Update opsi select sparepart supaya tidak ada duplikasi
            function updateAllSelectOptions() {
                const allSelects = document.querySelectorAll('.sparepart_id');
                const selectedIds = Array.from(allSelects)
                    .map(select => select.value)
                    .filter(value => value !== "");
                allSelects.forEach(select => {
                    const currentValue = select.value;
                    Array.from(select.options).forEach(option => {
                        if (option.value && option.value !== currentValue) {
                            option.hidden = selectedIds.includes(option.value);
                        } else {
                            option.hidden = false;
                        }
                    });
                });
            }

            // Event: Tambah baris sparepart
            document.getElementById('addRow').addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.sparepart_id'))
                    .map(select => select.value)
                    .filter(value => value !== "");

                if (selectedIds.length >= {{ $spareparts->count() }}) {
                    alert('Sparepart habis!');
                    return;
                }

                const tableBody = document.querySelector('#sparepartTable tbody');
                const row = tableBody.insertRow();

                let optionsHtml = '<option value="">Pilih Sparepart</option>';
                @foreach ($spareparts as $sparepart)
                    if (!selectedIds.includes("{{ $sparepart->id_sparepart }}")) {
                        optionsHtml += `
                            <option value="{{ $sparepart->id_sparepart }}" data-harga="{{ $sparepart->harga_jual }}">
                                {{ $sparepart->nama_sparepart }}
                            </option>
                        `;
                    }
                @endforeach

                row.innerHTML = `
                    <td>
                        <select name="sparepart_id[]" class="form-control sparepart_id" required>
                            ${optionsHtml}
                        </select>
                    </td>
                    <td><input type="text" class="form-control harga" readonly></td>
                    <td><input type="number" name="quantity[]" class="form-control jumlah" min="1" required></td>
                    <td><input type="text" class="form-control subtotal" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger remove-row">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                    </td>
                `;

                $(row.querySelector('.sparepart_id')).select2({ width: '100%' });
                updateAllSelectOptions();
            });

            // Event: Saat terjadi perubahan pada select sparepart atau input quantity
            document.querySelector('#sparepartTable').addEventListener('change', function(event) {
                if (event.target.classList.contains('sparepart_id') || event.target.classList.contains('jumlah')) {
                    const row = event.target.closest('tr');
                    if (event.target.classList.contains('sparepart_id')) {
                        const price = event.target.selectedOptions[0].getAttribute('data-harga') || 0;
                        row.querySelector('.harga').value = formatRupiah(price);
                    }
                    calculateSubtotal(row);
                    updateAllSelectOptions();
                }
            });

            // Event: Hapus baris sparepart
            document.querySelector('#sparepartTable').addEventListener('click', function(event) {
                if (event.target.closest('.remove-row')) {
                    event.target.closest('tr').remove();
                    updateTotalCost();
                    updateAllSelectOptions();
                }
            });

            // Format input uang masuk dan update nilai aslinya
            document.getElementById('purchase_price').addEventListener('input', function() {
                let value = unformat(this.value);
                this.value = formatRupiah(value);
                document.getElementById('purchase_price_asli').value = value;
                updateChange();
            });

            let initialValue = unformat(document.getElementById('purchase_price').value);
            document.getElementById('purchase_price').value = formatRupiah(initialValue);
            document.getElementById('purchase_price_asli').value = initialValue;

            updateAllSelectOptions();
            updateTotalCost();
            updateChange();
        });

        function confirmSubmit() {
            if (document.querySelectorAll('.sparepart_id').length === 0) {
                alert('Harap tambahkan minimal 1 sparepart.');
                return false;
            }
            return confirm('Apakah Anda yakin ingin menyimpan transaksi ini?');
        }
    </script>
@endsection