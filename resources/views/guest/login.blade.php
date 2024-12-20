<x-layout>Login</x-layout>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-light" style="width: 24rem;">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Login</h3>
            <p class="text-muted"></p>
        </div>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="contoh@gmail.com"
                    value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="text-red-500">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <!-- 2 column grid layout for inline styling -->
            <div class="row mb-4">
                <div class="col d-flex">
                    <!-- Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPassword" />
                        <label class="form-check-label" for="showPassword">lihat password</label>
                    </div>
                </div>

                <div class="col">
                    <a href="{{ route('lupa.password') }}" class="text-danger text-decoration-none text-danger">lupa
                        password?</a>
                </div>
            </div>

            <div class="py-1">
                <button type="submit" class="btn text-light w-100 bg-danger fw-bold">Kirim</button>
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
            @if (session('status'))
                <div class="alert alert-success">
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
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>
