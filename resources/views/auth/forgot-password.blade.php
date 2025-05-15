<x-layout></x-layout>

<script src="https://cdn.tailwindcss.com"></script>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-light" style="width: 32rem;">

        <div class="text-center mb-4">
            <h3 class="fw-bold">Lupa password</h3>
            <p class="text-muted"></p>
        </div>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Masukkan Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="contoh@gmail.com"
                    value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div class="py-3">
                <button type="submit" class="btn text-light w-100 bg-danger fw-bold">Kirim via Gmail</button>
            </div>

            {{-- chat via <a href="https://wa.me/6285719443650" class="text-decoration-none text-success fw-bold">Whatsapp</a> untuk mendapatkan Emergency password <br> --}}
            {{-- Klik <a href="{{ route('documentationGuest') }}" class="text-decoration-none text-danger fw-bold">Disini</a> untuk instruksi mendapatkan Emergency password  --}}
            {{-- <a href="{{ route('login') }}" class="text-decoration-none text-danger fw-bold">Kembali</a> --}}
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @if (session('status'))
                <div class="bg-green-100 text-green-800 p-3 rounded">
                    {{ session('status') }}
                </div>
            @endif
        </form>
    </div>
</div>
