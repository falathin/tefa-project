@extends('layouts.app')

@section('content')

<!-- Laporan Pelatihan Kerja -->
<div class="container mt-5">
    <h1 class="text-center mb-4">
        <i class="fas fa-file-alt"></i> Laporan Pelatihan Kerja: Dokumentasi Aplikasi Web
    </h1>

    <!-- Pendahuluan -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-book-open"></i> Pendahuluan
        </h2>
        <p>
            Laporan ini merupakan dokumentasi dari aplikasi web yang dikembangkan selama pelatihan kerja di <strong>PT Inovindo Digital Media</strong> menggunakan framework <strong>Laravel</strong>. Aplikasi ini bertujuan untuk mengelola data dan transaksi di dalam perusahaan, dengan fokus pada manajemen kendaraan, sparepart, dan transaksi pembayaran.
        </p>
        <p>
            Aplikasi ini dirancang untuk menyederhanakan proses bisnis yang ada, mengotomatiskan alur kerja, serta memberikan kemudahan bagi karyawan dalam mengelola dan memonitor transaksi harian. Selain itu, aplikasi ini juga memiliki tampilan yang responsif dan user-friendly, sehingga dapat digunakan dengan mudah oleh berbagai macam pengguna, baik itu administrator maupun pelanggan.
        </p>
    </section>

    <!-- Tujuan Pelatihan -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-bullseye"></i> Tujuan Pelatihan
        </h2>
        <p>
            Tujuan utama dari pelatihan ini adalah untuk memberikan pemahaman yang mendalam mengenai pengembangan aplikasi web menggunakan framework Laravel serta memperkenalkan praktik terbaik dalam pengelolaan proyek perangkat lunak. Pelatihan ini juga bertujuan untuk meningkatkan keterampilan teknis peserta dalam pengembangan aplikasi web, mulai dari perencanaan hingga implementasi.
        </p>
        <p>
            Dalam pelatihan ini, peserta juga dibekali dengan pengetahuan mengenai metodologi pengembangan perangkat lunak, serta keterampilan dalam bekerja dalam tim untuk menghasilkan produk perangkat lunak yang berkualitas.
        </p>
    </section>

    <!-- Proses Pengembangan Aplikasi -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-cogs"></i> Proses Pengembangan Aplikasi
        </h2>
        <p>
            Aplikasi ini dikembangkan melalui beberapa tahap penting yang meliputi perencanaan, desain, pengembangan, pengujian, dan implementasi. Setiap tahap dijalani dengan kolaborasi antara tim pengembang dan pengujian yang intensif, dengan pemahaman yang jelas mengenai tujuan aplikasi dan kebutuhan pengguna.
        </p>

        <p>
            <strong>Fase 1: Pembuatan Diagram dan Perencanaan Desain</strong><br>
            Proses dimulai dengan analisis kebutuhan sistem dan pembuatan diagram alur kerja serta diagram entitas-relasi (ERD) untuk memvisualisasikan struktur data aplikasi. Langkah ini sangat penting untuk memastikan bahwa sistem yang akan dibangun memiliki fondasi yang kokoh. Setelah itu, desain antarmuka pengguna (UI/UX) dimulai, dengan fokus pada pengalaman pengguna yang mudah diakses dan responsif di berbagai perangkat. Desain mockup dan wireframe dibuat menggunakan alat seperti Figma, yang kemudian dikaji ulang dengan tim UI/UX dan stakeholder untuk mendapatkan umpan balik awal.
        </p>

        <p>
            <strong>Fase 2: Pengembangan Fitur Utama dan Backend</strong><br>
            Setelah fase desain, pengembangan aplikasi dimulai dengan membangun backend menggunakan Laravel. Pada fase ini, pengembang menyiapkan struktur database, pengelolaan autentikasi pengguna, serta implementasi logika bisnis untuk manajemen pelanggan, kendaraan, sparepart, dan transaksi. Pengembangan frontend dilakukan secara bersamaan, dengan fokus pada implementasi antarmuka yang sudah disepakati pada tahap desain. Laravel menjadi pilihan untuk mempermudah pengelolaan rute dan middleware aplikasi, serta memastikan sistem backend yang aman dan efisien.
        </p>

        <p>
            <strong>Fase 3: Pengujian, Debugging, dan Penyempurnaan UI/UX</strong><br>
            Setelah fitur utama selesai, tim pengujian melakukan pengujian menyeluruh untuk memastikan bahwa setiap bagian dari aplikasi berfungsi sesuai dengan spesifikasi. Pengujian ini meliputi pengujian unit, pengujian fungsional, dan pengujian UI untuk memastikan bahwa antarmuka pengguna tidak hanya fungsional, tetapi juga mudah digunakan. Setiap bug atau masalah yang ditemukan selama pengujian akan diperbaiki sebelum melanjutkan ke fase berikutnya. Pada fase ini, perbaikan desain UI/UX juga dilakukan berdasarkan umpan balik dari pengujian pengguna untuk meningkatkan pengalaman pengguna secara keseluruhan.
        </p>

        <p>
            <strong>Fase 4: Implementasi dan Optimasi</strong><br>
            Setelah aplikasi siap untuk diluncurkan, fitur tambahan seperti grafik analisis data dan halaman kontak pelanggan ditambahkan. Pengujian lebih lanjut dilakukan untuk memastikan semua fitur berjalan lancar. Setelah itu, optimasi dilakukan untuk memastikan aplikasi dapat berjalan dengan baik di berbagai perangkat dan platform, termasuk melakukan pengujian kecepatan dan pengoptimalan kinerja aplikasi. Aplikasi kemudian dipublikasikan dan siap untuk digunakan oleh pelanggan.
        </p>

        <p>
            Setiap fase di atas dipandu oleh prinsip-prinsip <strong>Agile Development</strong>, di mana umpan balik dari pengguna dan stakeholder diterima dengan cepat untuk meningkatkan kualitas aplikasi. Iterasi dan pembaruan dilakukan secara berkelanjutan untuk memastikan aplikasi memenuhi kebutuhan pengguna dan dapat berfungsi dengan baik pada berbagai perangkat.
        </p>
    </section>

    <hr>

    <!-- Fitur Utama -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-list-alt"></i> Fitur Utama
        </h2>
        <ul class="list-group">
            <li class="list-group-item"><i class="fas fa-users"></i> Manajemen pelanggan (CRUD untuk pelanggan).</li>
            <li class="list-group-item"><i class="fas fa-car"></i> Manajemen Kendaraan dan Sparepart.</li>
            <li class="list-group-item"><i class="fas fa-credit-card"></i> Transaksi dan Pembayaran.</li>
            <li class="list-group-item"><i class="fas fa-chart-line"></i> Grafik untuk analisis data.</li>
            <li class="list-group-item"><i class="fas fa-envelope"></i> Halaman kontak untuk pertanyaan pelanggan.</li>
        </ul>
        <p>
            Setiap fitur aplikasi ini dikembangkan dengan memprioritaskan kemudahan penggunaan dan kestabilan sistem. Sistem manajemen pelanggan memungkinkan admin untuk memantau dan mengelola data pelanggan dengan mudah, sementara fitur transaksi dan pembayaran memungkinkan pelanggan melakukan transaksi secara aman dan efisien.
        </p>
    </section>

    <!-- Teknologi yang Digunakan -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-laptop-code"></i> Teknologi yang Digunakan
        </h2>
        <p>Aplikasi ini dibangun menggunakan teknologi berikut:</p>
        <ul class="list-group">
            <li class="list-group-item"><strong>Laravel:</strong> Framework PHP untuk back-end development.</li>
            <li class="list-group-item"><strong>MySQL:</strong> Database untuk menyimpan data aplikasi.</li>
            <li class="list-group-item"><strong>Bootstrap:</strong> Framework CSS untuk desain responsif dan modern.</li>
            <li class="list-group-item"><strong>JavaScript:</strong> Digunakan untuk interaksi dan manipulasi DOM.</li>
            <li class="list-group-item"><strong>Git:</strong> Sistem version control untuk mengelola pengembangan aplikasi secara kolaboratif.</li>
        </ul>
        <p>
            Penggunaan Laravel mempermudah pengembangan aplikasi ini, terutama dalam hal pengelolaan rute, middleware, serta manajemen autentikasi dan otorisasi pengguna. Dengan menggunakan MySQL sebagai database, aplikasi dapat menangani penyimpanan dan pengambilan data dengan cepat dan efisien.
        </p>
    </section>

    <!-- Instruksi Instalasi -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-download"></i> Instruksi Instalasi
        </h2>
        <p>Untuk menginstal aplikasi ini secara lokal, Anda dapat mengikuti langkah-langkah berikut:</p>
        <ol class="list-group list-group-numbered">
            <li class="list-group-item">Clone repository dari GitHub.</li>
            <li class="list-group-item">Jalankan perintah <code>composer install</code> untuk menginstal dependensi.</li>
            <li class="list-group-item">Atur file .env untuk konfigurasi database dan lainnya.</li>
            <li class="list-group-item">Jalankan perintah <code>php artisan migrate</code> untuk menjalankan migrasi database.</li>
            <li class="list-group-item">Jalankan aplikasi menggunakan perintah <code>php artisan serve</code>.</li>
        </ol>
        <p>
            Pastikan Anda telah menginstal Composer dan PHP di perangkat Anda. Jika Anda belum familiar dengan pengaturan Laravel, Anda bisa mengunjungi dokumentasi resmi Laravel untuk panduan lebih lengkap mengenai instalasi dan konfigurasi.
        </p>
    </section>

    <!-- Instruksi pelangganan -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-clipboard-list"></i> Instruksi Pelangganan
        </h2>
        <p>
            Setelah aplikasi berhasil dijalankan, Anda dapat mengakses berbagai fitur yang tersedia, seperti manajemen pelanggan, kendaraan, sparepart, dan transaksi. Setiap bagian memiliki antarmuka yang sederhana dan mudah digunakan.
        </p>
        <p>
            Pengguna dapat login untuk mengakses panel admin atau menggunakan tampilan pelanggan untuk melakukan transaksi, memantau status kendaraan, atau bertanya mengenai produk melalui halaman kontak. Aplikasi ini memberikan kemudahan bagi pengguna dalam mengelola semua aspek yang berhubungan dengan kendaraan dan sparepart.
        </p>
    </section>

    <!-- Tim Pengembang -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-users"></i> Tim Pengembang
        </h2>
        <p>
            Aplikasi ini dikembangkan oleh tim pengembang yang terdiri dari:
        </p>
        <ul class="list-group">
            <li class="list-group-item"><strong>Ibrahim Ahmad Falathin:</strong> Front-End Developer</li>
            <li class="list-group-item"><strong>Wawan Dwi Idhyana:</strong> Backend Developer</li>
            <li class="list-group-item"><strong>Yani Ariyani:</strong> UI/UX Designer</li>
            <li class="list-group-item"><strong>Rafie Seno Ramadhan:</strong> Quality Control</li>
            <li class="list-group-item"><strong>Ameliya Nurhasanah:</strong> Documentation and Designer</li>
            <li class="list-group-item"><strong>Annida:</strong> Public Speaking</li>
            <li class="list-group-item"><strong>Kharisma Putri Pratama:</strong> Designer and Editor</li>
        </ul>
    </section>

        <!-- Kesimpulan -->
        <section class="mb-4">
            <h2 class="h3 text-primary">
                <i class="fas fa-clipboard-check"></i> Kesimpulan
            </h2>
            <p>
                Aplikasi web yang dikembangkan ini merupakan hasil dari kerja keras dan kolaborasi antara seluruh tim pengembang. Proses pengembangan yang terstruktur dan metodologi yang diterapkan memastikan aplikasi berjalan dengan baik sesuai tujuan. Kami berharap aplikasi ini dapat memberikan manfaat bagi PT Inovindo Digital Media dan membantu meningkatkan efisiensi operasional perusahaan.
            </p>
            <p>
                Meskipun aplikasi ini telah melalui berbagai tahap pengembangan, kami menyadari bahwa kesempurnaan hanya milik Tuhan. Saat ini, masih banyak ruang untuk perbaikan dan penyempurnaan. Beberapa bug dan tantangan mungkin masih ada, namun kami akan terus berupaya untuk memperbaikinya. Terima kasih atas dukungan dan pengertian semua pihak yang telah berkontribusi dalam pengembangan aplikasi ini.
            </p>
            <p>
                Dengan adanya dokumentasi ini, diharapkan pengembang yang akan datang dapat dengan mudah memahami dan mengembangkan lebih lanjut aplikasi ini. Wassalamualaikum.
            </p>
        </section>

        <!-- WhatsApp Button for Bugs -->
        <div class="mt-4 mb-4 ml-3">
            <a href="https://wa.me/6285719443650" class="btn btn-success btn-md" target="_blank">
                <i class="fab fa-whatsapp"></i>&nbsp; Chat Laporkan (Jika ada Bug)
            </a>
        </div>
</div>

@endsection