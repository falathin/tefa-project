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
                                        value="{{ old('nama_sparepart', $sparepart->nama_sparepart ?? '') }}">
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
                                    <!-- KEMBALIKAN jadi text karena spek adalah string -->
                                    <input type="text" name="spek" id="spek"
                                        class="form-control @error('spek') is-invalid @enderror"
                                        value="{{ old('spek', $sparepart->spek ?? '') }}" maxlength="255" placeholder="Contoh: OEM, Tipe A, 12V, dll.">
                                    @error('spek')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Spesifikasi harus diisi (string).
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="jumlah_display"><i class="bi bi-stack"></i>&nbsp; Jumlah:</label></td>
                                <td>
                                    <!-- Display field (untuk format ribuan) -->
                                    <input type="text" id="jumlah_display"
                                        class="form-control @error('jumlah') is-invalid @enderror"
                                        value="{{ number_format(old('jumlah', $sparepart->jumlah ?? 0), 0, ',', '.') }}">
                                    <!-- Hidden field yang dikirim ke server -->
                                    <input type="hidden" name="jumlah" id="jumlah" value="{{ old('jumlah', $sparepart->jumlah ?? 0) }}">
                                    @error('jumlah')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Jumlah harus berupa angka dan minimal bernilai 1.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="harga_beli_display"><i class="bi bi-cash-stack"></i>&nbsp; Harga Beli :</label></td>
                                <td>
                                    <!-- display -->
                                    <input type="text" id="harga_beli_display"
                                        class="form-control @error('harga_beli') is-invalid @enderror"
                                        value="{{ number_format(old('harga_beli', $sparepart->harga_beli ?? 0), 0, ',', '.') }}">
                                    <!-- hidden untuk dikirim -->
                                    <input type="hidden" name="harga_beli" id="harga_beli" value="{{ old('harga_beli', $sparepart->harga_beli ?? 0) }}">
                                    @error('harga_beli')
                                        <div class="alert alert-danger mt-2">
                                            <strong>Error:</strong> Harga beli harus berupa angka dan tidak boleh kosong.
                                        </div>
                                    @enderror
                                </td>
                            </tr>

                            <tr>
                                <td><label for="harga_jual_display"><i class="bi bi-tag"></i>&nbsp; Harga Jual:</label></td>
                                <td>
                                    <!-- display -->
                                    <input type="text" id="harga_jual_display"
                                        class="form-control @error('harga_jual') is-invalid @enderror"
                                        value="{{ number_format(old('harga_jual', $sparepart->harga_jual ?? 0), 0, ',', '.') }}">
                                    <!-- hidden untuk dikirim -->
                                    <input type="hidden" name="harga_jual" id="harga_jual" value="{{ old('harga_jual', $sparepart->harga_jual ?? 0) }}">
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
                                        value="{{ old('tanggal_masuk', $sparepart->tanggal_masuk ?? '') }}">
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
                                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $sparepart->deskripsi ?? '') }}</textarea>
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
        // Utility: unformat string like "1.234.567" => number 1234567
        function unformatToInt(str) {
            if (str === undefined || str === null) return 0;
            // remove non-digit characters
            const cleaned = String(str).replace(/\./g, '').replace(/,/g, '.').replace(/[^0-9\-\.]/g, '');
            const n = parseFloat(cleaned);
            return isNaN(n) ? 0 : Math.round(n);
        }

        // Utility: format integer to "1.234.567"
        function formatRibuan(angka) {
            if (angka === '' || angka === null || angka === undefined) return '';
            const n = Number(angka) || 0;
            return new Intl.NumberFormat('id-ID').format(n);
        }

        // Lakukan update keuntungan berdasarkan hidden numeric values
        function updateKeuntungan() {
            const hargaBeli = unformatToInt(document.getElementById('harga_beli').value);
            const hargaJual = unformatToInt(document.getElementById('harga_jual').value);
            const jumlah = unformatToInt(document.getElementById('jumlah').value);

            const keuntungan = hargaJual - hargaBeli;
            const totalKeuntungan = keuntungan * jumlah;

            document.getElementById('keuntungan').value = formatRibuan(keuntungan);
            document.getElementById('total_keuntungan').value = formatRibuan(totalKeuntungan);
        }

        // Sinkronisasi: ketika user mengetik di display field, update hidden numeric field + hitung ulang
        function attachSyncListeners() {
            const pairs = [
                { displayId: 'harga_beli_display', hiddenId: 'harga_beli' },
                { displayId: 'harga_jual_display', hiddenId: 'harga_jual' },
                { displayId: 'jumlah_display', hiddenId: 'jumlah' }
            ];

            pairs.forEach(pair => {
                const display = document.getElementById(pair.displayId);
                const hidden = document.getElementById(pair.hiddenId);

                // on input: keep only digits, format display, fill hidden
                display.addEventListener('input', function (e) {
                    // ambil angka (hilangkan semua selain digits)
                    const onlyDigits = this.value.replace(/[^0-9]/g, '');
                    // update display dengan format
                    this.value = formatRibuan(onlyDigits);
                    // update hidden (numeric)
                    hidden.value = unformatToInt(this.value);
                    // hitung ulang
                    updateKeuntungan();
                });

                // juga support paste
                display.addEventListener('paste', function (e) {
                    setTimeout(() => {
                        const onlyDigits = this.value.replace(/[^0-9]/g, '');
                        this.value = formatRibuan(onlyDigits);
                        hidden.value = unformatToInt(this.value);
                        updateKeuntungan();
                    }, 0);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan nilai hidden sudah ada (di-set oleh blade dengan old/model).
            // Isi display field sesuai dengan hidden (agar konsisten)
            document.getElementById('jumlah_display').value = formatRibuan(document.getElementById('jumlah').value || 0);
            document.getElementById('harga_beli_display').value = formatRibuan(document.getElementById('harga_beli').value || 0);
            document.getElementById('harga_jual_display').value = formatRibuan(document.getElementById('harga_jual').value || 0);

            attachSyncListeners();

            // Hitung pertama kali tanpa menunggu interaksi user
            updateKeuntungan();
        });
    </script>
@endsection
