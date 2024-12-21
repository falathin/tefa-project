<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
            </a>

            <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Selamat Datang <span class="text-black fw-bold">{{ Auth::user()->name }}</span>
                </h1>
                <h3 class="welcome-sub-text">Ringkasan kinerja Anda minggu ini</h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">

            @include('partials.theme')

            @include('partials.notification_dropdown')


            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle" src="{{ asset('assets/images/profile.png') }}"
                        alt="Profile image">
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center mt-3">
                        {{-- <img class="img-md rounded-circle" src="{{ asset('assets/images/logo-mini.svg') }}"
                            alt="Profile image" width="100px"> --}}
                        <h1>{{ Auth::user()->jurusan }}</h1>
                        <p class="mb-1 mt-3 fw-semibold">{{ Auth::user()->name }}</p>
                        <p class="fw-light text-muted mb-0">{{ Auth::user()->email }}</p>
                        <p class="fw-semibold text-muted mb-0">{{ Auth::user()->level }}</p>
                    </div>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                            class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>Profil Saya
                    </a>
                    @if (Auth::user()->level == 'admin' || Auth::user()->level == 'engineer')
                        <a class="dropdown-item" href="{{ route('profile.daftar') }}"><i
                                class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i>
                            Buat akun baru
                        </a>
                        <a class="dropdown-item" href="{{ route('hapusAkunUser') }}"><i
                                class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i>
                            Hapus akun</a>
                    @endif
                    @if (Auth::user()->level == 'engineer')
                        <a class="dropdown-item" href="{{ route('gantiEmergencyPassword') }}"><i
                                class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i>
                            Ganti Emergency password</a>
                    @endif

                    <a class="dropdown-item" href="{{ route('confirm.logout') }}"><i
                            class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>
                        Logout</a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
