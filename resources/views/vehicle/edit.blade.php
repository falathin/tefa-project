@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <h4 class="text-center mb-4">Edit Service untuk Kendaraan: {{ $vehicle->license_plate }}</h4>            
            <form method="POST" action="{{ route('service.update', $service->id) }}">
                @csrf
                @method('PUT')
            
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                
                <div class="row">
                    <!-- Kolom pertama -->
                    <div class="col-md-6 mb-3">
                        <label for="complaint" class="form-label">Keluhan</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-comment"></i></span>
                            <input type="text" name="complaint" class="form-control" value="{{ old('complaint', $service->complaint) }}" placeholder="Masukkan keluhan kendaraan" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="current_mileage" class="form-label">Kilometer Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            <input type="number" name="current_mileage" class="form-control" value="{{ old('current_mileage', $service->current_mileage) }}" placeholder="Masukkan kilometer kendaraan" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="service_fee" class="form-label">Biaya Service</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" name="service_fee" class="form-control" id="service_fee" value="{{ old('service_fee', $service->service_fee) }}" placeholder="Masukkan biaya service" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="service_date" class="form-label">Tanggal Service</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="service_date" id="service_date" class="form-control" value="{{ old('service_date', $service->service_date->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="total_cost" class="form-label">Total Biaya</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                            <input type="number" name="total_cost" id="total_cost" class="form-control" readonly value="{{ old('total_cost', $service->total_cost) }}" placeholder="Total biaya" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="payment_received" class="form-label">Pembayaran Diterima</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                            <input type="number" name="payment_received" id="payment_received" class="form-control" value="{{ old('payment_received', $service->payment_received) }}" placeholder="Jumlah pembayaran diterima" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="change" class="form-label">Kembalian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                            <input type="number" name="change" id="change" class="form-control" readonly value="{{ old('change', $service->change) }}" placeholder="Kembalian" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="service_type" class="form-label">Jenis Service</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                            <select name="service_type" class="form-control" required>
                                <option value="light" {{ old('service_type', $service->service_type) == 'light' ? 'selected' : '' }}>Ringan</option>
                                <option value="medium" {{ old('service_type', $service->service_type) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="heavy" {{ old('service_type', $service->service_type) == 'heavy' ? 'selected' : '' }}>Berat</option>
                            </select>
                        </div>
                    </div>                                      
                </div>

                <!-- Informasi Sparepart -->
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
                                @foreach($service->serviceSpareparts as $serviceSparepart)
                                    <tr>
                                        <td>
                                            <select name="sparepart_id[]" class="form-control sparepart_id">
                                                <option value="{{ $serviceSparepart->sparepart->id_sparepart }}" selected>{{ $serviceSparepart->sparepart->nama_sparepart }}</option>
                                                @foreach($spareparts as $sparepart)
                                                    <option value="{{ $sparepart->id_sparepart }}" data-harga="{{ $sparepart->harga_jual }}">
                                                        {{ $sparepart->nama_sparepart }}
                                                    </option>                                      
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control harga" readonly value="{{ $serviceSparepart->sparepart->harga_jual }}"></td>
                                        <td><input type="number" name="jumlah[]" class="form-control jumlah" value="{{ $serviceSparepart->quantity }}" min="1"></td>
                                        <td><input type="text" class="form-control subtotal" readonly value="{{ number_format($serviceSparepart->sparepart->harga_jual * $serviceSparepart->quantity, 2) }}"></td>
                                        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <button type="button" class="btn btn-primary" id="addRow">+ Tambah Sparepart</button>
                </div>
                
                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Simpan Service</button>
                </div>
            </form>            
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill today's date for the service date if it's not set
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

        // Add a new row to the spare parts table
        document.getElementById('addRow').addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="sparepart_id[]" class="form-control sparepart_id">
                        @foreach($spareparts as $sparepart)
                            <option value="{{ $sparepart->id_sparepart }}" data-harga="{{ $sparepart->harga_jual }}">{{ $sparepart->nama_sparepart }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control harga" readonly></td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" value="1"></td>
                <td><input type="text" class="form-control subtotal" readonly></td>
                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
            `;
            document.querySelector('#sparepartTable tbody').appendChild(newRow);
            // Add event listener to new row
            newRow.querySelector('.sparepart_id').addEventListener('change', function() {
                const price = parseFloat(this.selectedOptions[0].dataset.harga);
                const row = this.closest('tr');
                row.querySelector('.harga').value = price.toFixed(2);
                calculateSubtotal(row);
            });

            newRow.querySelector('.jumlah').addEventListener('input', function() {
                calculateSubtotal(this.closest('tr'));
            });

            newRow.querySelector('.remove-row').addEventListener('click', function() {
                this.closest('tr').remove();
            });
        });

        // Event listeners for existing rows
        document.querySelectorAll('.sparepart_id').forEach(function(select) {
            select.addEventListener('change', function() {
                const price = parseFloat(this.selectedOptions[0].dataset.harga);
                const row = this.closest('tr');
                row.querySelector('.harga').value = price.toFixed(2);
                calculateSubtotal(row);
            });
        });

        document.querySelectorAll('.jumlah').forEach(function(input) {
            input.addEventListener('input', function() {
                calculateSubtotal(this.closest('tr'));
            });
        });

        // Remove row
        document.querySelectorAll('.remove-row').forEach(function(button) {
            button.addEventListener('click', function() {
                this.closest('tr').remove();
            });
        });
    });
</script>

@endsection