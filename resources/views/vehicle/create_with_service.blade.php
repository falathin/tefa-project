@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card shadow-sm mb-4 animate__animated animate__fadeInUp animate__delay-0.5s">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Tambah Kendaraan untuk {{ $customer->name }}</h5>
                <form action="{{ route('vehicle.store_with_service') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                    <!-- Vehicle Section -->
                    <h6 class="mb-3">Data Kendaraan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vehicle_type">Jenis Kendaraan</label>
                            <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" required>
                            @error('vehicle_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="license_plate">Nomor Plat Kendaraan</label>
                            <input type="text" class="form-control @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required>
                            @error('license_plate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="engine_code">Kode Mesin</label>
                            <input type="text" class="form-control @error('engine_code') is-invalid @enderror" id="engine_code" name="engine_code" value="{{ old('engine_code') }}">
                            @error('engine_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="color">Warna</label>
                            <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}">
                            @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="year">Tahun Kendaraan</label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year') }}">
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image">Foto Kendaraan (Opsional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow-sm mb-4 animate__animated animate__fadeInUp animate__delay-4s">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Informasi Sparepart untuk {{ $customer->name }}</h5>
                <form action="{{ route('vehicle.store_with_service') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                    <!-- Sparepart Section -->
                    <div class="card-header rounded bg-danger text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-wrench"></i> &nbsp; Tambah Informasi Sparepart</h5>
                        <small class="text-right"><b>*</b> Hapus jika tidak diperlukan</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="sparepartTable">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-wrench"></i> Nama Sparepart</th>
                                        <th><i class="fas fa-tag"></i> Harga Satuan</th>
                                        <th><i class="fas fa-plus-circle"></i> Jumlah yang Diambil</th>
                                        <th><i class="fas fa-calculator"></i> Subtotal</th>
                                        <th><i class="fas fa-trash-alt"></i> Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (old('spareparts'))
                                        @foreach (old('spareparts') as $index => $sparepart)
                                            <tr>
                                                <td>
                                                    <input type="text" name="spareparts[{{ $index }}][name]" 
                                                        class="form-control @error("spareparts.$index.name") is-invalid @enderror"
                                                        value="{{ old("spareparts.$index.name") }}" required>
                                                    @error("spareparts.$index.name")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" name="spareparts[{{ $index }}][unit_price]" 
                                                        class="form-control @error("spareparts.$index.unit_price") is-invalid @enderror" 
                                                        value="{{ old("spareparts.$index.unit_price") }}" required>
                                                    @error("spareparts.$index.unit_price")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" name="spareparts[{{ $index }}][quantity]" 
                                                        class="form-control @error("spareparts.$index.quantity") is-invalid @enderror" 
                                                        value="{{ old("spareparts.$index.quantity") }}" required>
                                                    @error("spareparts.$index.quantity")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" name="spareparts[{{ $index }}][subtotal]" 
                                                        class="form-control" value="{{ old("spareparts.$index.subtotal") }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger removeRow">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div><br>
                        <button type="button" class="btn btn-primary hover-effect" id="addRow">+ Tambah Sparepart</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow-sm mb-4 animate__animated animate__fadeInUp animate__delay-7s">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Tambah Servis untuk {{ $customer->name }}</h5>
                <form action="{{ route('vehicle.store_with_service') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                    <!-- Service Section -->
                    <h6 class="mb-3">Data Servis</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="service_date">Tanggal Servis</label>
                            <input type="date" class="form-control @error('service_date') is-invalid @enderror" id="service_date" name="service_date" value="{{ old('service_date') }}" required>
                            @error('service_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_type_select">Jenis Servis</label>
                            <select class="form-control @error('service_type') is-invalid @enderror" id="service_type_select" name="service_type" required>
                                <option value="light" {{ old('service_type') == 'light' ? 'selected' : '' }}>Ringan</option>
                                <option value="medium" {{ old('service_type') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="heavy" {{ old('service_type') == 'heavy' ? 'selected' : '' }}>Berat</option>
                            </select>
                            @error('service_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="complaint">Keluhan</label>
                            <textarea class="form-control @error('complaint') is-invalid @enderror" id="complaint" name="complaint" rows="3" required>{{ old('complaint') }}</textarea>
                            @error('complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="current_mileage">Kilometer Saat Ini</label>
                            <input type="number" class="form-control @error('current_mileage') is-invalid @enderror" id="current_mileage" name="current_mileage" value="{{ old('current_mileage') }}" required>
                            @error('current_mileage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="service_fee">Biaya Servis</label>
                            <input type="number" class="form-control @error('service_fee') is-invalid @enderror" id="service_fee" name="service_fee" value="{{ old('service_fee') }}" step="0.01" required>
                            @error('service_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="total_cost">Total Biaya</label>
                            <input type="number" class="form-control @error('total_cost') is-invalid @enderror" id="total_cost" name="total_cost" value="{{ old('total_cost') }}" step="0.01" required>
                            @error('total_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_received">Pembayaran Diterima</label>
                            <input type="number" class="form-control @error('payment_received') is-invalid @enderror" id="payment_received" name="payment_received" value="{{ old('payment_received') }}" step="0.01" required>
                            @error('payment_received') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="change">Kembalian</label>
                            <input type="number" class="form-control @error('change') is-invalid @enderror" id="change" name="change" value="{{ old('change') }}" step="0.01" required>
                            @error('change') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-success px-4">Simpan</button>
                    </div>
                </form>
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
    
                sparepartSelect.addEventListener('change', function () {
                    if (sparepartSelect.value && !quantityInput.value) {
                        showModalMessage('Silakan masukkan jumlah jika Anda memilih spare part!');
                    }
                });
    
                quantityInput.addEventListener('input', function () {
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
                    const harga = parseFloat(row.querySelector('.harga').value.replace(/[^0-9.-]+/g, "")) || 0;
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
            document.getElementById('addRow').addEventListener('click', function () {
                const tableBody = document.querySelector('#sparepartTable tbody');
                const row = tableBody.insertRow();
                row.innerHTML = `
                    <td>
                        <select name="sparepart_id[]" class="form-control sparepart_id">
                            <option value="">Pilih Sparepart</option>
                            @foreach($spareparts as $sparepart)
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
                updateTotalBiaya();
            });
    
            // Handle remove row
            document.querySelector('#sparepartTable').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-row')) {
                    event.target.closest('tr').remove();
                    updateTotalBiaya();
                }
            });
    
            // Handle sparepart change
            document.querySelector('#sparepartTable').addEventListener('change', function (event) {
                if (event.target.classList.contains('sparepart_id')) {
                    const harga = parseFloat(event.target.selectedOptions[0].dataset.harga) || 0;
                    event.target.closest('tr').querySelector('.harga').value = harga.toFixed(2);
                    calculateSubtotal(event.target.closest('tr'));
                    updateTotalBiaya();
                }
            });
    
            // Handle quantity change
            document.querySelector('#sparepartTable').addEventListener('input', function (event) {
                if (event.target.classList.contains('jumlah') || event.target.classList.contains('harga')) {
                    calculateSubtotal(event.target.closest('tr'));
                    updateTotalBiaya();
                }
            });
    
            // Initial calculation and validation
            document.querySelectorAll('#sparepartTable tbody tr').forEach(function (row) {
                validateSparepartFields(row);
                row.querySelector('.sparepart_id').addEventListener('change', function () {
                    const price = this.options[this.selectedIndex].getAttribute('data-harga') || 0;
                    row.querySelector('.harga').value = parseFloat(price).toFixed(2);
                    calculateSubtotal(row);
                });
                row.querySelector('.jumlah').addEventListener('input', function () {
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