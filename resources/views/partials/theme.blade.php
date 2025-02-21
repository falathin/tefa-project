<script>
    function toggleTheme() {
        let currentTheme = localStorage.getItem('theme') || 'light';
        let newTheme = currentTheme === 'light' ? 'dark' : 'light';

        document.documentElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        let themeIcon = document.getElementById('themeIcon');
        if (newTheme === 'dark') {
            themeIcon.classList.replace('bi-moon', 'bi-sun');
        } else {
            themeIcon.classList.replace('bi-sun', 'bi-moon');
        }

        themeIcon.classList.add('rotate');
        setTimeout(() => themeIcon.classList.remove('rotate'), 500);
    }

    document.addEventListener('DOMContentLoaded', function () {
        let savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        let themeIcon = document.getElementById('themeIcon');
        if (savedTheme === 'dark') {
            themeIcon.classList.replace('bi-moon', 'bi-sun');
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
        style="background-color: var(--bg-color); color: var(--text-color); border: 1px solid #ccc;">
        <span class="input-group-addon input-group-prepend border-right" 
            style="background-color: transparent;">
            <span class="icon-calendar input-group-text calendar-icon" 
                style="color: var(--text-color);"></span>
        </span>
        <input type="text" class="form-control" style="background-color: var(--bg-color); color: var(--text-color);">
    </div>
</li>