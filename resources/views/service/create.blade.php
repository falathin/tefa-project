@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body">
                <h4 class="text-center mb-4">Tambah Service untuk Kendaraan: {{ $vehicle->license_plate }}</h4>
                <form method="POST" action="{{ route('service.storeServis') }}">
                    @csrf
                    <input type="hidden" name="jurusan" id="jurusan" value="{{ Auth::user()->jurusan }}">
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                    <div class="row">
                        <!-- Kategori Informasi Servis -->
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold text-primary">Informasi Servis</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="complaint" class="form-label text-dark">Keluhan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-white"><i class="fas fa-comment"></i></span>
                                <input type="text" name="complaint" class="form-control border-warning"
                                    placeholder="Masukkan keluhan kendaraan" value="{{ old('complaint') }}" required>
                            </div>
                            @error('complaint')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="current_mileage" class="form-label text-dark">Kilometer Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text bg-info text-white"><i class="fas fa-tachometer-alt"></i></span>
                                <input type="number" name="current_mileage" class="form-control border-info"
                                    placeholder="Masukkan kilometer kendaraan" value="{{ old('current_mileage') }}"
                                    required>
                            </div>
                            @error('current_mileage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_date" class="form-label text-dark">Tanggal Service</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-calendar-alt"></i></span>
                                <!-- Tanggal otomatis hari ini jika tidak ada nilai lama -->
                                <input type="date" name="service_date" id="service_date" class="form-control border-success"
                                    value="{{ old('service_date', \Carbon\Carbon::now()->toDateString()) }}" required>
                            </div>
                            @error('service_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_type" class="form-label text-dark">Jenis Service</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-danger text-white">
                                        <i class="fas fa-tools"></i>
                                    </span>

                                    <select name="service_type" class="form-control border-danger" required>
                                        <option value="">-- Pilih Jenis Service --</option>

                                        @if (Auth::user()->jurusan == 'TSM')
                                            <option value="light" {{ old('service_type') == 'light' ? 'selected' : '' }}>
                                                Ringan
                                            </option>

                                            <option value="medium" {{ old('service_type') == 'medium' ? 'selected' : '' }}>
                                                Sedang
                                            </option>

                                            <option value="heavy" {{ old('service_type') == 'heavy' ? 'selected' : '' }}>
                                                Berat
                                            </option>

                                        @elseif (Auth::user()->jurusan == 'TKRO')
                                            <option value="light" {{ old('service_type') == 'light' ? 'selected' : '' }}>
                                                10.000 KM (Ringan)
                                            </option>

                                            <option value="medium" {{ old('service_type') == 'medium' ? 'selected' : '' }}>
                                                30.000 KM (Sedang)
                                            </option>

                                            <option value="heavy" {{ old('service_type') == 'heavy' ? 'selected' : '' }}>
                                                50.000 KM (Berat)
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            @error('service_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="technician_name" class="form-label text-dark">Nama Teknisi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-user-cog"></i></span>
                                <input type="text" name="technician_name" id="technician_name" class="form-control border-primary"
                                    placeholder="Masukkan nama teknisi" value="{{ old('technician_name') }}" required>
                            </div>
                            @error('technician_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Informasi Sparepart -->
                    <div class="card-header mt-3 rounded bg-danger text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-wrench"></i> &nbsp; Tambah Informasi Sparepart</h5>
                        <small class="text-white"><b>*</b> Hapus jika tidak diperlukan</small>
                    </div>
                    <div class="card-body">
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
                                    <!-- Sparepart Rows akan ditambahkan secara dinamis -->
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <button type="button" class="btn btn-primary" id="addRow">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Sparepart
                        </button>
                    </div>

                    <!-- Kategori Catatan Tambahan -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold">Informasi Tambahan</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="additional_notes" class="form-label">Catatan Tambahan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                <textarea name="additional_notes" id="additional_notes" class="form-control"
                                    placeholder="Tambahkan catatan tambahan jika diperlukan!" rows="5" style="resize: vertical;">{{ old('additional_notes') }}</textarea>
                            </div>
                            @error('additional_notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit dan Navigasi -->
                    <div class="text-center mt-4">
                        <a href="{{ route('vehicle.show', $vehicle->id) }}" class="btn btn-secondary btn-md">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="reset" class="btn btn-warning btn-md">
                            <i class="fas fa-redo"></i> Reset Form
                        </button>
                        <button type="submit" class="btn btn-success btn-md" id="submitButton"
                            onclick="return confirmSubmit()">
                            <i class="fas fa-save"></i> Simpan Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script untuk perhitungan, validasi, dan pengaturan sparepart -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi format angka
            function formatRibuan(angka) {
                return new Intl.NumberFormat("id-ID").format(angka);
            }
            function formatRupiah(angka) {
                return "Rp " + formatRibuan(angka);
            }
            function unformat(angka) {
                return parseInt(angka.replace(/\D/g, "")) || 0;
            }
            // Fungsi format currency dengan Select2 (menggunakan Intl.NumberFormat untuk format IDR)
            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);
            }

            // Fungsi untuk menghitung subtotal pada satu baris sparepart
            function calculateSubtotal(row) {
                const price = parseFloat($(row).find('.sparepart_id option:selected').data('harga')) || 0;
                const quantity = parseInt($(row).find('.jumlah').val()) || 0;
                const subtotal = price * quantity;
                $(row).find('.subtotal').val(formatCurrency(subtotal));
                updateTotalCost();
            }

            // Fungsi untuk mengupdate total biaya sparepart (dari semua baris)
            function updateTotalCost() {
                let total = 0;
                $('.subtotal').each(function() {
                    const value = $(this).val().replace(/[^\d]/g, '');
                    total += parseInt(value) || 0;
                });
                document.getElementById('total_price_asli').value = total;
                updateDiscount();
            }

            // Fungsi untuk mengupdate opsi di select sparepart agar tidak ada duplikasi
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
                // Jika seluruh sparepart sudah dipilih, tampilkan alert dan batalkan penambahan
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
                                {{ $sparepart->nama_sparepart }} {{ $sparepart->spek }}
                            </option>
                        `;
                    }
                @endforeach
                row.innerHTML = `
                    <td>
                        <select name="sparepart_id[]" class="form-control sparepart_id select2" required>
                            ${optionsHtml}
                        </select>
                    </td>
                    <td><input type="text" class="form-control harga" readonly></td>
                    <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" required style="width:100px"></td>
                    <td><input type="text" class="form-control subtotal" readonly style="width:250px"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger remove-row">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                    </td>
                `;
                // Inisialisasi Select2 untuk baris baru
                $(row.querySelector('.select2')).select2({ width: '100%' });
                updateAllSelectOptions();
                // Event: Saat sparepart dipilih di baris baru
                $(row).find('.sparepart_id').on('select2:select', function() {
                    const selectedOption = $(this).find('option:selected');
                    const price = selectedOption.data('harga') || 0;
                    $(row).find('.harga').val(formatCurrency(price));
                    calculateSubtotal(row);
                });
                // Event: Saat jumlah diubah di baris baru
                $(row).find('.jumlah').on('input', function() {
                    calculateSubtotal(row);
                });
                updateTotalCost();
            });

            // Event: Saat terjadi perubahan pada select sparepart atau input jumlah
            document.querySelector('#sparepartTable').addEventListener('change', function(event) {
                if (event.target.classList.contains('sparepart_id')) {
                    const row = event.target.closest('tr');
                    const price = event.target.selectedOptions[0].getAttribute('data-harga') || 0;
                    row.querySelector('.harga').value = formatCurrency(parseFloat(price));
                    calculateSubtotal(row);
                    updateAllSelectOptions();
                }
                if (event.target.classList.contains('jumlah')) {
                    const row = event.target.closest('tr');
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

            // Inisialisasi Select2 untuk baris yang sudah ada (jika ada)
            $(document).ready(function() {
                $('.select2').select2({ width: '100%' });
            });

            // Inisialisasi nilai awal untuk input uang masuk
            document.getElementById('purchase_price').addEventListener('input', function() {
                let value = unformat(this.value);
                this.value = formatRupiah(value);
                document.getElementById('purchase_price_asli').value = value;
            });
            let initialValue = unformat(document.getElementById('purchase_price').value);
            document.getElementById('purchase_price').value = formatRupiah(initialValue);
            document.getElementById('purchase_price_asli').value = initialValue;

            // Panggil update opsi pada saat halaman load
            updateAllSelectOptions();
            updateTotalCost();
        });

        // Fungsi konfirmasi submit form sederhana (tidak validasi minimal sparepart)
        function confirmSubmit() {
            return confirm('Apakah Anda yakin ingin menyimpan data service ini?');
        }
    </script>
@endsection