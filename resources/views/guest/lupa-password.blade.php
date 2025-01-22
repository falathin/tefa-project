<x-layout></x-layout>

{{-- 'email' => 'required|email',
'emergency_password' => 'required',
'new_password' => 'required|min:8|confirmed', --}}

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-light" style="width: 32rem;">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Lupa password</h3>
            <p class="text-muted"></p>
        </div>
        <form action="{{ route('lupa.password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Masukkan Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="contoh@gmail.com"
                    value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div class="mb-3">
                <label for="emergency_password" class="form-label">Masukkan Emergency Password</label>
                <input type="password" class="form-control" id="emergency_password" name="emergency_password" required>
            </div>

            <!-- 1 column grid layout for inline styling -->
            <div class="row mb-4">
                <div class="col d-flex">
                    <!-- Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPassword"/>
                        <label class="form-check-label" for="showPassword">lihat password</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Masukkan Password baru</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Konfirmasi Password baru</label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>

            <div class="py-3">
                <button type="submit" class="btn text-light w-100 bg-danger fw-bold">Reset Password</button>
            </div>

            chat via <a href="https://wa.me/6285692548351" class="text-decoration-none text-success fw-bold">Whatsapp</a> untuk mendapatkan Emergency password

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</div>
<script>
    const passwordInput = document.getElementById('emergency_password');
    const showPasswordCheckbox = document.getElementById('showPassword');

    showPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>
