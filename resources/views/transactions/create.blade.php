@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-wrench"></i> Tambah Informasi Sparepart</h5>
                    <small><b>*</b> Hapus jika tidak diperlukan</small>
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

                    @if ($spareparts->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle"></i> Tidak ada sparepart yang tersedia.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="sparepartTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nama Sparepart</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="addRow">+ Tambah Sparepart</button>
                    @endif
                    
                    @csrf
                    <input type="hidden" name="jurusan" value="{{ Auth::user()->jurusan }}">
                    <div class="row mt-3">
                        <div class="col-md-6 mt-1">
                            <label for="transaction_date"><i class="bi bi-calendar-event"></i> Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" id="transaction_date" class="form-control mt-2" value="{{ old('transaction_date', now()->toDateString()) }}" required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label for="transaction_type"><i class="bi bi-arrow-up-down"></i> Jenis Transaksi</label>
                            <select name="transaction_type" id="transaction_type" class="form-control mt-2" required>
                                <option value="purchase" {{ old('transaction_type', 'sale') == 'purchase' ? 'selected' : '' }}>Pembelian</option>
                                <option value="sale" {{ old('transaction_type', 'sale') == 'sale' ? 'selected' : '' }}>Penjualan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6 mt-1">
                            <label for="purchase_price"><i class="bi bi-credit-card"></i> Uang Masuk</label>
                            <input type="text" id="purchase_price" class="form-control mt-2" value="{{ old('purchase_price', 0) }}" required>
                            <input type="hidden" name="purchase_price[]" id="purchase_price_asli" value="{{ old('purchase_price', 0) }}">
                        </div>                    
                        <div class="col-md-6 mt-1">
                            <label for="total_price"><i class="bi bi-wallet2"></i> Total Biaya</label>
                            <input type="text" id="total_price" class="form-control mt-2" value="{{ old('total_price', 0) }}" readonly>
                            <input type="hidden" name="total_price" id="total_price_asli">
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6 mt-1">
                            <label for="change"><i class="bi bi-cash-coin"></i> Kembalian</label>
                            <input type="text" id="change" class="form-control mt-2" readonly>
                            <input type="hidden" id="change_asli">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success" onclick="return confirmSubmit()">
                            <i class="bi bi-save"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                const paymentReceived = parseFloat(unformat(document.getElementById('purchase_price_asli').value)) || 0;
                const totalCost = parseFloat(unformat(document.getElementById('total_price').value)) || 0;
                const change = paymentReceived - totalCost;
    
                document.getElementById('change').value = formatRupiah(change);
                document.getElementById('change_asli').value = change;
            }
    
            $(document).ready(function () {
                $(".js-example-basic-single, .js-example-basic-multiple, .sparepart_id").select2({ width: '100%' });
    
                $('#addRow').on('click', function () {
                    const newRow = `
                        <tr>
                            <td>
                                <select name="sparepart_id[]" class="form-control sparepart_id" required>
                                    <option value="">Pilih Sparepart</option>
                                    @foreach ($spareparts->where('jurusan', Auth::user()->jurusan) as $sparepart)
                                        <option value="{{ $sparepart->id_sparepart }}" data-harga="{{ $sparepart->harga_jual }}">
                                            {{ $sparepart->nama_sparepart }} {{ $sparepart->spek }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control harga" readonly></td>
                            <td><input type="number" name="quantity[]" class="form-control jumlah" min="1" required></td>
                            <td><input type="text" class="form-control subtotal" readonly></td>
                            <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                        </tr>
                    `;
                    $('#sparepartTable tbody').append(newRow);
                    $('.sparepart_id').select2({ width: '100%' });
                });
    
                $('#sparepartTable').on('change', '.sparepart_id', function () {
                    const row = $(this).closest('tr');
                    const price = $(this).find(':selected').data('harga') || 0;
                    row.find('.harga').val(formatRupiah(price));
                    calculateSubtotal(row[0]);
                });
    
                $('#sparepartTable').on('input', '.jumlah', function () {
                    calculateSubtotal($(this).closest('tr')[0]);
                });
    
                $('#sparepartTable').on('click', '.remove-row', function () {
                    $(this).closest('tr').remove();
                    updateTotalCost();
                });
    
                $('#purchase_price').on('input', function () {
                    this.value = formatRupiah(this.value.replace(/\D/g, ""));
                    $('#purchase_price_asli').val(unformat(this.value));
                    updateChange();
                });
    
                $('#transactionForm').on('submit', function (e) {
                    let valid = true;
                    $('select[name="sparepart_id[]"]').each(function () {
                        if (!$(this).val()) {
                            alert('Pilih Sparepart terlebih dahulu.');
                            valid = false;
                            return false;
                        }
                    });
                    $('input[name="quantity[]"]').each(function () {
                        if (!$(this).val() || $(this).val() <= 0) {
                            alert('Jumlah harus lebih dari 0.');
                            valid = false;
                            return false;
                        }
                    });
                    if (!valid) e.preventDefault();
                });
            });
        });
    </script>    
    <script>
        function confirmSubmit() {
            return confirm('Apakah Anda yakin ingin menyimpan transaksi ini?');
        }
    </script>
@endsection