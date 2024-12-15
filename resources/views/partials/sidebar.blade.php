<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav flex-column">
        <!-- Dashboard Menu Item -->
        <li class="nav-item animate__animated animate__slideInLeft animate__delay-0.3s">
            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item nav-category animate__animated animate__slideInLeft animate__delay-1.8s">Pengaturan</li>

        <!-- Program Service Menu Item -->
        <li class="nav-item animate__animated animate__slideInLeft animate__delay-3.3s">
            <a class="nav-link {{ Request::is('customer*') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                <i class="mdi mdi-cog-outline menu-icon"></i>
                <span class="menu-title">Program Layanan</span>
            </a>
        </li>

        <!-- Sparepart Menu -->
        <li class="nav-item animate__animated animate__slideInLeft animate__delay-4.8s">
            <a class="nav-link {{ Request::is('sparepart*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#sparepartMenu" aria-expanded="false" aria-controls="sparepartMenu">
                <i class="mdi mdi-toolbox-outline menu-icon"></i>
                <span class="menu-title">Suku Cadang</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('sparepart*') ? 'show' : '' }}" id="sparepartMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item animate__animated animate__slideInLeft animate__delay-6.3s">
                        <a class="nav-link {{ Request::is('sparepart') ? 'active' : '' }}" href="{{ route('sparepart.index') }}">Data Suku Cadang</a>
                    </li>
                    <li class="nav-item animate__animated animate__slideInLeft animate__delay-7.8s">
                        <a class="nav-link {{ Request::is('sparepart/transaction*') ? 'active' : '' }}" href="https://coming-soon-my-work.netlify.app/" style="text-decoration: line-through;">Transaksi Suku Cadang</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Riwayat Service Menu Item -->
        <li class="nav-item animate__animated animate__slideInLeft animate__delay-9.3s">
            <a class="nav-link {{ Request::is('service*') ? 'active' : '' }}" href="{{ route('service.index') }}">
                <i class="mdi mdi-history menu-icon"></i>
                <span class="menu-title">Riwayat Layanan</span>
            </a>
        </li>

        <li class="nav-item animate__animated animate__slideInLeft animate__delay-10.8s">
            <a class="nav-link {{ Request::is('documentation') ? 'active' : '' }}" href="{{ route('documentation') }}">
                <i class="mdi mdi-file-document menu-icon"></i>
                <span class="menu-title">Dokumentasi Web</span>
            </a>
        </li>             
    </ul>
</nav>