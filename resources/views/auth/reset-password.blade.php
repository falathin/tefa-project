{{-- resources/views/auth/reset-password.blade.php --}}
<x-layout></x-layout>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-light" style="width: 32rem;">

        <div class="text-center mb-4">
            <h3 class="fw-bold">Reset password</h3>
            <p class="text-muted"></p>
        </div>

        <form action="{{ route('password.store') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $request->email }}">
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Masukkan Password baru</label>
                <div class="position-relative">
                    <input type="password" class="form-control pe-5" id="password" name="password"
                        autocomplete="new-password" autofocus required>
                    <button type="button"
                        class="position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent"
                        onclick="togglePassword('password', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                            class="eye-icon" viewBox="0 0 16 16">
                            <path
                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM8 11.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7z" />
                            <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Konfirmasi --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password baru</label>
                <div class="position-relative">
                    <input type="password" class="form-control pe-5" id="password_confirmation"
                        name="password_confirmation" required>
                    <button type="button"
                        class="position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent"
                        onclick="togglePassword('password_confirmation', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                            class="eye-icon" viewBox="0 0 16 16">
                            <path
                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM8 11.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7z" />
                            <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                        </svg>
                    </button>
                </div>
            </div>


            <div class="py-3">
                <button type="submit" class="btn text-light w-100 bg-danger fw-bold">Reset Password</button>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('status'))
                <div class="bg-green-100 text-green-800 p-3 rounded">
                    {{ session('status') }}
                </div>
            @endif
        </form>
    </div>
</div>

{{-- Script buat toggle icon dan tipe input --}}
<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        // Ganti icon SVG
        const eyeOpen = `
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM8 11.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7z"/>
              <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
          </svg>
      `;

        const eyeClosed = `
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 16 16">
              <path d="M13.359 11.238 15.5 13.38l-1.06 1.06-2.143-2.143A8.093 8.093 0 0 1 8 13.5C3 13.5 0 8 0 8a13.134 13.134 0 0 1 3.22-3.857L1.4 2.32 2.46 1.26l12.04 12.04-1.14 1.14-1.06-1.06z"/>
              <path d="M10.477 8.356a2.5 2.5 0 0 0-3.54-3.54l3.54 3.54z"/>
          </svg>
      `;

        btn.innerHTML = isPassword ? eyeClosed : eyeOpen;
    }
</script>
