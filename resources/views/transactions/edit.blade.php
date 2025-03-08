@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" id="transactionForm">
                @method('PUT')

                <div class="card-header mt-3 rounded bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-wrench"></i> &nbsp; Edit Informasi Sparepart</h5>
                    <small class="text-right"><b>*</b> Hapus jika tidak diperlukan</small>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle"></i>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (empty($spareparts) || count($spareparts) == 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle"></i> Tidak ada sparepart yang tersedia.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" id="sparepartTable">
                                <thead>
                                    <tr>
                                        <th>Nama Sparepart</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
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
                                                    value="{{ number_format($detail['quantity']) }}" min="1"
                                                    required>
                                            </td>

                                            <td>
                                                <input type="text" class="form-control subtotal"
                                                    value="{{ number_format($detail['subtotal']) }}" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <br>
                        <button type="button" class="btn btn-primary" id="addRow">+ Tambah Sparepart</button>
                    @endif

                    @csrf
                    <div class="form-group mt-3">
                        <label for="transaction_date">
                            <i class="bi bi-calendar-event"></i> Tanggal Transaksi
                        </label>
                        <input type="date" name="transaction_date" id="transaction_date" class="form-control"
                            value="{{ old('transaction_date', \Carbon\Carbon::parse($transaction->transaction_date)->toDateString()) }}"
                            required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="transaction_type">
                            <i class="bi bi-arrow-up-down"></i> Jenis Transaksi
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

                    <div class="form-group mt-3">
                        <label for="total_price">
                            <i class="bi bi-wallet2"></i> Total Biaya
                        </label>
                        <input type="text" id="total_price" class="form-control"
                            value="{{ old('total_price', $transaction->total_price) }}" readonly>
                        <input type="hidden" id="total_price_asli" name="total_price">
                    </div>

                    <div class="form-group mt-3">
                        <label for="purchase_price">
                            <i class="bi bi-credit-card"></i> Uang Masuk
                        </label>
                        <input type="text" id="purchase_price" class="form-control" min="0"
                            value="{{ old('purchase_price', $transaction->purchase_price) }}">
                        <input type="hidden" id="purchase_price_asli" name="purchase_price">
                    </div>

                    <div class="form-group mt-3">
                        <label for="change">
                            <i class="bi bi-cash-coin"></i> Kembalian
                        </label>
                        <input type="text" id="change" class="form-control"
                            value="{{ old('change', $transaction->change) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-success mt-4">
                        <i class="bi bi-save"></i> Simpan Transaksi
                    </button>
            </form>
        </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function purchasePrice() {
                let a = formatRupiah(document.getElementById('purchase_price').value);
                document.getElementById("purchase_price_asli").value = unformat(a);
                document.getElementById("purchase_price").value = a;
            }

            function formatRibuan(angka) {
                return new Intl.NumberFormat("id-ID").format(angka);
            }

            function formatRupiah(angka) {
                return "Rp " + formatRibuan(angka);
            }

            function unformat(angka) {
                return parseInt(angka.replace(/\D/g, "")) || 0;
            }

            const transactionDateInput = document.getElementById('transaction_date');
            if (!transactionDateInput.value) {
                transactionDateInput.value = new Date().toISOString().split('T')[0];
            }

            function calculateSubtotal(row) {
                const price = parseFloat(unformat(row.querySelector('.harga').value)) || 0;
                const quantity = parseFloat(row.querySelector('.jumlah').value) || 0;
                const subtotal = price * quantity;
                row.querySelector('.subtotal').value = formatRupiah(subtotal);
                updateTotalCost();
            }

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

            function updateChange() {
                const paymentReceived = parseFloat(unformat(document.getElementById('purchase_price_asli')
                    .value)) || 0;
                const totalCost = parseFloat(unformat(document.getElementById('total_price').value)) || 0;
                const change = paymentReceived - totalCost;

                document.getElementById('change').value = formatRupiah(change);
                document.getElementById('change_asli').value = change;
            }

            document.getElementById('addRow').addEventListener('click', function() {
                // Ambil semua sparepart_id yang sudah dipilih
                const selectedIds = Array.from(document.querySelectorAll('.sparepart_id'))
                    .map(select => select.value)
                    .filter(value => value !== "");

                const tableBody = document.querySelector('#sparepartTable tbody');
                const row = tableBody.insertRow();

                // Bangun opsi dengan filter
                let optionsHtml = '<option value="">Pilih Sparepart</option>';
                @foreach ($spareparts as $sparepart)
                    if (!selectedIds.includes("{{ $sparepart->id_sparepart }}")) {
                        optionsHtml += `
                <option 
                    value="{{ $sparepart->id_sparepart }}" 
                    data-harga="{{ $sparepart->harga_jual }}"
                >
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
        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
    `;

                // Tambahkan event listener untuk update opsi saat ada perubahan
                const newSelect = row.querySelector('.sparepart_id');
                newSelect.addEventListener('change', function() {
                    updateAllSelectOptions();
                });

                updateAllSelectOptions();
            });

            // Fungsi untuk update semua opsi select
            function updateAllSelectOptions() {
                const allSelects = document.querySelectorAll('.sparepart_id');
                const selectedIds = Array.from(allSelects)
                    .map(select => select.value)
                    .filter(value => value !== "");

                allSelects.forEach(select => {
                    const currentValue = select.value;
                    const options = Array.from(select.options);

                    // Sembunyikan opsi yang dipilih di select lain
                    options.forEach(option => {
                        if (option.value && option.value !== currentValue) {
                            option.hidden = selectedIds.includes(option.value);
                        }
                    });
                });
            }

            // Panggil fungsi saat halaman pertama kali load
            document.addEventListener('DOMContentLoaded', function() {
                updateAllSelectOptions();
            });

            document.querySelector('#sparepartTable').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-row')) {
                    event.target.closest('tr').remove();
                    updateTotalCost();
                }
            });

            document.querySelector('#sparepartTable').addEventListener('change', function(event) {
                if (event.target.classList.contains('sparepart_id') || event.target.classList.contains(
                        'jumlah')) {
                    const row = event.target.closest('tr');
                    calculateSubtotal(row);
                }
            });

            document.getElementById('purchase_price').addEventListener('input', function() {
                this.value = formatRupiah(this.value.replace(/\D/g, ""));
                document.getElementById('purchase_price_asli').value = unformat(this.value);
                updateChange();
            });

            purchasePrice();
            updateTotalCost();
            updateChange();
        });
    </script>
@endsection
