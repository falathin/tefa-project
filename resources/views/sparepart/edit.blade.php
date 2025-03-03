@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Sparepart</h1>
        <br>

        <form action="{{ route('sparepart.update', $sparepart->id_sparepart) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-3 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Sparepart</h5>
                </div>
                <div class="card-body">

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><label for="nama_sparepart"><i class="bi bi-wrench"></i>&nbsp; Nama Sparepart:</label>
                                </td>
                                <td>
                                    <input type="text" name="nama_sparepart" id="nama_sparepart"
                                        class="form-control @error('nama_sparepart') is-invalid @enderror"
                                        value="{{ old('nama_sparepart', $sparepart->nama_sparepart) }}">
                                    @error('nama_sparepart')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Nama sparepart harus diisi dan tidak boleh melebihi 255
                                            karakter.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="spek"><i class="bi bi-stack"></i>&nbsp; Spesifikasi:</label></td>
                                <td>
                                    <input type="text" name="spek" id="spek"
                                        class="form-control @error('spek') is-invalid @enderror"
                                        value="{{ old('spek', $sparepart->spek) }}" min="1">
                                    @error('spek')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Spesifikasi harus berupa angka dan minimal bernilai 1.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="jumlah"><i class="bi bi-stack"></i>&nbsp; Jumlah:</label></td>
                                <td>
                                    <input type="text" name="jumlah" id="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror"
                                        value="{{ old('jumlah', $sparepart->jumlah) }}" min="1">
                                    <input type="hidden" name="jumlah" id="jumlah_asli">
                                    @error('jumlah')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Jumlah harus berupa angka dan minimal bernilai 1.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="harga_beli"><i class="bi bi-cash-stack"></i>&nbsp; Harga Beli :</label></td>
                                <td>
                                    <input type="text" id="harga_beli"
                                        class="form-control @error('harga_beli') is-invalid @enderror"
                                        value="{{ old('harga_beli', $sparepart->harga_beli) }}" step="0.01">
                                    <input type="hidden" name="harga_beli" id="harga_beli_asli">
                                    @error('harga_beli')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Harga beli harus berupa angka dan tidak boleh kosong.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="harga_jual"><i class="bi bi-tag"></i>&nbsp; Harga Jual:</label></td>
                                <td>
                                    <input type="text" id="harga_jual"
                                        class="form-control @error('harga_jual') is-invalid @enderror"
                                        value="{{ old('harga_jual', $sparepart->harga_jual) }}">
                                    <input type="hidden" name="harga_jual" id="harga_jual_asli">
                                    @error('harga_jual')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Harga jual harus berupa angka dan tidak boleh kosong.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="keuntungan"><i class="bi bi-calculator"></i>&nbsp; Keuntungan (Per
                                        Barang):</label></td>
                                <td>
                                    <input type="text" id="keuntungan" class="form-control" readonly>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="total_keuntungan"><i class="bi bi-wallet2"></i>&nbsp; Total
                                        Keuntungan:</label></td>
                                <td>
                                    <input type="text" id="total_keuntungan" class="form-control" readonly>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="tanggal_masuk"><i class="bi bi-calendar-plus"></i>&nbsp; Tanggal
                                        Masuk:</label></td>
                                <td>
                                    <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                        class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                        value="{{ old('tanggal_masuk', $sparepart->tanggal_masuk) }}">
                                    @error('tanggal_masuk')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Tanggal masuk harus diisi dengan format tanggal yang valid.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="deskripsi"><i class="bi bi-info-circle"></i>&nbsp; Deskripsi:</label></td>
                                <td>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $sparepart->deskripsi) }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-success mt-3"><i class="bi bi-save"></i>&nbsp; Perbarui
                        Sparepart</button>
                    <a href="{{ route('sparepart.index') }}" class="btn btn-secondary mt-3 ml-2"><i
                            class="bi bi-arrow-left"></i>&nbsp; Kembali</a>
                </div>
            </div>

        </form>
    </div>

    <script>
        // Menjalankan fungsi saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', function() {
            let jumlah = document.getElementById('jumlah');
            let harga_beli = document.getElementById('harga_beli');
            let harga_jual = document.getElementById('harga_jual');

            jumlah.value = formatRibuan(jumlah.value.replace(/\D/g, ""));
            harga_beli.value = formatRibuan(harga_beli.value.replace(/\D/g, ""));
            harga_jual.value = formatRibuan(harga_jual.value.replace(/\D/g, ""));
            
            document.getElementById("jumlah_asli").value = unformat(jumlah.value);
            document.getElementById("harga_beli_asli").value = unformat(harga_beli.value);
            document.getElementById("harga_jual_asli").value = unformat(harga_jual.value);
        });

        function unformat(angka) {
            return parseInt(angka.replace(/\D/g, "")) || 0;
        }

        function formatRibuan(angka) {
            return new Intl.NumberFormat("id-ID").format(angka);
        }

        function updateKeuntungan() {

            const hargaBeli = parseFloat(document.getElementById('harga_beli_asli').value) || 0;
            const hargaJual = parseFloat(document.getElementById('harga_jual_asli').value) || 0;
            const jumlah = parseInt(document.getElementById('jumlah_asli').value) || 0;

            const keuntungan = hargaJual - hargaBeli;
            document.getElementById('keuntungan').value = keuntungan.toLocaleString('id-ID');

            const totalKeuntungan = keuntungan * jumlah;
            document.getElementById('total_keuntungan').value = totalKeuntungan.toLocaleString('id-ID');
        }

        document.getElementById('harga_beli').addEventListener('input', function() {
            this.value = formatRibuan(this.value.replace(/\D/g, ""));
            document.getElementById("harga_beli_asli").value = unformat(this.value);
            updateKeuntungan()
        });
        document.getElementById('harga_jual').addEventListener('input', function() {
            this.value = formatRibuan(this.value.replace(/\D/g, ""));
            document.getElementById("harga_jual_asli").value = unformat(this.value);
            updateKeuntungan()
        });
        document.getElementById('jumlah').addEventListener('input', function() {
            this.value = formatRibuan(this.value.replace(/\D/g, ""));
            document.getElementById("jumlah_asli").value = unformat(this.value);
            updateKeuntungan()
        });

        updateKeuntungan();
    </script>
@endsection
