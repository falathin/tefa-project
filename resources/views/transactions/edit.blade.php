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
                                    @foreach ($transaction->spareparts as $sparepart)
                                        <tr>
                                            <td>
                                                <select name="sparepart_id[]" class="form-control sparepart_id" required>
                                                    <option value="">Pilih Sparepart</option>
                                                    @foreach ($spareparts as $availableSparepart)
                                                        <option value="{{ $availableSparepart->id_sparepart }}"
                                                                data-harga="{{ $availableSparepart->harga_jual }}"
                                                                @if ($sparepart->id_sparepart == $availableSparepart->id_sparepart) selected @endif>
                                                            {{ $availableSparepart->nama_sparepart }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control harga"
                                                    value="{{ $sparepart->harga_jual }}" readonly></td>
                                            <td><input type="number" name="quantity[]" class="form-control jumlah"
                                                    value="{{ $sparepart->pivot->quantity ?? 1 }}" min="1" required></td>
                                            <td><input type="text" class="form-control subtotal"
                                                    value="{{ $sparepart->pivot->subtotal ?? 0 }}" readonly></td>
                                            <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
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
                        <label for="total_cost">
                            <i class="bi bi-wallet2"></i> Total Biaya
                        </label>
                        <input type="text" id="total_cost" class="form-control"
                            value="{{ old('total_cost', $transaction->total_cost) }}" readonly>
                    </div>

                    <div class="form-group mt-3">
                        <label for="payment_received">
                            <i class="bi bi-credit-card"></i> Uang Masuk
                        </label>
                        <input type="number" name="payment_received" id="payment_received" class="form-control"
                            min="0" value="{{ old('payment_received', $transaction->payment_received) }}">
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
            const transactionDateInput = document.getElementById('transaction_date');
            if (!transactionDateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                transactionDateInput.value = today;
            }

            function calculateSubtotal(row) {
                const price = parseFloat(row.querySelector('.harga').value) || 0;
                const quantity = parseFloat(row.querySelector('.jumlah').value) || 0;
                const subtotal = price * quantity;
                row.querySelector('.subtotal').value = subtotal.toFixed(2);
                updateTotalCost();
            }

            function updateTotalCost() {
                const totalSparepart = Array.from(document.querySelectorAll('#sparepartTable tbody tr')).reduce((sum, row) => {
                    const price = parseFloat(row.querySelector('.harga').value.replace(/[^0-9.-]+/g, "")) || 0;
                    const quantity = parseInt(row.querySelector('.jumlah').value) || 0;
                    return sum + (price * quantity);
                }, 0);
                document.getElementById('total_cost').value = totalSparepart.toFixed(2);
                updateChange();
            }

            function updateChange() {
                const paymentReceived = parseFloat(document.getElementById('payment_received').value) || 0;
                const totalCost = parseFloat(document.getElementById('total_cost').value) || 0;
                document.getElementById('change').value = (paymentReceived - totalCost).toFixed(2);
            }

            document.getElementById('addRow').addEventListener('click', function() {
                const tableBody = document.querySelector('#sparepartTable tbody');
                const row = tableBody.insertRow();
                row.innerHTML = ` 
                    <td>
                        <select name="sparepart_id[]" class="form-control sparepart_id" required>
                            <option value="">Pilih Sparepart</option>
                            @foreach ($spareparts as $sparepart)
                                <option value="{{ $sparepart->id_sparepart }}" data-harga="{{ $sparepart->harga_jual }}">
                                    {{ $sparepart->nama_sparepart }}
                                </option> 
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control harga" readonly></td>
                    <td><input type="number" name="quantity[]" class="form-control jumlah" min="1" required></td>
                    <td><input type="text" class="form-control subtotal" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                `;

                const newRow = tableBody.lastElementChild;
                newRow.querySelector('.sparepart_id').addEventListener('change', function() {
                    const price = this.options[this.selectedIndex].getAttribute('data-harga') || 0;
                    newRow.querySelector('.harga').value = parseFloat(price).toFixed(2);
                    calculateSubtotal(newRow);
                });
                newRow.querySelector('.jumlah').addEventListener('input', function() {
                    calculateSubtotal(newRow);
                });

                updateTotalCost();
            });

            document.querySelector('#sparepartTable').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-row')) {
                    event.target.closest('tr').remove();
                    updateTotalCost();
                }
            });

            document.querySelector('#sparepartTable').addEventListener('change', function(event) {
                if (event.target.classList.contains('sparepart_id') || event.target.classList.contains('jumlah')) {
                    const row = event.target.closest('tr');
                    calculateSubtotal(row);
                }
            });

            document.getElementById('payment_received').addEventListener('input', updateChange);

            updateTotalCost();
            updateChange();
        });
    </script>
@endsection