@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Servis</h1>
        <br>

        <form action="{{ route('servis.store') }}" method="POST">
            @csrf
        
            <!-- Section 1: Input Pelanggan -->
            <div class="card mb-3 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> &nbsp; Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama_pelanggan"><i class="fas fa-user"></i> Nama Pelanggan:</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}">
                        @error('nama_pelanggan')
                            <div class="alert alert-danger mt-2">
                                <small>Nama Pelanggan Wajib Di-isi ! :</small> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="kontak"><i class="fas fa-phone"></i> Kontak:</label>
                        <input type="text" name="kontak" id="kontak" class="form-control" value="{{ old('kontak') }}">
                        @error('kontak')
                            <div class="alert alert-danger mt-2">
                                <small>Kontak Wajib Di-isi ! :</small> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat:</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="alert alert-danger mt-2">
                                <small>Alamat Wajib Di-isi ! :</small> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        
            <!-- Section 2: Input Kendaraan -->
            <div class="card mb-3 shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-car"></i> &nbsp; Informasi Kendaraan</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nomor_polisi"><i class="fas fa-car"></i> Nomor Polisi:</label>
                        <input type="text" name="nomor_polisi" id="nomor_polisi" class="form-control" value="{{ old('nomor_polisi') }}">
                        @error('nomor_polisi')
                            <div class="alert alert-danger mt-2">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>                    
                    <div class="form-group">
                        <label for="jenis_kendaraan"><i class="fas fa-car-side"></i> Jenis Kendaraan:</label>
                        <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" class="form-control" value="{{ old('jenis_kendaraan') }}">
                        @error('jenis_kendaraan')
                            <div class="alert alert-danger mt-2">
                                <small>Jenis Kendaraan Wajib Di-isi ! :</small> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="warna"><i class="fas fa-paint-brush"></i> Warna Kendaraan:</label>
                        <input type="text" name="warna" id="warna" class="form-control" value="{{ old('warna') }}">
                        @error('warna')
                            <div class="alert alert-danger mt-2">
                                <small>Warna Kendaraan Wajib Di-isi ! :</small> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="kode_mesin"><i class="fas fa-cogs"></i> Kode Mesin:</label>
                        <input type="text" name="kode_mesin" id="kode_mesin" class="form-control" value="{{ old('kode_mesin') }}">
                        @error('kode_mesin')
                            <div class="alert alert-danger mt-2">
                                <small>Kode Mesin Wajib Di-isi ! :</small> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tahun_produksi"><i class="fas fa-calendar-alt"></i> Tahun Produksi:</label>
                        <input type="text" name="tahun_produksi" id="tahun_produksi" class="form-control" value="{{ old('tahun_produksi') }}">
                        @error('tahun_produksi')
                            <div class="alert alert-danger mt-2">
                                <small>Tahun Produksi Wajib Di-isi ! :</small> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        
            <!-- Section 3: Input Sparepart -->
            <div class="card mb-3 shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-wrench"></i> &nbsp; Informasi Sparepart</h5>
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
                                @foreach(old('sparepart_id', []) as $index => $oldSparepartId)
                                <tr>
                                    <td>
                                        <select name="sparepart_id[]" class="form-control sparepart_id" required>
                                            <option value="">Pilih Sparepart</option>
                                            @foreach($spareparts as $sparepart)
                                                <option value="{{ $sparepart->id_sparepart }}" 
                                                    @if($sparepart->id_sparepart == $oldSparepartId) selected @endif
                                                    data-harga="{{ $sparepart->harga_jual }}">
                                                    {{ $sparepart->nama_sparepart }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sparepart_id.*')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control harga" readonly value="{{ old('harga')[$index] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah[]" class="form-control jumlah" value="{{ old('jumlah')[$index] ?? '' }}">
                                        @error('jumlah.*')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal" readonly value="{{ old('subtotal')[$index] ?? '' }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger remove-row hover-effect">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>                
                        </table><br><br>
                    </div>
                    <button type="button" class="btn btn-primary hover-effect" id="addRow">+ Tambah Sparepart</button>
                </div>
            </div>
        
            <!-- Section 4: Input Servis -->
            <div class="card mb-3 shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> &nbsp; Informasi Servis</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Jenis Servis -->
                            <div class="form-group mb-3">
                                <label for="jenis_servis" class="col-form-label">
                                    <i class="fas fa-cogs"></i> Jenis Servis
                                    <small id="jenisServisHelp" class="form-text text-muted">
                                        Pilih jenis servis yang sesuai dengan kebutuhan kendaraan.
                                    </small>
                                </label>
                                <select name="jenis_servis" id="jenis_servis" class="form-control form-select" aria-describedby="jenisServisHelp">
                                    <option value="ringan" {{ old('jenis_servis') == 'ringan' ? 'selected' : '' }}>Ringan</option>
                                    <option value="sedang" {{ old('jenis_servis') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="berat" {{ old('jenis_servis') == 'berat' ? 'selected' : '' }}>Berat</option>
                                </select>
                                @error('jenis_servis')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Keluhan -->
                            <div class="form-group mb-3">
                                <label for="keluhan" class="col-form-label">
                                    <i class="fas fa-exclamation-circle"></i> Keluhan:
                                </label>
                                <textarea name="keluhan" id="keluhan" class="form-control" rows="4" placeholder="Jelaskan keluhan kendaraan di sini...">{{ old('keluhan') }}</textarea>
                                @error('keluhan')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Harga Jasa -->
                            <div class="form-group mb-3">
                                <label for="harga_jasa" class="col-form-label">
                                    <i class="fas fa-money-bill-wave"></i> Harga Jasa:
                                </label>
                                <input type="number" name="harga_jasa" id="harga_jasa" class="form-control" value="{{ old('harga_jasa') }}">
                                @error('harga_jasa')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Uang Masuk -->
                            <div class="form-group mb-3">
                                <label for="uang_masuk" class="col-form-label">
                                    <i class="fas fa-money-bill-wave"></i> Uang Masuk:
                                </label>
                                <input type="number" name="uang_masuk" id="uang_masuk" class="form-control" step="0.01" value="{{ old('uang_masuk') }}">
                                @error('uang_masuk')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Kilometer Saat Ini -->
                            <div class="form-group mb-3">
                                <label for="kilometer_saat_ini" class="col-form-label">
                                    <i class="fas fa-tachometer-alt"></i> Kilometer Saat Ini:
                                </label>
                                <input type="number" name="kilometer_saat_ini" id="kilometer_saat_ini" class="form-control" value="{{ old('kilometer_saat_ini') }}">
                                @error('kilometer_saat_ini')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Total Biaya -->
                            <div class="form-group mb-3">
                                <label for="total_biaya" class="col-form-label">
                                    <i class="fas fa-dollar-sign"></i> Total Biaya:
                                </label>
                                <input type="text" name="total_biaya" id="total_biaya" class="form-control" readonly value="{{ old('total_biaya') }}">
                                @error('total_biaya')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kembalian -->
                            <div class="form-group mb-3">
                                <label for="kembalian" class="col-form-label">
                                    <i class="fas fa-money-bill-wave"></i> Kembalian:
                                </label>
                                <input type="number" name="kembalian" id="kembalian" class="form-control" step="0.01" value="{{ old('kembalian') }}" readonly>
                                @error('kembalian')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Servis -->
                            <div class="form-group mb-3">
                                <label for="tanggal_servis" class="col-form-label">
                                    <i class="fas fa-calendar-day"></i> Tanggal Servis:
                                </label>
                                <input type="date" name="tanggal_servis" id="tanggal_servis" class="form-control" value="{{ old('tanggal_servis', date('Y-m-d')) }}">
                                @error('tanggal_servis')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Submit and Reset Buttons with Icons and Animations -->
            <div class="text-center">
                <button type="reset" class="btn btn-secondary hover-effect">
                    <i class="fas fa-undo"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-success hover-effect">
                    <i class="fas fa-check-circle"></i> Simpan Servis
                </button>
            </div>

        </form>
    </div>

    {{-- Modal Alert 1 --}}

    <!-- Validation Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: rgba(255, 255, 255, 0.9);">
                <div class="modal-header bg-white">
                    <h5 class="modal-title d-flex align-items-center text-success" id="validationModalLabel">
                        <i class="fas fa-exclamation-circle me-2 text-success"></i> Peringatan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" style="color: #333;">
                    <div class="d-flex align-items-center">
                        <p id="validationMessage">Pesan peringatan akan ditampilkan di sini.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success hover-effect" data-bs-dismiss="modal">
                        <i class="fas fa-check-circle me-2"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update total biaya based on harga jasa and spare part subtotal
        function updateTotalBiaya() {
            const hargaJasa = parseFloat(document.getElementById('harga_jasa').value) || 0;
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
            document.getElementById('total_biaya').value = totalBiaya.toFixed(2);
        }

                // Update kembalian
                function updateKembalian() {
                    const uangMasuk = parseFloat(document.getElementById('uang_masuk').value) || 0;
                    const totalBiaya = parseFloat(document.getElementById('total_biaya').value) || 0;
                    const kembalian = uangMasuk - totalBiaya;
                    document.getElementById('kembalian').value = kembalian.toFixed(2);
                }

                // Event listeners
                document.getElementById('harga_jasa').addEventListener('input', updateTotalBiaya);
                document.getElementById('uang_masuk').addEventListener('input', updateKembalian);

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

                document.querySelector('#sparepartTable').addEventListener('click', function (event) {
                    if (event.target.classList.contains('remove-row')) {
                        event.target.closest('tr').remove();
                        updateTotalBiaya();
                    }
                });

                document.querySelector('#sparepartTable').addEventListener('change', function (event) {
                    if (event.target.classList.contains('sparepart_id')) {
                        const harga = parseFloat(event.target.selectedOptions[0].dataset.harga) || 0;
                        event.target.closest('tr').querySelector('.harga').value = harga.toFixed(2);
                        updateTotalBiaya();
                    }
                });

                document.querySelector('#sparepartTable').addEventListener('input', function (event) {
                    if (event.target.classList.contains('jumlah') || event.target.classList.contains('harga')) {
                        updateTotalBiaya();
                    }
                });

                // Initial calculation
                updateTotalBiaya();

                document.addEventListener('DOMContentLoaded', function () {
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

            // Initialize validation and subtotal calculation for each row
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

            // Remove row functionality
            document.querySelectorAll('.remove-row').forEach(function (button) {
                button.addEventListener('click', function () {
                    this.closest('tr').remove();
                });
            });
        });

    </script>    
@endsection