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
                <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Selamat Pagi, <span class="text-black fw-bold">John Doe</span></h1>
                <h3 class="welcome-sub-text">Ringkasan kinerja Anda minggu ini</h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="themeDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Pilih Tema </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="themeDropdown">
                    <a class="dropdown-item py-3">
                        <p class="mb-0 fw-medium float-start">Pilih tema</p>
                    </a>
                    <div class="dropdown-divider"></div>
                    @foreach(['Tema Terang', 'Tema Gelap', 'Tema Warna-Warni', 'Tema Minimalis'] as $theme)
                        <a class="dropdown-item preview-item">
                            <div class="preview-item-content flex-grow py-2">
                                <p class="preview-subject fw-medium text-dark mb-1">{{ $theme }}</p>
                                <p class="fw-light small-text mb-0">Deskripsi: {{ $theme }}</p>
                            </div>                            
                        </a>
                    @endforeach
                </div>
            </li>
            <li class="nav-item d-none d-lg-block">
                <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                    <span class="input-group-addon input-group-prepend border-right">
                        <span class="icon-calendar input-group-text calendar-icon"></span>
                    </span>
                    <input type="text" class="form-control">
                </div>
            </li>
            <li class="nav-item">
                <form class="search-form" action="#">
                    <i class="icon-search"></i>
                    <input type="search" class="form-control" placeholder="Cari Disini" title="Search here">
                </form>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="icon-bell"></i>
                    <span class="count"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                    <a class="dropdown-item py-3 border-bottom">
                        <p class="mb-0 fw-medium float-start">Anda memiliki 4 notifikasi baru</p>
                        <span class="badge badge-pill badge-primary float-end">Lihat semua</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <!-- Notification items -->
                </div>
            </li>
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="Profile image">
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="Profile image">
                        <p class="mb-1 mt-3 fw-semibold">Allen Moreno</p>
                        <p class="fw-light text-muted mb-0">allenmoreno@gmail.com</p>
                    </div>
                    <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Profil Saya <span class="badge badge-pill badge-danger">1</span></a>
                    <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Pesan</a>
                    <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Aktivitas</a>
                    <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>
                    <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Keluar</a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
