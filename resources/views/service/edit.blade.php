@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <h4 class="text-center mb-4">Edit Service untuk Kendaraan: {{ $service->license_plate }}</h4>            
            <form method="POST" action="{{ route('service.update', $service->id) }}">
                            @csrf
                            @method('PUT')
                        
                            <input type="hidden" name="vehicle_id" value="{{ $service->vehicle_id }}">
                            <div class="row">
                                <!-- Kolom pertama -->
                                <div class="col-md-6 mb-3">
                                    <label for="complaint" class="form-label">Keluhan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                        <input type="text" name="complaint" class="form-control" placeholder="Masukkan keluhan kendaraan" value="{{ old('complaint', $service->complaint) }}" required>
                                    </div>
                                    @error('complaint')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="current_mileage" class="form-label">Kilometer Saat Ini</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                        <input type="number" name="current_mileage" class="form-control" placeholder="Masukkan kilometer kendaraan" value="{{ old('current_mileage', $service->current_mileage) }}" required>
                                    </div>
                                    @error('current_mileage')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="service_fee" class="form-label">Biaya Jasa Pelayanan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        <input type="number" name="service_fee" class="form-control" id="service_fee" placeholder="Masukkan Biaya Pelayanan" value="{{ old('service_fee', $service->service_fee) }}" required>
                                    </div>
                                    @error('service_fee')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="service_date" class="form-label">Tanggal Service</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" name="service_date" id="service_date" class="form-control" value="{{ old('service_date', $service->service_date) }}" required>
                                    </div>
                                    @error('service_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="total_cost" class="form-label">Total Biaya</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                        <input type="number" name="total_cost" id="total_cost" class="form-control" readonly placeholder="Total biaya" value="{{ old('total_cost', $service->total_cost) }}" required>
                                    </div>
                                    @error('total_cost')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="payment_received" class="form-label">Pembayaran Diterima</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="number" name="payment_received" id="payment_received" class="form-control" placeholder="Jumlah pembayaran diterima" value="{{ old('payment_received', $service->payment_received) }}" required>
                                    </div>
                                    @error('payment_received')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="change" class="form-label">Kembalian</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="number" name="change" id="change" class="form-control" readonly placeholder="Kembalian" value="{{ old('change', $service->change) }}" required>
                                    </div>
                                    @error('change')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
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
                                    @error('service_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
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
                                                @if (old('sparepart_id') && old('jumlah'))
                                                    @foreach (old('sparepart_id') as $index => $sparepart_id)
                                                        <tr>
                                                            <td>
                                                                <select name="sparepart_id[]" class="form-control">
                                                                    @foreach ($spareparts as $sparepart)
                                                                        <option value="{{ $sparepart->id_sparepart }}"
                                                                            {{ $sparepart->id_sparepart == $sparepart_id ? 'selected' : '' }}>
                                                                            {{ $sparepart->nama_sparepart }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('sparepart_id.' . $index)
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control harga" 
                                                                    value="{{ $spareparts->where('id_sparepart', $sparepart_id)->first()->harga_jual ?? 0 }}" 
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="jumlah[]" class="form-control jumlah" 
                                                                    value="{{ old('jumlah')[$index] }}">
                                                                @error('jumlah.' . $index)
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control subtotal" value="0" readonly>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash-alt"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @elseif (isset($service->serviceSpareparts))
                                                    @foreach ($service->serviceSpareparts as $serviceSparepart)
                                                        <tr data-sparepart-id="{{ $serviceSparepart->sparepart_id }}">
                                                            <td>
                                                                <select name="sparepart_id[]" class="form-control">
                                                                    @foreach ($spareparts as $sparepart)
                                                                        <option value="{{ $sparepart->id_sparepart }}"
                                                                            {{ $sparepart->id_sparepart == $serviceSparepart->sparepart_id ? 'selected' : '' }}>
                                                                            {{ $sparepart->nama_sparepart }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('sparepart_id.' . $loop->index)
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control harga" 
                                                                    value="{{ $serviceSparepart->sparepart->harga_jual }}" 
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="jumlah[]" class="form-control jumlah" 
                                                                    value="{{ $serviceSparepart->quantity }}">
                                                                @error('jumlah.' . $loop->index)
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control subtotal" 
                                                                    value="{{ $serviceSparepart->sparepart->harga_jual * $serviceSparepart->quantity }}" 
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash-alt"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <button type="button" class="btn btn-primary" id="addRow">+ Tambah Sparepart</button>
                                </div>




                                <div class="text-center mt-4">
                                    @if($service->vehicle)
                                    <a href="{{ route('vehicle.show', $service->vehicle->id) }}" class="btn btn-dark btn-md me-3 mb-2 mb-md-0">
                                        <i class="mdi mdi-car me-2"></i> Kembali
                                    </a>
                                    @endif
                                    
                                    <button type="reset" class="btn btn-warning btn-md me-3 mb-2 mb-md-0">
                                        <i class="fas fa-redo"></i> Reset Form
                                    </button>
                                    
                                    <button type="button" class="btn btn-success btn-md mb-2 mb-md-0" id="submitButton">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                                
                                <!-- Scripts -->
                                <script>
                                    document.getElementById('submitButton').addEventListener('click', function () {
                                        // Show a simple JavaScript confirmation alert
                                        const confirmAction = confirm("Apakah Anda yakin ingin menyimpan perubahan? Pastikan semua data telah diperiksa.");
                                        
                                        if (confirmAction) {
                                            // If confirmed, submit the form
                                            document.querySelector('form').submit();
                                        }
                                    });
                                </script>                                
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Function to calculate subtotal when spare part or quantity changes
                    function calculateSubtotal(row) {
                        const price = parseFloat(row.querySelector('.harga').value) || 0;
                        const quantity = parseFloat(row.querySelector('.jumlah').value) || 0;
                        const subtotal = price * quantity;
                        row.querySelector('.subtotal').value = subtotal.toFixed(2);
                        updateTotalBiaya();
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
                        updateKembalian(); // Recalculate kembalian whenever total biaya is updated
                    }

                    // Update kembalian
                    function updateKembalian() {
                        const uangMasuk = parseFloat(document.getElementById('payment_received').value) || 0;
                        const totalBiaya = parseFloat(document.getElementById('total_cost').value) || 0;
                        const kembalian = uangMasuk - totalBiaya;
                        document.getElementById('change').value = kembalian.toFixed(2);
                    }

                    // Handle quantity change
                    document.querySelector('#sparepartTable').addEventListener('input', function (event) {
                        if (event.target.classList.contains('jumlah') || event.target.classList.contains('harga')) {
                            calculateSubtotal(event.target.closest('tr'));
                            updateTotalBiaya();
                        }
                    });

                    // Handle payment_received change
                    document.getElementById('payment_received').addEventListener('input', function () {
                        updateKembalian();
                    });

                    // Handle service_fee change
                    document.getElementById('service_fee').addEventListener('input', updateTotalBiaya);

                    // Handle addRow
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
                        const newRow = tableBody.lastElementChild;
                        newRow.querySelector('.sparepart_id').addEventListener('change', function () {
                            const price = this.options[this.selectedIndex].getAttribute('data-harga') || 0;
                            newRow.querySelector('.harga').value = parseFloat(price).toFixed(2);
                            calculateSubtotal(newRow);
                        });
                        newRow.querySelector('.jumlah').addEventListener('input', function () {
                            calculateSubtotal(newRow);
                        });
                        newRow.querySelector('.remove-row').addEventListener('click', function () {
                            newRow.remove();
                            updateTotalBiaya();
                        });
                    });

                    // Handle row removal
                    document.querySelectorAll('.removeRow').forEach(function (button) {
                        button.addEventListener('click', function () {
                            const row = button.closest('tr');
                            row.remove();
                            updateTotalBiaya();
                        });
                    });

                    // Initialize total biaya
                    updateTotalBiaya();
                });
            </script>
        </div>
    </div>
</div>
    
@endsection