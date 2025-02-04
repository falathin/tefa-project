<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial Memulihkan Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
        /* Additional Custom Styles */
        .section {
            padding: 60px 0;
        }
        .section h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand brand-logo" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" width="125px"/>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lupa.password') }}">Kembali</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Hero Section -->
        <div class="text-center mt-5">
            <h1>Tutorial Memulihkan Password</h1>
            <p class="lead">Ikuti langkah-langkah di bawah ini untuk memulihkan password Anda menggunakan Emergency Password.</p>
            
        </div>

        <!-- Emergency Password Section -->
        <div id="emergency-password-section" class="section">
            
            <ol class="fs-6">
                <li>
                    <strong>Masuk ke halaman lupa password </strong><br>
                    Pada bagian bawah tombol Reset Password, klik <a href="https://wa.me/6285719443650" class="text-decoration-none text-success fw-bold">Whatsapp</a> untuk menghubungi tim iteens
                </li>
                <li>
                    <strong>Hubungi tim iteens</strong><br>
                    Kirim pesan WhatsApp ke nomor tim iteens kami melalui <strong>nomor Anda</strong> yang terdaftar di akun Anda dengan format berikut ( copy jika perlu ) : <br><br>
                    <pre>
Permintaan Emergency Password
Nama: [Nama Lengkap Anda]
Email Terdaftar: [Email yang Anda gunakan untuk mendaftar]
</pre>
                </li>
                <li>
                    <strong>Tunggu Balasan</strong><br>
                    Tim tim iteens akan memverifikasi data Anda dan mengirimkan <strong>Emergency Password</strong>
                    ke
                    email
                    atau WhatsApp Anda.
                </li>
                <li>
                    <strong>Gunakan Emergency Password</strong><br>
                    - Masuk ke halaman login.<br>
                    - Klik opsi <strong>Lupa Password</strong>.<br>
                    - Masukkan <strong>Emergency Password</strong>.<br>
                    - Ikuti instruksi selanjutnya untuk membuat password baru.
                </li>
                <li>
                    <strong>Login dengan Password Baru</strong><br>
                    Setelah berhasil membuat password baru, Anda bisa login ke akun Anda seperti biasa.
                </li>
            </ol>
            <div class="alert alert-info">
                <strong>Catatan:</strong><br>
                - Pastikan data yang Anda berikan email sesuai dengan data terdaftar.<br>
                - Jika Anda mengalami kendala, jangan ragu untuk menghubungi pihak Iteens kembali melalui <a href="https://wa.me/6285719443650" class="text-decoration-none text-success fw-bold">Whatsapp</a>.
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2023 MyWebsite. All rights reserved.</p>
    </footer>

    <!-- Bootstrap 5 JS (Optional, for certain components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>