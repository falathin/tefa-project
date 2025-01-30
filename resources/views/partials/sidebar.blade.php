<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav flex-column">
        <!-- Dashboard Menu Item -->
        <li
            class="nav-item {{ request()->route()->getName() == 'dashboard' ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-0.3s">
            <a class="nav-link" href="{{ url('/') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item nav-category animate__animated animate__slideInLeft animate__delay-1.8s">Pengaturan</li>

        <!-- Program Service Menu Item -->
        @if (Gate::allows('isAdminOrEngineer') xor Gate::allows('isKasir'))
        <li
            class="nav-item {{ request()->route()->getName() == 'service' || request()->route()->getName() == 'customer.show' ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-3.3s">
            <a class="nav-link" href="{{ route('customer.index') }}">
                <i class="mdi mdi-cog-outline menu-icon"></i>
                <span class="menu-title">Program Layanan</span>
            </a>
        </li>
        @endif

        <!-- Sparepart Menu -->
        <li
            class="nav-item {{ Request::is('sparepart.*') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-4.8s">
            <a class="nav-link" data-bs-toggle="collapse" href="#sparepartMenu"
                aria-expanded="{{ Request::is('sparepart*') ? 'true' : 'false' }}" aria-controls="sparepartMenu">
                <i class="mdi mdi-toolbox-outline menu-icon"></i>
                <span class="menu-title">Suku Cadang</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sparepartMenu">
                <ul class="nav flex-column sub-menu">
                    <li
                    class="nav-item {{ Request::routeIs('sparepart.index') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-6.3s">
                    <a class="nav-link" href="{{ route('sparepart.index') }}">Data Suku Cadang</a>
                </li>
                <li
                        class="nav-item {{ Request::routeIs('transactions.index') ? 'active' : '' }} animate__animated animate__slideInLeft animate__delay-7.8s">
                        <a class="nav-link" href="{{ route('transactions.index') }}">Transaksi Suku Cadang</a>
                    </li>
                </ul>
            </div>
        </li>
        {{-- @elseif (Gate::allows('isBendahara')) --}}
        {{-- @if (Gate::allows('isAdminOrEngineer') xor Gate::allows('isKasir')) --}}
        {{-- @endif --}}

        @if (Gate::allows('isAdminOrEngineer') xor Gate::allows('isKasir'))
        <!-- Riwayat Service Menu Item -->
        <li class="nav-item animate__animated animate__slideInLeft animate__delay-9.3s">
            <a class="nav-link {{ request()->route()->getName() == 'service.index' ? 'active' : '' }}"
                href='{{ route('service.index') }}'>
                <i class="mdi mdi-history menu-icon"></i>
                <span class="menu-title">Riwayat Layanan</span>
            </a>
        </li>
        @endif

        <!-- Documentation Menu Item -->
        <li class="nav-item animate__animated animate__slideInLeft animate__delay-10.8s">
            <a class="nav-link request()->route()->getName() == 'documentation' ? 'active' : '' }}"
                href="{{ route('documentation') }}">
                <i class="mdi mdi-file-document menu-icon"></i>
                <span class="menu-title">Dokumentasi Web</span>
            </a>
        </li>
    </ul>
</nav>