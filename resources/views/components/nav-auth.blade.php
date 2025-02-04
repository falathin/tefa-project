<!-- As a heading -->
<nav class="navbar navbar-light bg-light">
    <div class="container-fluid mx-5 py-2">
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" width="125px" />
        </a>
        <a class="nav-link" href="{{ url('/') }}">
            <i class=""></i>
                <span class="menu-title">Dashboard</span>
        </a>
    </div>
</nav>
<h6 class="section-heading bg-body-secondary"></h6>
<nav class="navbar navbar-light bg-body-tertiary">
    <div class="container-fluid mx-5">
        <span class="navbar-brand mb-0 h1">{{ $slot }}</span>
    </div>
</nav>