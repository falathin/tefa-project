@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="card shadow">
            @php
                // Ambil nama-nama customer sesuai jurusan user dan cek apakah nama transaksi sudah termasuk di sana
                $customerNames = $customers
                    ->where('jurusan', Auth::user()->jurusan)
                    ->pluck('name')
                    ->toArray();
                $isExistingCustomer = in_array($transaction->name, $customerNames);
            @endphp

            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" id="transactionForm">
                @method('PUT')
                @csrf

                <div class="card">
                    <!-- Header Form -->
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-wrench text-warning"></i> &nbsp; Edit Informasi Sparepart & Transaksi
                        </h5>
                        <small class="text-white"><b>*</b> Hapus jika tidak diperlukan</small>
                    </div>

                    <div class="card-body">
                        <!-- Notifikasi -->
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

                        <!-- Detail Transaksi -->
                        <section class="mb-4">
                            <h5 class="text-primary mb-3">Detail Transaksi</h5>

                            <!-- Pilih Metode Input Nama Pelanggan -->
                            <div class="row">
                                <div class="col-12">
                                    <label>
                                        <i class="bi bi-person"></i> Pilih Metode Input Nama Pelanggan
                                    </label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_option" id="option_existing"
                                            value="existing" {{ $isExistingCustomer ? 'checked' : '' }}>
                                        <label class="form-check-label" for="option_existing">Pilih dari data yang sudah ada</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_option" id="option_manual"
                                            value="manual" {{ !$isExistingCustomer ? 'checked' : '' }}>
                                        <label class="form-check-label" for="option_manual">Isi secara manual</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Nama Pelanggan -->
                            <div class="row mt-2">
                                <div class="col-12" id="customerInputField">
                                    <div id="existingCustomerDiv" class="mb-2"
                                        style="{{ $isExistingCustomer ? '' : 'display:none;' }}">
                                        <label for="customer_name_existing">
                                            <i class="bi bi-person"></i> Nama Pelanggan
                                        </label>
                                        <select id="customer_name_existing" class="form-control select2 mt-2" name="name" required>
                                            <option value="">Pilih Customer</option>
                                            @foreach ($customers->where('jurusan', Auth::user()->jurusan) as $customer)
                                                <option value="{{ $customer->name }}"
                                                    {{ $isExistingCustomer && $customer->name == $transaction->name ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="manualCustomerDiv" class="mb-2"
                                        style="{{ $isExistingCustomer ? 'display:none;' : '' }}">
                                        <label for="customer_name_manual">
                                            <i class="bi bi-person"></i> Nama Pelanggan
                                        </label>
                                        <input type="text" id="customer_name_manual" class="form-control mt-2"
                                            placeholder="Masukkan nama pelanggan"
                                            value="{{ !$isExistingCustomer ? $transaction->name : '' }}"
                                            {{ !$isExistingCustomer ? 'required' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Transaksi & Jenis Transaksi -->
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label for="transaction_date">
                                        <i class="bi bi-calendar-event-fill text-info"></i> Tanggal Transaksi
                                    </label>
                                    <input type="date" name="transaction_date" id="transaction_date" class="form-control mt-2"
                                        value="{{ old('transaction_date', \Carbon\Carbon::parse($transaction->transaction_date)->toDateString()) }}"
                                        required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="transaction_type">
                                        <i class="bi bi-arrow-up-down text-info"></i> Jenis Transaksi
                                    </label>
                                    <select name="transaction_type" id="transaction_type" class="form-control mt-2" required>
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

                            <!-- Metode Pembayaran -->
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label for="payment_method">
                                        <i class="bi bi-credit-card-2-front text-info"></i> Metode Pembayaran
                                    </label>
                                    <select name="payment_method" id="payment_method" class="form-control mt-2" required>
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

                            <!-- Diskon & Total Biaya Setelah Diskon -->
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label for="discount">
                                        <i class="bi bi-tags-fill text-info"></i> Diskon (%)
                                    </label>
                                    <input type="text" name="discount" id="discount" class="form-control mt-2"
                                        value="{{ $transaction->discount }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    @php
                                        $totalSetelahDiskon = $subtotalBeforeDiscount * ((100 - $transaction->discount) / 100);
                                    @endphp
                                    <label for="total_price">
                                        <i class="bi bi-wallet2 text-info"></i> Total Biaya Setelah Diskon
                                    </label>
                                    <input type="text" id="total_price" class="form-control mt-2"
                                        value="Rp {{ number_format($totalSetelahDiskon) }}" readonly>
                                    <input type="hidden" id="total_price_asli" name="total_price"
                                        value="{{ $totalSetelahDiskon }}">
                                </div>
                            </div>

                            <!-- Uang Masuk & Kembalian/Hutang -->
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label for="purchase_price">
                                        <i class="bi bi-credit-card text-info"></i> Uang Masuk
                                    </label>
                                    <input type="text" id="purchase_price" class="form-control mt-2" min="0"
                                        value="Rp {{ number_format($transaction->purchase_price) }}">
                                    <input type="hidden" id="purchase_price_asli" name="purchase_price">
                                </div>
                                <div class="col-12 col-md-6">
                                    @php
                                        $kembalian = $transaction->purchase_price - $totalSetelahDiskon;
                                    @endphp
                                    <label for="change">
                                        <i class="bi bi-cash-coin text-info"></i> Kembalian / Hutang
                                    </label>
                                    <input type="text" id="change" class="form-control mt-2"
                                        value="Rp {{ number_format(abs($kembalian)) }}" readonly>
                                </div>
                            </div>
                        </section>

                        <!-- Informasi Sparepart -->
                        <section class="mb-4">
                            <h5 class="text-primary mb-3">Informasi Sparepart</h5>
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
                        </section>

                        <!-- Tombol Submit -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success" onclick="return confirmSubmit()">
                                <i class="bi bi-save-fill"></i> Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Include jQuery dan Select2 (jangan rubah script) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#customer_name_existing').select2({ width: '100%' });
                    function toggleCustomerInput() {
                        if ($('input[name="customer_option"]:checked').val() === 'existing') {
                            $('#existingCustomerDiv').show();
                            $('#customer_name_existing').prop('name', 'name').prop('required', true);
                            $('#manualCustomerDiv').hide();
                            $('#customer_name_manual').removeAttr('name').prop('required', false);
                        } else {
                            $('#manualCustomerDiv').show();
                            $('#customer_name_manual').prop('name', 'name').prop('required', true);
                            $('#existingCustomerDiv').hide();
                            $('#customer_name_existing').removeAttr('name').prop('required', false);
                        }
                    }
                    toggleCustomerInput();
                    $('input[name="customer_option"]').on('change', toggleCustomerInput);
                });
            </script>
        </div>
    </div>
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi helper format
            function formatRibuan(angka) {
                return new Intl.NumberFormat("id-ID").format(angka);
            }

            function formatRupiah(angka) {
                return "Rp " + formatRibuan(angka);
            }

            function unformat(angka) {
                // Menghapus karakter non-numerik, misalnya "Rp", spasi, koma, titik
                return parseFloat(angka.replace(/[Rp\s,.]/g, "")) || 0;
            }

            // Hitung subtotal sparepart dan update total biaya setelah diskon persen
            function updateTotalCost() {
                let totalSparepart = 0;
                document.querySelectorAll('#sparepartTable tbody tr').forEach(row => {
                    const price = parseFloat(unformat(row.querySelector('.harga').value)) || 0;
                    const quantity = parseInt(row.querySelector('.jumlah').value) || 0;
                    totalSparepart += price * quantity;
                });
                // Ambil nilai diskon persen dari input (misal: 10 untuk 10%)
                const discountPercent = parseFloat(document.getElementById('discount').value) || 0;
                const totalAfterDiscount = totalSparepart * ((100 - discountPercent) / 100);
                document.getElementById('total_price').value = formatRupiah(totalAfterDiscount);
                document.getElementById('total_price_asli').value = totalAfterDiscount;
                updateChange();
            }

            // Hitung kembalian/hutang berdasarkan uang masuk dan total biaya setelah diskon
            function updateChange() {
                const paymentReceived = parseFloat(unformat(document.getElementById('purchase_price').value)) || 0;
                const totalCost = parseFloat(unformat(document.getElementById('total_price').value)) || 0;
                const change = paymentReceived - totalCost;
                document.getElementById('change').value = formatRupiah(change);
            }

            // Event listener untuk input diskon (sebagai persen)
            document.getElementById('discount').addEventListener('input', function() {
                // Tidak perlu diformat seperti rupiah karena ini persen
                updateTotalCost();
            });

            // Event listener lainnya (contoh: untuk sparepart, uang masuk, dll.)
            $(document).ready(function() {
                // Event: Tambah baris sparepart
                $(document).on('click', '#addRow', function() {
                    var availableCount =
                        {{ $spareparts->where('jurusan', Auth::user()->jurusan)->count() }};
                    var currentCount = $('.sparepart_id').length;
                    if (currentCount >= availableCount) {
                        alert('Sparepart habis!');
                        return;
                    }
                    const tableBody = document.querySelector('#sparepartTable tbody');
                    const newRow = document.createElement('tr');

                    let optionsHtml = '<option value="">Pilih Sparepart</option>';
                    @foreach ($spareparts->where('jurusan', Auth::user()->jurusan) as $sparepart)
                        optionsHtml += ` 
                            <option value="{{ $sparepart->id_sparepart }}" data-harga="{{ $sparepart->harga_jual }}">
                                {{ $sparepart->nama_sparepart }}
                            </option>`;
                    @endforeach

                    newRow.innerHTML = `
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
                    tableBody.appendChild(newRow);
                    $(newRow.querySelector('.sparepart_id')).select2({
                        width: '100%'
                    });
                    updateAllSelectOptions();
                });

                // Event: Perubahan pada select sparepart atau input quantity
                $('#sparepartTable').on('change', '.sparepart_id, .jumlah', function(e) {
                    const row = $(this).closest('tr')[0];
                    if ($(this).hasClass('sparepart_id')) {
                        const selectedOption = this.options[this.selectedIndex];
                        const price = selectedOption.getAttribute('data-harga') || 0;
                        row.querySelector('.harga').value = formatRupiah(price);
                    }
                    calculateSubtotal(row);
                    updateAllSelectOptions();
                });

                // Event: Hapus baris sparepart
                $('#sparepartTable').on('click', '.remove-row', function() {
                    $(this).closest('tr').remove();
                    updateTotalCost();
                    updateAllSelectOptions();
                });

                // Format input uang masuk dan update change
                $('#purchase_price').on('input', function() {
                    let value = unformat($(this).val());
                    $(this).val(formatRupiah(value));
                    $('#purchase_price_asli').val(value);
                    updateChange();
                });

                let initialValue = unformat($('#purchase_price').val());
                $('#purchase_price').val(formatRupiah(initialValue));
                $('#purchase_price_asli').val(initialValue);

                // Validasi form sebelum submit
                $('#transactionForm').on('submit', function(e) {
                    if ($('.sparepart_id').length === 0) {
                        alert('Harap tambahkan minimal 1 sparepart.');
                        e.preventDefault();
                        return false;
                    }
                });

                updateAllSelectOptions();
                updateTotalCost();
                updateChange();
            });

            // Fungsi tambahan: Hitung subtotal untuk satu baris sparepart
            function calculateSubtotal(row) {
                const priceStr = row.querySelector('.harga').value;
                const price = parseFloat(unformat(priceStr)) || 0;
                const quantity = parseInt(row.querySelector('.jumlah').value) || 0;
                const subtotal = price * quantity;
                row.querySelector('.subtotal').value = formatRupiah(subtotal);
                updateTotalCost();
            }

            // Fungsi tambahan: Update opsi pada select sparepart agar opsi yang sudah dipilih tidak muncul di select lainnya
            function updateAllSelectOptions() {
                const allSelects = document.querySelectorAll('.sparepart_id');
                const selectedIds = Array.from(allSelects)
                    .map(select => select.value)
                    .filter(value => value !== "");
                allSelects.forEach(select => {
                    const currentValue = select.value;
                    Array.from(select.options).forEach(option => {
                        if (option.value && option.value !== currentValue && selectedIds.includes(
                                option.value)) {
                            option.disabled = true;
                            option.hidden = true;
                        } else {
                            option.disabled = false;
                            option.hidden = false;
                        }
                    });
                });
            }
        });
    </script>
@endsection