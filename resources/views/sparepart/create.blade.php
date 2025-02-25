@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Sparepart</h1>
        <br>

        <form action="{{ route('sparepart.store') }}" method="POST">
            @csrf

            <input type="text" value="{{ Auth::user()->jurusan }}" name="jurusan" id="jurusan" hidden>
            <div class="card mb-3 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Sparepart</h5>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="nama_sparepart"><i class="bi bi-wrench"></i>&nbsp; Nama Sparepart:</label>
                        <input type="text" name="nama_sparepart" id="nama_sparepart"
                            class="form-control @error('nama_sparepart') is-invalid @enderror"
                            value="{{ old('nama_sparepart') }}">
                        @error('nama_sparepart')
                            <div class="alert alert-danger mt-2">
                                <strong>Error:</strong> Nama sparepart harus diisi dan tidak boleh melebihi 255 karakter.
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="spek"><i class="bi bi-wrench"></i>&nbsp; Spesifikasi Sparepart:</label>
                        <input type="text" name="spek" id="spek"
                            class="form-control @error('spek') is-invalid @enderror" value="{{ old('spek') }}">
                        @error('spek')
                            <div class="alert alert-danger mt-2">
                                <strong>Error:</strong> Spesifikasi sparepart harus diisi dan tidak boleh melebihi 255 karakter.
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah"><i class="bi bi-stack"></i>&nbsp; Jumlah:</label>
                        <input type="text" id="jumlah"
                            class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}"
                            min="1">
                        <input type="hidden" id="jumlah_asli" name="jumlah" >
                        @error('jumlah')
                            <div class="alert alert-danger mt-2">
                                <strong>Error:</strong> Jumlah harus berupa angka dan minimal bernilai 1.
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga_beli"><i class="bi bi-cash-stack"></i>&nbsp; Harga Beli:</label>
                        <input type="text" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror"
                            value="{{ old('harga_beli') }}" step="0.01">
                        <input type="hidden" id="harga_beli_asli" name="harga_beli">
                        @error('harga_beli')
                            <div class="alert alert-danger mt-2">
                                <strong>Error:</strong> Harga beli harus berupa angka dan tidak boleh kosong.
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga_jual"><i class="bi bi-tag"></i>&nbsp; Harga Jual:</label>
                        <input type="text" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror"
                            value="{{ old('harga_jual') }}" step="0.01">
                        <input type="hidden" id="harga_jual_asli" name="harga_jual">
                        @error('harga_jual')
                            <div class="alert alert-danger mt-2">
                                <strong>Error:</strong> Harga jual harus berupa angka dan tidak boleh kosong.
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="keuntungan"><i class="bi bi-calculator"></i>&nbsp; Keuntungan (Per Barang):</label>
                        <input type="text" id="keuntungan" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="total_keuntungan"><i class="bi bi-wallet2"></i>&nbsp; Total Keuntungan:</label>
                        <input type="text" id="total_keuntungan" class="form-control" readonly>
                        <input type="hidden" id="total_keuntungan_asli">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_masuk"><i class="bi bi-calendar-plus"></i>&nbsp; Tanggal Masuk:</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                            class="form-control @error('tanggal_masuk') is-invalid @enderror"
                            value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}">
                        @error('tanggal_masuk')
                            <div class="alert alert-danger mt-2">
                                <strong>Error:</strong> Tanggal masuk harus diisi dengan format tanggal yang valid.
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi"><i class="bi bi-info-circle"></i>&nbsp; Deskripsi:</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success mt-3"><i class="bi bi-save"></i>&nbsp; Simpan
                        Sparepart</button>
                    <a href="{{ route('sparepart.index') }}" class="btn btn-secondary mt-3 ml-2"><i
                            class="bi bi-arrow-left"></i>&nbsp; Kembali</a>
                </div>
            </div>

        </form>
    </div>

    <script>
        function formatRibuan(angka) {
            return new Intl.NumberFormat("id-ID").format(angka);
        }

        function unformat(angka) {
            return parseInt(angka.replace(/\D/g, "")) || 0;
        }

        // function updateKeuntungan() {
        //     const hargaBeli = parseFloat(document.getElementById('harga_beli').value) || 0;
        //     const hargaJual = parseFloat(document.getElementById('harga_jual').value) || 0;
        //     const jumlah = parseInt(document.getElementById('jumlah').value) || 0;

        //     const keuntungan = hargaJual - hargaBeli;
        //     document.getElementById('keuntungan').value = keuntungan.toLocaleString('id-ID');

        //     const totalKeuntungan = keuntungan * jumlah;
        //     document.getElementById('total_keuntungan').value = totalKeuntungan.toLocaleString('id-ID');
        // }

        function updateKeuntungan() {
            let hargaBeli = parseFloat(document.getElementById('harga_beli_asli').value) || 0;
            let hargaJual = parseFloat(document.getElementById('harga_jual_asli').value) || 0;
            let jumlah = parseInt(document.getElementById('jumlah_asli').value) || 0;

            let keuntungan = hargaJual - hargaBeli;
            let totalKeuntungan = keuntungan * jumlah;

            document.getElementById('keuntungan').value = keuntungan;
            document.getElementById('total_keuntungan').value = totalKeuntungan;

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

        document.getElementById('total_keuntungan').addEventListener('input', function() {
            this.value = formatRibuan(this.value.replace(/\D/g, ""));
            updateKeuntungan()
        });

    </script>

    {{-- contoh kode currency --}}
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            let spareparts = @json($service->serviceSpareparts);
            let totalSparepart = spareparts.reduce((total, item) => total + (item.sparepart.harga_jual * item
                .quantity), 0);

            function formatRibuan(angka) {
                return new Intl.NumberFormat("id-ID").format(angka);
            }

            function unformat(angka) {
                return parseInt(angka.replace(/\D/g, "")) || 0;
            }

            function updateTotalCost() {
                let serviceFee = unformat(document.getElementById("service_fee").value);
                let diskon = unformat(document.getElementById("diskon").value) / 100;
                let total = (serviceFee + totalSparepart) * (1 - diskon);

                document.getElementById("total_cost").value = formatRibuan(total);
                document.getElementById("total_cost_asli").value = total;
                updateChange();
            }

            function updateChange() {
                let totalCost = unformat(document.getElementById("total_cost").value);
                let paymentReceived = unformat(document.getElementById("payment_received").value);
                let change = paymentReceived - totalCost;

                document.getElementById("change").value = formatRibuan(change);
                document.getElementById("change_asli").value = change;
            }

            document.getElementById("service_fee").addEventListener("input", function() {
                this.value = formatRibuan(this.value.replace(/\D/g, ""));
                document.getElementById("service_fee_asli").value = unformat(this.value);
                updateTotalCost();
            });

            document.getElementById("diskon").addEventListener("input", function() {
                this.value = formatRibuan(this.value.replace(/\D/g, ""));
                document.getElementById("diskon_asli").value = unformat(this.value);
                updateTotalCost();
            });

            document.getElementById("payment_received").addEventListener("input", function() {
                this.value = formatRibuan(this.value.replace(/\D/g, ""));
                document.getElementById("payment_received_asli").value = unformat(this.value);
                updateChange();
            });

            updateTotalCost();
        });
    </script> --}}
@endsection
