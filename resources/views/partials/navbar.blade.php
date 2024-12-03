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
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator position-relative" id="notificationDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- Conditional Class for Notification Bell -->
                    <i class="bi bi-bell-fill fs-4 {{ App\Models\Notification::where('is_read', false)->count() > 0 ? 'text-warning animate__animated animate__jello animate__infinite animate__slow' : 'text-dark' }}"></i>
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-circle py-1 px-2 fs-8">
                        {{ App\Models\Notification::where('is_read', false)->count() }}
                    </span>                    
                </a>
                <ul class="dropdown-menu dropdown-menu-end navbar-dropdown shadow-lg rounded-3" aria-labelledby="notificationDropdown">
                    <!-- Header Notifikasi -->
                    <li class="dropdown-header px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                        <p class="mb-0 fw-bold text-dark">
                            <i class="bi bi-bell-fill me-2 text-warning fs-5"></i>
                            Anda memiliki <strong>{{ App\Models\Notification::where('is_read', false)->count() }}</strong> notifikasi baru
                        </p>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary text-white">
                            <i class="bi bi-eye-fill"></i>&nbsp; Lihat Semua
                        </a>
                    </li>
                
                    <!-- Daftar Notifikasi -->
                    @if(App\Models\Notification::where('is_read', false)->count() > 0)
                        @foreach(App\Models\Notification::where('is_read', false)->latest()->take(5)->get() as $notification)
                        <li class="dropdown-item d-flex justify-content-between align-items-start px-3 py-2">
                            <div class="flex-grow-1">
                                <p class="mb-1 text-truncate text-dark" style="max-width: 200px;">
                                    <i class="bi bi-info-circle-fill text-warning me-2"></i>
                                    {{ $notification->message }}
                                </p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="d-flex flex-column align-items-end ms-3">
                                <!-- Check if there's a related sparepart -->
                                @if($notification->sparepart)
                                    <a href="{{ route('sparepart.edit', $notification->sparepart->id) }}" class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                @else
                                    <span class="text-muted">No Sparepart</span>
                                @endif
            
                                <!-- Mark notification as read -->
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-success p-0">
                                        <i class="bi bi-check-circle-fill"></i>&nbsp;&nbsp; Tandai Dibaca
                                    </button>
                                </form>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @endforeach
                    
                    @else
                        <li class="dropdown-item text-center py-3 d-flex justify-content-center align-items-center">
                            <p class="mb-0 text-muted animate__animated animate__jello animate__infinite animate__slow">
                                <i class="bi bi-emoji-smile-fill text-success"></i>
                                &nbsp;&nbsp; Tidak ada notifikasi baru
                            </p>
                        </li>
                    @endif                            
                
                    <!-- Footer -->
                    <li class="dropdown-footer text-center py-2 mt-2">
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary text-white">
                            <i class="bi bi-arrow-right-circle"></i>&nbsp;&nbsp; Lihat Semua Notifikasi
                        </a>
                    </li>
                </ul>
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
