<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav flex-column">
        <!-- Dashboard Menu Item -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item nav-category">Pengaturan</li>

        <!-- Program Service Menu Item -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('servis*') ? 'active' : '' }}" href="{{ route('servis.index') }}">
                <i class="mdi mdi-cog-outline menu-icon"></i>
                <span class="menu-title">Program Service</span>
            </a>
        </li>

        <!-- Data SparePart Menu Item -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('sparepart*') ? 'active' : '' }}" href="{{ route('sparepart.index') }}">
                <i class="mdi mdi-toolbox-outline menu-icon"></i>
                <span class="menu-title">Data SparePart</span>
            </a>
        </li>

        <!-- Riwayat Service Menu Item -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('riwayat-service') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-history menu-icon"></i>
                <span class="menu-title">Riwayat Service</span>
            </a>
        </li>

        <!-- Documentation Menu Item -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('dokumentasi') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-file-document menu-icon"></i>
                <span class="menu-title">Dokumentasi</span>
            </a>
        </li>
    </ul>
</nav>