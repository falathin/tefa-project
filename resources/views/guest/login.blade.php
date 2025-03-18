<x-layout>Login</x-layout>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-light" style="width: 24rem;">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Masuk ke Akun</h3>
            <p class="text-muted">Silakan gunakan akun yang terdaftar</p>
        </div>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" placeholder="contoh@gmail.com" value="{{ old('email') }}" required autofocus
                    autocomplete="email">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Tampilkan pesan error rate limiting dalam card -->
            @if ($errors->has('throttle'))
                <div class="card border-danger mb-3" id="throttleMessage">
                    <div class="card-body text-danger">
                        {!! $errors->first('throttle') !!}
                    </div>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col d-flex">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPassword" />
                        <label class="form-check-label" for="showPassword">Tampilkan Sandi</label>
                    </div>
                </div>
                <div class="col text-end">
                    <a href="{{ route('lupa.password') }}" class="text-danger text-decoration-none">
                        Lupa Sandi?
                    </a>
                </div>
            </div>

            <div class="py-1">
                <button type="submit" class="btn btn-danger w-100 fw-bold" id="loginButton">Masuk</button>
            </div>

            @if (session('status'))
                <div class="alert alert-success mt-3">
                    {{ session('status') }}
                </div>
            @endif
        </form>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('showPassword');

    showPasswordCheckbox.addEventListener('change', function() {
        passwordInput.type = this.checked ? 'text' : 'password';
    });

    // Jika pesan throttle ada, jalankan countdown live
    const countdownElement = document.getElementById('countdown');
    if (countdownElement) {
        const loginButton = document.getElementById('loginButton');
        loginButton.disabled = true;
        let timeLeft = parseInt(countdownElement.textContent);
        const interval = setInterval(() => {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(interval);
                loginButton.disabled = false;
                const throttleMessage = document.getElementById('throttleMessage');
                if (throttleMessage) {
                    throttleMessage.style.display = 'none';
                }
            }
        }, 1000);
    }
</script>