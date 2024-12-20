<x-layout></x-layout>
<x-nav-auth>Ganti Emergency Password</x-nav-auth>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-light" style="width: 24rem;">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Ganti Emergency Password</h3>
            <p class="text-muted"></p>
        </div>
        <form action="{{ route('gantiEmergencyPassword') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="current_password" class="form-label">Emergency password</label>
                <input type="email" class="form-control" id="current_password" name="current_password"
                    value="{{ $data->emergency_password }}" readonly>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Ganti Emergency password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
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
            </div>

            <div class="py-1">
                <button type="submit" class="btn text-light w-100 bg-danger fw-bold">Ganti</button>
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
