<x-layout></x-layout>
<x-nav-auth>Daftar Akun</x-nav-auth>
<div class="container d-flex justify-content-center align-items-center vh-75 mt-5">
    <div class="card shadow-lg p-4 bg-light" style="width: 32rem;">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Daftar akun</h3>
            <p class="text-muted"></p>
        </div>
        <form action="{{ route('profile.daftar') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Masukkan username</label>
                <input type="text" class="form-control" id="username" name="username" required
                    autocomplete="username" autofocus value="{{ old('username') }}">
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">Masukkan Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="contoh@gmail.com"
                    value="{{ old('email') }}" required autocomplete="email">
            </div>

            <select class="form-select mb-4 w-50" aria-label="Pilih level akun" id="level" name="level" required>
                <option value="engineer">Engineer</option>
                <option value="admin">Admin</option>
                <option value="bendahara">Bendahara</option>
                <option value="kasir" selected>Kasir</option>
            </select>

            <div class="mb-1">
                <label for="new_password" class="form-label">Masukkan Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <!-- 1 column grid layout for inline styling -->
            <div class="row mb-3">
                <div class="col d-flex">
                    <!-- Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPassword" />
                        <label class="form-check-label" for="showPassword">lihat password</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="new_password_confirmation"
                    name="new_password_confirmation" required>
            </div>

            <div class="py-3">
                <button type="submit" class="btn text-light w-100 bg-danger fw-bold">Daftar</button>
            </div>

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
    const passwordInput = document.getElementById('new_password');
    const showPasswordCheckbox = document.getElementById('showPassword');

    showPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>
