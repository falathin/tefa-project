<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav flex-column">
        <!-- Dashboard Menu Item -->
        <li class="nav-item {{ request()->is('/') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-0.3s">
            <a class="nav-link" href="{{ url('/') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item nav-category animate__animated animate__slideInLeft animate__delay-1.8s">Pengaturan</li>

        <!-- Program Service Menu Item -->
        @if (Gate::allows('isAdminOrEngineer') xor Gate::allows('isKasir'))
        <li class="nav-item {{ request()->is('service*') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-3.3s">
            <a class="nav-link" href="{{ route('customer.index') }}">
                <i class="mdi mdi-cog-outline menu-icon"></i>
                <span class="menu-title">Layanan Servis</span>
            </a>
        </li>
        @endif

        <!-- Sparepart Menu -->
        <li class="nav-item {{ request()->is('sparepart*') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-4.8s">
            <a class="nav-link" data-bs-toggle="collapse" href="#sparepartMenu"
                aria-expanded="{{ request()->is('sparepart*') ? 'true' : 'false' }}" aria-controls="sparepartMenu">
                <i class="mdi mdi-toolbox-outline menu-icon"></i>
                <span class="menu-title">Sparepart</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('sparepart*') ? 'show' : '' }}" id="sparepartMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('sparepart') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-6.3s">
                        <a class="nav-link" href="{{ route('sparepart.index') }}">Data Sparepart</a>
                    </li>
                    <li class="nav-item {{ request()->is('transactions*') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-7.8s">
                        <a class="nav-link" href="{{ route('transactions.index') }}">Transaksi Sparepart</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Riwayat Service Menu Item -->
        <li class="nav-item {{ request()->is('service*') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-9.3s">
            <a class="nav-link" href="{{ route('service.index') }}">
                <i class="mdi mdi-history menu-icon"></i>
                <span class="menu-title">Riwayat Servis</span>
            </a>
        </li>

        <!-- Documentation Menu Item -->
        <li class="nav-item {{ request()->is('documentation') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-10.8s">
            <a class="nav-link" href="{{ route('documentation') }}">
                <i class="mdi mdi-file-document menu-icon"></i>
                <span class="menu-title">Dokumentasi Web</span>
            </a>
        </li>
    </ul>
</nav>