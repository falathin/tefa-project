{{-- <script>
    function toggleTheme() {
        let currentTheme = localStorage.getItem('theme') || 'light';
        let newTheme = currentTheme === 'light' ? 'dark' : 'light';

        document.documentElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        let themeIcon = document.getElementById('themeIcon');
        themeIcon.classList.toggle('bi-sun', newTheme === 'dark');
        themeIcon.classList.toggle('bi-moon', newTheme === 'light');

        themeIcon.classList.add('rotate');
        setTimeout(() => themeIcon.classList.remove('rotate'), 500);
    }

    document.addEventListener('DOMContentLoaded', function () {
        let savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        let themeIcon = document.getElementById('themeIcon');
        if (savedTheme === 'dark') {
            themeIcon.classList.add('bi-sun');
            themeIcon.classList.remove('bi-moon');
        }
    });
</script>

<style>
    .rotate {
        animation: rotateIcon 0.5s ease-in-out;
    }

    @keyframes rotateIcon {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(180deg);
        }
    }

    [data-bs-theme="dark"] {
        --bg-color: #121212;
        --text-color: #ffffff;
        --card-bg-color: #1e1e1e;
    }

    [data-bs-theme="light"] {
        --bg-color: #ffffff;
        --text-color: #000000;
        --card-bg-color: #f8f9fa;
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-color);
    }

    .card {
        background-color: var(--card-bg-color);
    }
</style>

<!-- Theme Selector Dropdown -->
<li class="nav-item dropdown d-none d-lg-block">
    <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="themeDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">Pilih Tema</a>
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
</li> --}}

<!-- Theme Toggle Button -->
<li class="nav-item d-none d-lg-block">
    <button class="btn btn-link nav-link" id="themeSwitchButton" onclick="toggleTheme()">
        <i class="bi bi-moon" id="themeIcon"></i>
    </button>
</li>

<!-- Calendar Date Picker -->
<li class="nav-item d-none d-lg-block">
    <div id="datepicker-popup" 
        class="input-group date datepicker navbar-date-picker" 
        style="background-color: white; color: black; border: 1px solid #ccc;">
        <span class="input-group-addon input-group-prepend border-right" 
            style="background-color: transparent;">
            <span class="icon-calendar input-group-text calendar-icon" 
                style="color: black;"></span>
        </span>
        <input type="text" class="form-control" style="background-color: white; color: black;">
    </div>
</li>