@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                @csrf
                <input type="hidden" name="jurusan" value="{{ Auth::user()->jurusan }}">
            
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
                                        <th>Nama Sparepart + Spek</th>
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
            
                    <!-- Pilih Metode Input Nama Pelanggan -->
                    <div class="row mt-3">
                        <div class="col-md-12" style="margin-left:50px;">
                            <label><i class="bi bi-person"></i> Pilih Metode Input Nama Pelanggan</label>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="radio" name="customer_option" id="option_existing" value="existing" checked>
                                <label class="form-check-label" for="option_existing">
                                    Pilih dari data yang sudah ada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="customer_option" id="option_manual" value="manual">
                                <label class="form-check-label" for="option_manual">
                                    Isi secara manual
                                </label>
                            </div>
                        </div>
                    </div>
            
                    <!-- Grid: Nama Pelanggan & Tanggal Transaksi -->
                    <div class="row mt-3">
                        <div class="col-md-6" id="customerFieldContainer">
                            <!-- Field untuk opsi "Pilih dari data yang sudah ada" -->
                            <div id="existingCustomerDiv">
                                <label for="customer_name_existing"><i class="bi bi-person"></i> Nama Pelanggan</label>
                                <select id="customer_name_existing" class="form-control select2 mt-2" name="name" required>
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers->where('jurusan', Auth::user()->jurusan) as $customer)
                                        <option value="{{ $customer->name }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Field untuk opsi "Isi secara manual" -->
                            <div id="manualCustomerDiv" style="display:none;">
                                <label for="customer_name_manual"><i class="bi bi-person"></i> Nama Pelanggan</label>
                                <input type="text" id="customer_name_manual" class="form-control mt-2" placeholder="Masukkan nama pelanggan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="transaction_date"><i class="bi bi-calendar-event"></i> Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" id="transaction_date" class="form-control mt-2"
                                value="{{ old('transaction_date', now()->toDateString()) }}" required>
                        </div>
                    </div>
            
                    <!-- Field tambahan -->
                    <div class="row mt-3">
                        <div class="col-md-6 mt-1">
                            <label for="transaction_type"><i class="bi bi-arrow-up-down"></i> Jenis Transaksi</label>
                            <select name="transaction_type" id="transaction_type" class="form-control mt-2" required>
                                {{-- <option value="purchase" {{ old('transaction_type', 'sale') == 'purchase' ? 'selected' : '' }} disabled>Pembelian</option> --}}
                                <option value="sale" {{ old('transaction_type', 'sale') == 'sale' ? 'selected' : '' }}>Penjualan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label for="payment_method"><i class="bi bi-credit-card-2-front"></i> Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-control mt-2" required>
                                <option value="Bayar Tunai">Bayar Cash</option>
                                <option value="Kooperasi">Kooperasi</option>
                                <option value="Tata Usaha">Tata Usaha</option>
                            </select>
                        </div>
                    </div>
            
                    <div class="row mt-3">
                        <div class="col-md-6 mt-1">
                            <label for="purchase_price"><i class="bi bi-credit-card"></i> Uang Masuk</label>
                            <input type="text" id="purchase_price" class="form-control mt-2" value="{{ old('purchase_price', 0) }}" required>
                            <input type="hidden" name="purchase_price" id="purchase_price_asli" value="{{ old('purchase_price', 0) }}">
                        </div>
                        <div class="col-md-6 mt-1">
                            <label for="discount"><i class="bi bi-tag"></i> Diskon (%)</label>
                            <input type="number" name="discount" id="discount" class="form-control mt-2" min="0" max="100">
                        </div>
                    </div>
            
                    <div class="row mt-3">
                        <div class="col-md-6 mt-1">
                            <label for="total_price"><i class="bi bi-wallet2"></i> Total Biaya</label>
                            <input type="text" id="total_price" class="form-control mt-2" value="{{ old('total_price', 0) }}" readonly>
                            <input type="hidden" name="total_price" id="total_price_asli">
                        </div>
                        <div class="col-md-6 mt-1">
                            <label for="change"><i class="bi bi-cash-coin"></i> Kembalian</label>
                            <input type="text" id="change" class="form-control mt-2" readonly>
                            <input type="hidden" id="change_asli">
                            <input type="hidden" id="sparepart_ids" name="sparepart_id">
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
            
            <!-- Include jQuery dan Select2 (jangan rubah script) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function() {
                    // Inisialisasi Select2 untuk dropdown
                    $('#customer_name_existing').select2({
                        width: '100%'
                    });
            
                    // Toggle antar opsi input dan required attribute
                    $('input[name="customer_option"]').on('change', function() {
                        if ($(this).val() === 'existing') {
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
                    });
                });
            </script>
                      
        </div>
    </div>

    <script>
        var selectedSprtIds = [];
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

            function updateSparepartOptions() {
                const selectedValues = new Set(
                    Array.from(document.querySelectorAll('.sparepart_id')).map(select => select.value)
                );
                document.querySelectorAll('.sparepart_id option').forEach(option => {
                    option.disabled = selectedValues.has(option.value) && option.value !== "";
                    $('#sparepart_ids').val(Array.from(selectedValues));
                });
            }

            function calculateSubtotal(row) {
                const price = parseFloat(unformat(row.querySelector('.harga').value)) || 0;
                const quantity = parseInt(row.querySelector('.jumlah').value) || 0;
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
                // Simpan nilai total asli (sebelum diskon) pada hidden field
                document.getElementById('total_price_asli').value = totalSparepart;
                updateDiscount(); // Perbarui total harga berdasarkan diskon
            }

            function updateDiscount() {
                // Ambil nilai total biaya asli (tidak berubah) dari hidden field
                let originalTotal = parseFloat(document.getElementById('total_price_asli').value) || 0;
                // Ambil nilai diskon dari input
                let discount = parseFloat(document.getElementById('discount').value) || 0;

                // Pastikan nilai diskon berada di antara 0 dan 100
                discount = Math.min(Math.max(discount, 0), 100);
                document.getElementById('discount').value = discount;

                // Hitung harga setelah diskon berdasarkan total asli
                let discountedPrice = originalTotal - (originalTotal * (discount / 100));

                // Tampilkan total harga yang telah didiskon
                document.getElementById('total_price').value = formatRupiah(discountedPrice);

                // Perbarui kembalian berdasarkan total harga yang didiskon
                updateChange();
            }

            // Fungsi update kembalian: hitung kembalian berdasarkan uang yang diterima dan harga diskon
            function updateChange() {
                const paymentReceived = parseFloat(unformat(document.getElementById('purchase_price').value)) || 0;
                let originalTotal = parseFloat(document.getElementById('total_price_asli').value) || 0;
                let discount = parseFloat(document.getElementById('discount').value) || 0;
                discount = Math.min(Math.max(discount, 0), 100);
                let discountedPrice = originalTotal - (originalTotal * (discount / 100));
                const change = paymentReceived - discountedPrice;

                document.getElementById('change').value = formatRupiah(change);
                document.getElementById('change_asli').value = change;
            }
            $(document).ready(function() {
                // Cek jika jumlah baris sparepart sudah mencapai jumlah sparepart yang tersedia
                $(document).on('click', '#addRow', function() {
                    var availableCount =
                        {{ $spareparts->where('jurusan', Auth::user()->jurusan)->count() }};
                    var currentCount = $('.sparepart_id').length;
                    if (currentCount >= availableCount) {
                        alert('Sparepart habis!');
                        return;
                    }
                    const newRow = `
                        <tr>
                            <td>
                                <select class="form-control sparepart_id" name="sparepart_id[]" required>
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
                        </tr>`;
                    $('#sparepartTable tbody').append(newRow);
                    $('.sparepart_id').select2({
                        width: '100%'
                    });
                    updateSparepartOptions();
                });
                $(document).on('change', '.sparepart_id', function() {
                    const row = $(this).closest('tr');
                    const price = $(this).find(':selected').data('harga') || 0;
                    row.find('.harga').val(formatRupiah(price));
                    calculateSubtotal(row[0]);
                    updateSparepartOptions();
                });
                $(document).on('input', '.jumlah', function() {
                    calculateSubtotal($(this).closest('tr')[0]);
                });
                $(document).on('click', '.remove-row', function() {
                    $(this).closest('tr').remove();
                    updateTotalCost();
                    updateSparepartOptions();
                });
                $('#discount').on('input', function() {
                    updateDiscount();
                });
                $('#purchase_price').on('input', function() {
                    let value = unformat($(this).val());
                    $(this).val(formatRupiah(value));
                    $('#purchase_price_asli').val(value);
                    updateChange();
                });
                let initialValue = unformat($('#purchase_price').val());
                $('#purchase_price').val(formatRupiah(initialValue));
                $('#purchase_price_asli').val(initialValue);
                $('#transactionForm').on('submit', function(e) {
                    if ($('.sparepart_id').length === 0) {
                        alert('Harap tambahkan minimal 1 sparepart.');
                        e.preventDefault();
                        return false;
                    }
                });
            });
        });
    </script>

    <!-- Konfirmasi sebelum submit -->
    <script>
        function confirmSubmit() {
            if (document.querySelectorAll('.sparepart_id').length === 0) {
                alert('Harap tambahkan minimal 1 sparepart.');
                return false;
            }
            return confirm('Apakah Anda yakin ingin menyimpan transaksi ini?');
        }
    </script>
@endsection
