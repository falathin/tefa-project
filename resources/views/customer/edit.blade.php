@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm animate__animated animate__fadeIn">
        <div class="card-body">
            <h5 class="card-title text-center">
                <i class="fas fa-user-edit"></i> Edit Pelanggan
            </h5>

            <!-- Menampilkan alert sukses jika ada -->
            @if(session('success'))
                <div class="alert alert-success animate__animated animate__fadeInDown">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Pelanggan -->
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Nama Pelanggan</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $customer->name) }}" required>
                </div>

                <!-- No HP (contact) -->
                <div class="form-group">
                    <label for="contact"><i class="fas fa-phone"></i> No HP</label>
                    <input type="text" class="form-control" id="contact" name="contact" 
                           value="{{ old('contact', $customer->contact) }}" placeholder="contoh: 0812-3456-7890">
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label for="address"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea class="form-control" id="address" name="address">{{ old('address', $customer->address) }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning mt-3">
                    <i class="fas fa-save"></i> Update
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk format nomor telepon (hanya pada tampilan) -->
<script>
    const contactInput = document.getElementById('contact');
    contactInput.addEventListener('input', function(e) {
        let inputVal = e.target.value;
        // Jika dimulai dengan +62, ubah menjadi 08
        if (inputVal.startsWith('+62')) {
            inputVal = '08' + inputVal.slice(3);
        }
        // Hapus karakter selain angka
        let digits = inputVal.replace(/\D/g, '');
        // Terapkan format strip: misalnya 4 digit pertama - 4 digit berikutnya - sisa digit
        if (digits.length > 4 && digits.length <= 8) {
            inputVal = digits.substring(0, 4) + '-' + digits.substring(4);
        } else if (digits.length > 8) {
            inputVal = digits.substring(0, 4) + '-' + digits.substring(4, 8) + '-' + digits.substring(8);
        } else {
            inputVal = digits;
        }
        e.target.value = inputVal;
    });
</script>
@endsection