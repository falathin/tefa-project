@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body">
                <h4 class="text-center mb-4">Tambah Service untuk Kendaraan: {{ $vehicle->license_plate }}</h4>
                <form method="POST" action='{{ route('service.store-2') }}'>
                    @csrf

                    <input type="hidden" name="jurusan" id="jurusan" value="{{ Auth::user()->jurusan }}">
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                    <div class="row">
                        <!-- Kategori Informasi Servis -->
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold">Informasi Servis</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="complaint" class="form-label">Keluhan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                <input type="text" name="complaint" class="form-control"
                                    placeholder="Masukkan keluhan kendaraan" value="{{ old('complaint') }}" required>
                            </div>
                            @error('complaint')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="current_mileage" class="form-label">Kilometer Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                <input type="number" name="current_mileage" class="form-control"
                                    placeholder="Masukkan kilometer kendaraan" value="{{ old('current_mileage') }}"
                                    required>
                            </div>
                            @error('current_mileage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_date" class="form-label">Tanggal Service</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="service_date" id="service_date" class="form-control"
                                    value="{{ old('service_date') }}" required>
                            </div>
                            @error('service_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_type" class="form-label">Jenis Service</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                <select name="service_type" class="form-control" required>
                                    <option value="light" {{ old('service_type') == 'light' ? 'selected' : '' }}>Ringan
                                    </option>
                                    <option value="medium" {{ old('service_type') == 'medium' ? 'selected' : '' }}>Sedang
                                    </option>
                                    <option value="heavy" {{ old('service_type') == 'heavy' ? 'selected' : '' }}>Berat
                                    </option>
                                </select>
                            </div>
                            @error('service_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="technician_name" class="form-label">Nama Teknisi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                <input type="text" name="technician_name" id="technician_name" class="form-control"
                                    placeholder="Masukkan nama teknisi" value="{{ old('technician_name') }}" required>
                            </div>
                            @error('technician_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informasi Sparepart -->
                        <div
                            class="card-header mt-3 rounded bg-danger card-header text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-wrench"></i> &nbsp; Tambah Informasi Sparepart</h5>
                            <small class="text-right"><b>*</b> Hapus jika tidak diperlukan</small>
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
                                        <!-- Sparepart Rows -->
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <button type="button" class="btn btn-primary" id="addRow">+ Tambah Sparepart</button>
                        </div>

                        <!-- Kategori Informasi Pembayaran -->
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold">Informasi Pembayaran</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_fee" class="form-label">Jasa Pelayanan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" name="service_fee" class="form-control" id="service_fee"
                                    placeholder="Masukkan Biaya Jasa" value="{{ old('service_fee') }}" required>
                            </div>
                            @error('service_fee')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_cost" class="form-label">Total Biaya</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                <input type="number" name="total_cost" id="total_cost" class="form-control" readonly
                                    placeholder="Total biaya" value="{{ old('total_cost') }}" required>
                            </div>
                            @error('total_cost')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="payment_received" class="form-label">Pembayaran Diterima</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                <input type="number" name="payment_received" id="payment_received" class="form-control"
                                    placeholder="Jumlah pembayaran diterima" value="{{ old('payment_received') }}"
                                    required>
                            </div>
                            @error('payment_received')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="change" class="form-label">Kembalian</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                <input type="number" name="change" id="change" class="form-control" readonly
                                    placeholder="Kembalian" value="{{ old('change') }}" required>
                            </div>
                            @error('change')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori Catatan Tambahan -->
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold">Informasi Tambahan</h5>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="additional_notes" class="form-label">Catatan Tambahan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                <textarea name="additional_notes" id="additional_notes" class="form-control" placeholder="Masukkan catatan tambahan"
                                    rows="5" style="resize: vertical;">{{ old('additional_notes') }}</textarea>
                            </div>
                            @error('additional_notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Submit and Back Buttons -->
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

                    <script>
                        function confirmSubmit() {
                            return confirm("Apakah Anda yakin ingin menyimpan data service ini?");
                        }
                    </script>

                    <!-- Scripts -->
                    {{-- <script>
                        document.getElementById('submitButton').addEventListener('click', function() {
                            const confirmAction = confirm("Apakah Anda yakin semua data sudah benar?");

                            if (confirmAction) {
                                document.querySelector('form').submit();
                            }
                        });
                    </script> --}}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Automatically set today's date for service date input
            const serviceDateInput = document.getElementById('service_date');
            if (!serviceDateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                serviceDateInput.value = today;
            }

            // Function to calculate subtotal when spare part or quantity changes
            function calculateSubtotal(row) {
                const price = parseFloat(row.querySelector('.harga').value) || 0;
                const quantity = parseFloat(row.querySelector('.jumlah').value) || 0;
                const subtotal = price * quantity;
                row.querySelector('.subtotal').value = subtotal.toFixed(2);
                updateTotalBiaya();
            }

            // Show modal with custom message
            function showModalMessage(message) {
                const validationMessage = document.getElementById('validationMessage');
                validationMessage.textContent = message;
                const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
                validationModal.show();
            }

            // Validate sparepart and jumlah fields
            function validateSparepartFields(row) {
                const sparepartSelect = row.querySelector('.sparepart_id');
                const quantityInput = row.querySelector('.jumlah');

                sparepartSelect.addEventListener('change', function() {
                    if (sparepartSelect.value && !quantityInput.value) {
                        showModalMessage('Silakan masukkan jumlah jika Anda memilih spare part!');
                    }
                });

                quantityInput.addEventListener('input', function() {
                    if (quantityInput.value && !sparepartSelect.value) {
                        showModalMessage('Silakan pilih spare part jika Anda memasukkan jumlah!');
                    }
                });
            }

            // Update total biaya based on harga jasa and spare part subtotal
            function updateTotalBiaya() {
                const hargaJasa = parseFloat(document.getElementById('service_fee').value) || 0;
                let totalSparepart = 0;

                // Calculate total spare part cost
                document.querySelectorAll('#sparepartTable tbody tr').forEach(row => {
                    const harga = parseFloat(row.querySelector('.harga').value.replace(/[^0-9.-]+/g, "")) ||
                        0;
                    const jumlah = parseInt(row.querySelector('.jumlah').value) || 0;
                    const subtotal = harga * jumlah;
                    row.querySelector('.subtotal').value = subtotal.toFixed(2);
                    totalSparepart += subtotal;
                });

                // Update total biaya
                const totalBiaya = hargaJasa + totalSparepart;
                document.getElementById('total_cost').value = totalBiaya.toFixed(2);
            }

            // Update kembalian
            function updateKembalian() {
                const uangMasuk = parseFloat(document.getElementById('payment_received').value) || 0;
                const totalBiaya = parseFloat(document.getElementById('total_cost').value) || 0;
                const kembalian = uangMasuk - totalBiaya;
                document.getElementById('change').value = kembalian.toFixed(2);
            }

            // Add new row
            document.getElementById('addRow').addEventListener('click', function() {
                const tableBody = document.querySelector('#sparepartTable tbody');
                const row = tableBody.insertRow();
                row.innerHTML = `
                <td>
                    <select name="sparepart_id[]" class="form-control sparepart_id">
                        <option value="">Pilih Sparepart</option>
                        @foreach ($spareparts as $sparepart)
                            <option value="{{ $sparepart->id_sparepart }}" data-harga="{{ $sparepart->harga_jual }}">
                                {{ $sparepart->nama_sparepart }}
                            </option>                                      
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control harga" readonly></td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1"></td>
                <td><input type="text" class="form-control subtotal" readonly></td>
                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
            `;
                // Re-add event listeners for the new row
                const newRow = tableBody.lastElementChild;
                validateSparepartFields(newRow);
                newRow.querySelector('.sparepart_id').addEventListener('change', function() {
                    const price = this.options[this.selectedIndex].getAttribute('data-harga') || 0;
                    newRow.querySelector('.harga').value = parseFloat(price).toFixed(2);
                    calculateSubtotal(newRow);
                });
                newRow.querySelector('.jumlah').addEventListener('input', function() {
                    calculateSubtotal(newRow);
                });

                updateTotalBiaya();
            });

            // Handle remove row
            document.querySelector('#sparepartTable').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-row')) {
                    event.target.closest('tr').remove();
                    updateTotalBiaya();
                }
            });

            // Handle sparepart change
            document.querySelector('#sparepartTable').addEventListener('change', function(event) {
                if (event.target.classList.contains('sparepart_id')) {
                    const harga = parseFloat(event.target.selectedOptions[0].dataset.harga) || 0;
                    event.target.closest('tr').querySelector('.harga').value = harga.toFixed(2);
                    calculateSubtotal(event.target.closest('tr'));
                    updateTotalBiaya();
                }
            });

            // Handle quantity change
            document.querySelector('#sparepartTable').addEventListener('input', function(event) {
                if (event.target.classList.contains('jumlah') || event.target.classList.contains('harga')) {
                    calculateSubtotal(event.target.closest('tr'));
                    updateTotalBiaya();
                }
            });

            // Initial calculation and validation
            document.querySelectorAll('#sparepartTable tbody tr').forEach(function(row) {
                validateSparepartFields(row);
                row.querySelector('.sparepart_id').addEventListener('change', function() {
                    const price = this.options[this.selectedIndex].getAttribute('data-harga') || 0;
                    row.querySelector('.harga').value = parseFloat(price).toFixed(2);
                    calculateSubtotal(row);
                });
                row.querySelector('.jumlah').addEventListener('input', function() {
                    calculateSubtotal(row);
                });
            });

            // Initial calculation of total biaya
            updateTotalBiaya();
            document.getElementById('service_fee').addEventListener('input', updateTotalBiaya);
            document.getElementById('payment_received').addEventListener('input', updateKembalian);
        });
    </script>
@endsection