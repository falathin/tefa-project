@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Sparepart</h1>
    <br>

    <form action="{{ route('sparepart.store') }}" method="POST">
        @csrf

        <!-- Section 1: Informasi Sparepart -->
        <div class="card mb-3 shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Sparepart</h5>
            </div>
            <div class="card-body">
                
                <!-- Nama Sparepart -->
                <div class="form-group">
                    <label for="nama_sparepart"><i class="bi bi-wrench"></i>&nbsp; Nama Sparepart:</label>
                    <input type="text" name="nama_sparepart" id="nama_sparepart" class="form-control @error('nama_sparepart') is-invalid @enderror" value="{{ old('nama_sparepart') }}">
                    @error('nama_sparepart')
                        <div class="alert alert-danger mt-2">
                            <strong>Error:</strong> Nama sparepart harus diisi dan tidak boleh melebihi 255 karakter.
                        </div>
                    @enderror
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label for="jumlah"><i class="bi bi-stack"></i>&nbsp; Jumlah:</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" min="1">
                    @error('jumlah')
                        <div class="alert alert-danger mt-2">
                            <strong>Error:</strong> Jumlah harus berupa angka dan minimal bernilai 1.
                        </div>
                    @enderror
                </div>

                <!-- Harga Beli -->
                <div class="form-group">
                    <label for="harga_beli"><i class="bi bi-cash-stack"></i>&nbsp; Harga Beli:</label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ old('harga_beli') }}" step="0.01">
                    @error('harga_beli')
                        <div class="alert alert-danger mt-2">
                            <strong>Error:</strong> Harga beli harus berupa angka dan tidak boleh kosong.
                        </div>
                    @enderror
                </div>

                <!-- Harga Jual -->
                <div class="form-group">
                    <label for="harga_jual"><i class="bi bi-tag"></i>&nbsp; Harga Jual:</label>
                    <input type="number" name="harga_jual" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual') }}" step="0.01">
                    @error('harga_jual')
                        <div class="alert alert-danger mt-2">
                            <strong>Error:</strong> Harga jual harus berupa angka dan tidak boleh kosong.
                        </div>
                    @enderror
                </div>

                <!-- Keuntungan per Barang -->
                <div class="form-group">
                    <label for="keuntungan"><i class="bi bi-calculator"></i>&nbsp; Keuntungan (Per Barang):</label>
                    <input type="text" id="keuntungan" class="form-control" readonly>
                </div>

                <!-- Total Keuntungan -->
                <div class="form-group">
                    <label for="total_keuntungan"><i class="bi bi-wallet2"></i>&nbsp; Total Keuntungan:</label>
                    <input type="text" id="total_keuntungan" class="form-control" readonly>
                </div>

                <!-- Tanggal Masuk -->
                <div class="form-group">
                    <label for="tanggal_masuk"><i class="bi bi-calendar-plus"></i>&nbsp; Tanggal Masuk:</label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}">
                    @error('tanggal_masuk')
                        <div class="alert alert-danger mt-2">
                            <strong>Error:</strong> Tanggal masuk harus diisi dengan format tanggal yang valid.
                        </div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label for="deskripsi"><i class="bi bi-info-circle"></i>&nbsp; Deskripsi:</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success mt-3"><i class="bi bi-save"></i>&nbsp; Simpan Sparepart</button>
            </div>
        </div>

    </form>
</div>

<script>
    // Function to update the 'Keuntungan' and 'Total Keuntungan' fields
    function updateKeuntungan() {
        const hargaBeli = parseFloat(document.getElementById('harga_beli').value) || 0;
        const hargaJual = parseFloat(document.getElementById('harga_jual').value) || 0;
        const jumlah = parseInt(document.getElementById('jumlah').value) || 0;

        // Hitung keuntungan per barang
        const keuntungan = hargaJual - hargaBeli;
        document.getElementById('keuntungan').value = keuntungan.toLocaleString('id-ID');

        // Hitung total keuntungan
        const totalKeuntungan = keuntungan * jumlah;
        document.getElementById('total_keuntungan').value = totalKeuntungan.toLocaleString('id-ID'); // Format sebagai IDR
    }

    // Event listeners for harga_beli, harga_jual, dan jumlah inputs
    document.getElementById('harga_beli').addEventListener('input', updateKeuntungan);
    document.getElementById('harga_jual').addEventListener('input', updateKeuntungan);
    document.getElementById('jumlah').addEventListener('input', updateKeuntungan);
</script>
@endsection