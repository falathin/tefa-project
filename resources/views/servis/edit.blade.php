@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Servis</h2>
    <form action="{{ route('servis.update', $servis->id_servis) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Data Pelanggan -->
        <div class="form-group">
            <label for="nama_pelanggan">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="{{ $servis->kendaraan->pelanggan->nama_pelanggan }}" required>
        </div>

        <div class="form-group">
            <label for="kontak">Kontak</label>
            <input type="text" name="kontak" class="form-control" value="{{ $servis->kendaraan->pelanggan->kontak }}" required>
        </div>

        <!-- Data Kendaraan -->
        <div class="form-group">
            <label for="nomor_polisi">Nomor Polisi</label>
            <input type="text" name="nomor_polisi" class="form-control" value="{{ $servis->kendaraan->no_polisi }}" required>
        </div>

        <div class="form-group">
            <label for="jenis_kendaraan">Jenis Kendaraan</label>
            <input type="text" name="jenis_kendaraan" class="form-control" value="{{ $servis->kendaraan->jenis_kendaraan }}" required>
        </div>

        <!-- Form Sparepart -->
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
                            @foreach($servis->spareparts as $index => $sparepart)
                            <tr>
                                <td>
                                    <select name="sparepart_id[]" class="form-control sparepart_id" required>
                                        <option value="">Pilih Sparepart</option>
                                        @foreach($spareparts as $spare)
                                            <option value="{{ $spare->id_sparepart }}" 
                                                @if($spare->id_sparepart == $sparepart->id_sparepart) selected @endif
                                                data-harga="{{ $spare->harga_jual }}">
                                                {{ $spare->nama_sparepart }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control harga" readonly value="{{ $sparepart->harga_jual }}">
                                </td>
                                <td>
                                    <input type="number" name="jumlah[]" class="form-control jumlah" value="{{ $sparepart->pivot->jumlah }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control subtotal" readonly value="{{ $sparepart->harga_jual * $sparepart->pivot->jumlah }}">
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

        <!-- Form input lain -->
        <div class="form-group">
            <label for="keluhan">Keluhan</label>
            <textarea name="keluhan" class="form-control" required>{{ $servis->keluhan }}</textarea>
        </div>

        <div class="form-group">
            <label for="kilometer_saat_ini">Kilometer Saat Ini</label>
            <input type="number" name="kilometer_saat_ini" class="form-control" value="{{ $servis->kilometer_saat_ini }}" required>
        </div>

        <div class="form-group">
            <label for="harga_jasa">Harga Jasa</label>
            <input type="number" name="harga_jasa" class="form-control" value="{{ $servis->harga_jasa }}" required>
        </div>

        <div class="form-group">
            <label for="total_biaya">Total Biaya</label>
            <input type="number" name="total_biaya" class="form-control" value="{{ $servis->total_biaya }}" required>
        </div>

        <div class="form-group">
            <label for="uang_masuk">Uang Masuk</label>
            <input type="number" name="uang_masuk" class="form-control" value="{{ $servis->uang_masuk }}" required>
        </div>

        <div class="form-group">
            <label for="jenis_servis">Jenis Servis</label>
            <select name="jenis_servis" class="form-control" required>
                <option value="ringan" @if($servis->jenis_servis == 'ringan') selected @endif>Ringan</option>
                <option value="sedang" @if($servis->jenis_servis == 'sedang') selected @endif>Sedang</option>
                <option value="berat" @if($servis->jenis_servis == 'berat') selected @endif>Berat</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
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
