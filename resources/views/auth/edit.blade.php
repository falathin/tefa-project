<x-layout-profile></x-layout-profile>
@if (session('statusBerhasil'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('statusBerhasil') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<x-nav-auth>Akun</x-nav-auth>
<section class="my-5 bg-light container rounded-3 py-2">
    <div class="p-3">
        <div class="fs-4 ">Informasi akun</div>
        <h6 class="fw-light">Ubah username dan email anda disini.</h6>

        <form action="{{ route('profile.update') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-3 ">
                <label for="name" class="form-label">Username</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ Auth::user()->name }}" required autocomplete="name">
            </div>

            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ Auth::user()->email }}" required autocomplete="email"
                id="email" name="email">

            <div class="mt-4">
                <button type="submit" class="btn text-light w-20 bg-danger fw-semibold">Simpan</button>
            </div>
        </form>
    </div>
</section>

<section class="my-5 bg-light container rounded-3 py-2">
    <div class="p-3">
        <div class="fs-4 ">Ubah password</div>
        <h6 class="fw-light">Direkomendasikan password menggunakan kombinasi angka dan huruf.</h6>

        <form action="{{ route('update.password') }}" method="POST" class="mt-4">
            @csrf
            {{-- @method('put') --}}
            <div class="mb-3 ">
                <label for="current_password" class="form-label">Password anda</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" id="show-current-password" />
                    <label class="form-check-label" for="show-current-password">lihat password</label>
                </div>
            </div>

            <label for="new_password" class="form-label">Password baru</label>
            <input type="password" class="form-control" required id="new_password" name="new_password"
                placeholder="panjang password minimal 8 karakter">
            <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" id="show-new-password" />
                <label class="form-check-label" for="show-new-password">lihat password</label>
            </div>

            <label for="confirm-password" class="form-label mt-3">Konfirmasi Password baru</label>
            <input type="password" class="form-control" required id="confirm-password" name="confirm-password">

            <div class="mt-4">
                <button type="submit" class="btn text-light bg-danger fw-semibold">Ubah password</button>
            </div>
        </form>
        @error('current_password')
            <div class="alert alert-danger mt-5">
                <ul>
                    <li>{{ $message }}</li>
                </ul>
            </div>
        @enderror
    </div>
</section>
<script>
    const currentPasswordInput = document.getElementById('current_password');
    const showCurrentPassword = document.getElementById('show-current-password');

    const newPasswordInput = document.getElementById('new_password');
    const showNewPassword = document.getElementById('show-new-password');

    showCurrentPassword.addEventListener('change', function() {
        if (this.checked) {
            currentPasswordInput.type = 'text';
        } else {
            currentPasswordInput.type = 'password';
        }
    });
    showNewPassword.addEventListener('change', function() {
        if (this.checked) {
            newPasswordInput.type = 'text';
        } else {
            newPasswordInput.type = 'password';
        }
    });
</script>
