@extends('layouts.app')

@section('content')

<!-- Laporan Pelatihan Kerja -->

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
    
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    
        h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }
    
        h2 i {
            margin-right: 10px;
        }
    
        p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
        }
    
        strong {
            font-weight: bold;
        }
    
        .text-primary {
            color: #007bff;
        }
    
        .section-heading {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    
        .mb-4 {
            margin-bottom: 1.5rem;
        }
    
        .fas {
            font-size: 1.2rem;
        }
    </style>
    
    <!-- Pendahuluan -->
    <section class="container mb-4">
        <br><br>
        <h1 class="text-center mb-4">
            <i class="fas fa-file-alt text-primary fs-1"></i>
            <span class="d-block mt-3 font-weight-bold text-dark">Laporan Pelatihan Kerja</span>
            <span class="d-block text-muted">Dokumentasi Aplikasi Web</span>
        </h1>        <br><br><br>
        <h2 class="section-heading text-primary">
            <i class="fas fa-book-open"></i> Pendahuluan
        </h2>
        <p>
            Assalamualaikum warahmatullahi wabarakatuh, 
        </p>
        <p>
            Laporan ini merupakan dokumentasi dari aplikasi web yang dikembangkan selama pelatihan kerja di <strong>Teaching Factory Bengkel TKRO dan TBSM</strong> di <strong>PT Inovindo Digital Media</strong>, menggunakan framework <strong>Laravel</strong>. Aplikasi ini bertujuan untuk mengelola berbagai aspek yang berkaitan dengan kegiatan operasional di bengkel, khususnya dalam manajemen kendaraan, sparepart, serta transaksi pembayaran yang terjadi dalam proses perawatan dan servis kendaraan.
        </p>
        <p>
            Dalam lingkungan bengkel yang melayani berbagai jenis kendaraan, terutama dalam bidang Teknik Kendaraan Ringan Otomotif (TKRO) dan Teknik Bisnis Sepeda Motor (TBSM), pengelolaan data yang efisien dan cepat menjadi sangat penting. Oleh karena itu, aplikasi ini dirancang dengan tujuan untuk mempercepat dan mempermudah proses pencatatan, pengelolaan, dan pelaporan data yang berkaitan dengan kendaraan, sparepart, dan transaksi. Dengan begitu, proses bisnis dapat berjalan lebih lancar dan lebih terstruktur.
        </p>
        <p>
            Aplikasi ini memiliki beberapa fitur utama, di antaranya adalah pengelolaan data kendaraan, pengelolaan sparepart, serta pengelolaan transaksi layanan dan pembayaran yang terkait dengan pekerjaan yang dilakukan di bengkel. Semua data yang dimasukkan ke dalam sistem akan langsung tercatat dalam basis data yang terintegrasi, memungkinkan akses yang mudah dan akurat bagi para pengguna. Selain itu, sistem juga mendukung pembuatan laporan yang dapat membantu manajer bengkel dalam mengambil keputusan berbasis data.
        </p>
        <p>
            Salah satu fitur unggulan aplikasi ini adalah kemampuannya untuk mengotomatisasi alur kerja di bengkel. Misalnya, setiap kali terjadi transaksi atau pengadaan sparepart baru, sistem secara otomatis akan memperbarui stok, menghitung keuntungan, serta mengirimkan notifikasi kepada pihak terkait, seperti manajer bengkel atau kepala teknisi. Dengan adanya fitur ini, manajemen bengkel dapat lebih fokus pada pengambilan keputusan strategis tanpa harus terjebak dalam pekerjaan administratif yang memakan waktu.
        </p>
        <p>
            Aplikasi ini juga dirancang dengan antarmuka yang sederhana namun fungsional. Tampilan yang responsif dan user-friendly memungkinkan siapa saja, baik itu administrator, teknisi, maupun pelanggan, untuk menggunakannya tanpa kesulitan. Pengguna hanya perlu mengikuti alur yang sudah disusun dalam aplikasi untuk menyelesaikan tugas mereka dengan cepat dan efektif. Fitur-fitur ini dirancang dengan prinsip kemudahan akses dan efisiensi waktu sebagai fokus utama.
        </p>
        <p>
            Di sisi lain, aplikasi ini juga memperhatikan aspek keamanan. Semua data yang dimasukkan dan diproses dalam aplikasi dijaga kerahasiaannya dengan sistem keamanan yang handal, serta kontrol akses untuk memastikan bahwa hanya pihak yang berwenang yang dapat mengakses informasi sensitif. Dengan sistem ini, aplikasi tidak hanya mempermudah pekerjaan, tetapi juga menjaga kepercayaan dan integritas data pelanggan dan bengkel.
        </p>
        <p>
            Secara keseluruhan, tujuan utama dari aplikasi ini adalah untuk meningkatkan produktivitas, efisiensi, dan akurasi dalam pengelolaan data dan transaksi di bengkel. Kami berharap aplikasi ini dapat memberikan manfaat jangka panjang bagi operasional bengkel, membantu meningkatkan kualitas layanan kepada pelanggan, serta memudahkan pengelolaan dan pelaporan secara lebih transparan dan terorganisir.
        </p>
        <p>
            Kami juga berharap bahwa aplikasi ini dapat berkembang lebih lanjut, dengan fitur-fitur tambahan yang lebih inovatif, sesuai dengan kebutuhan pengguna dan perkembangan teknologi yang terus berubah. Oleh karena itu, masukan dan umpan balik dari semua pihak yang terlibat sangat dihargai untuk meningkatkan kualitas dan fungsionalitas aplikasi ini di masa mendatang.
        </p>
        <p>
            Semoga aplikasi ini dapat memberikan manfaat yang optimal, memperlancar alur kerja di bengkel, dan menjadi bagian dari kemajuan dunia industri yang semakin mengandalkan teknologi dalam operasional sehari-hari. 
        </p>
    </section>
    
    <!-- Tujuan Pelatihan -->
    <section class="container mb-4">
        <h2 class="section-heading text-primary">
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
    <section class="container mb-4">
        <h2 class="section-heading text-primary">
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
    

<div class="container mb-4">
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
            <li class="list-group-item">Clone repository <a style="text-decoration: none" class="text-danger" href="https://github.com/falathin/tefa-project.git"><code>git clone https://github.com/falathin/tefa-project.git</code></a> dari GitHub.</li>
            <li class="list-group-item">Jalankan perintah <code>composer install</code> untuk menginstal dependensi.</li>
            <li class="list-group-item">Atur file .env untuk konfigurasi database dan lainnya.</li>
            <li class="list-group-item">Jalankan perintah <code>php artisan migrate</code> untuk menjalankan migrasi database.</li>
            <li class="list-group-item">Jalankan perintah <code>php artisan storage:link</code> untuk mengkoneksikan gambar.</li>
            <li class="list-group-item">Jalankan aplikasi menggunakan perintah <code>php artisan serve</code>.</li>
        </ol>
        <p>
            Pastikan Anda telah menginstal Composer dan PHP di perangkat Anda. Jika Anda belum familiar dengan pengaturan Laravel, Anda bisa mengunjungi dokumentasi resmi Laravel untuk panduan lebih lengkap mengenai instalasi dan konfigurasi.
        </p>
    </section>
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-clipboard-list"></i> Instruksi Pengguna
        </h2>
        <p>
            Setelah aplikasi berhasil dijalankan, Anda dapat mengakses berbagai fitur yang tersedia, seperti manajemen pelanggan, kendaraan, sparepart, dan transaksi. Setiap bagian memiliki antarmuka yang sederhana dan mudah digunakan.
        </p>
        <p>
            Pengguna dapat login untuk mengakses panel admin atau menggunakan tampilan pelanggan untuk melakukan transaksi, memantau status kendaraan, atau bertanya mengenai produk melalui halaman kontak. Aplikasi ini memberikan kemudahan bagi pengguna dalam mengelola semua aspek yang berhubungan dengan kendaraan dan sparepart.
        </p>
    </section>
    
    <!-- Informasi Commit di GitHub -->
    <section class="mb-4">
        <h2 class="h3 text-primary">
            <i class="fas fa-code"></i> Riwayat Commit
        </h2>
        <p>Aplikasi ini telah mengalami perkembangan melalui beberapa commit yang tercatat di repository GitHub berikut:</p>
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Repository Proyek TeFa:</strong> <a href="https://github.com/piscokz/proyek_tefa" target="_blank">https://github.com/piscokz/proyek_tefa</a><br>
                <em>Total Commit: 72</em>
            </li>
            <li class="list-group-item">
                <strong>Repository TeFa Project:</strong> <a href="https://github.com/falathin/tefa-project" target="_blank">https://github.com/falathin/tefa-project</a><br>
                <em>Total Commit: 27</em>
            </li>
        </ul>
        <p>Silakan kunjungi repository untuk melihat lebih lanjut mengenai pengembangan aplikasi ini.</p>
    </section>
</div>

    <!-- Tim Pengembang -->
    <section class="mb-4">
        <div class="container py-5">
            <h2 class="h3 text-primary">
                <i class="fas fa-users"></i> Tim Pengembang
            </h2>
            <p>
                Aplikasi ini dikembangkan oleh tim pengembang yang terdiri dari:
            </p>
            <h2 class="text-center mb-5">Anggota I-Teenagers :</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Kolom untuk Ibrahim -->
                <div class="col d-flex justify-content-center">
                    <div class="card shadow-sm border-0 hover-effect d-flex flex-column">
                        <a href="https://falathin.github.io/My-Protofolio/" target="_blank" class="text-decoration-none text-dark">
                            <img src="https://falathin.github.io/My-Protofolio/ibrahim%201.jpg" class="card-img-top" alt="Ibrahim Ahmad Falathin" style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-person"></i>&nbsp; Ibrahim Ahmad Falathin</h5>
                                <p class="card-text">Front-End Developer, Web Designer</p>
                            </div>
                        </a>
                    </div>
                </div>
        
                <!-- Kolom untuk Fariyd (Leader) -->
                <div class="col d-flex justify-content-center">
                    <div class="card shadow-sm border-0 hover-effect d-flex flex-column">
                        <a href="https://piscokz.github.io/profil_fariyd/" target="_blank" class="text-decoration-none text-dark">
                            <img src="https://piscokz.github.io/profil_fariyd/foto.jpg" class="card-img-top" alt="Muhammad Fariyd" style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-person-circle"></i>&nbsp; Muhammad Fariyd (Ketua Tim)</h5>
                                <p class="card-text">Backend Developer, Architect of System</p>
                            </div>
                        </a>
                    </div>
                </div>
        
                <!-- Kolom untuk Yani -->
                <div class="col d-flex justify-content-center">
                    <div class="card shadow-sm border-0 hover-effect d-flex flex-column">
                        <a href="https://yani-ariyanti.github.io/CV-Yani-Ariyanti/" target="_blank" class="text-decoration-none text-dark">
                            <img src="{{asset('assets/images/yani.jpg')}}" class="card-img-top" alt="Yani Ariyani" style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-paint-bucket"></i>&nbsp; Yani Ariyanti</h5>
                                <p class="card-text">UI/UX Designer, Interface Specialist</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
                <!-- Kolom untuk anggota lainnya -->
                <div class="col">
                    <div class="card shadow-sm border-0 hover-effect d-flex flex-column">
                        <a href="https://rsrkodingz.github.io/RafieSenoRamadhan/" target="_blank" class="text-decoration-none text-dark">
                            <img src="{{asset('assets/images/seno.jpg')}}" class="card-img-top" alt="Rafie Seno Ramadhan" style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-clipboard-check"></i>&nbsp; Rafie Seno Ramadhan</h5>
                                <p class="card-text">Quality Control, Test Automation Expert</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0 hover-effect d-flex flex-column">
                        <a href="https://amelianurhasanah.github.io/MyPortofolio/" target="_blank" class="text-decoration-none text-dark">
                            <img src="https://amelianurhasanah.github.io/MyPortofolio/assets/images/img-01.jpg" class="card-img-top" alt="Ameliya Nurhasanah" style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-file-earmark-text"></i>&nbsp; Ameliya Nurhasanah</h5>
                                <p class="card-text">Documentation Specialist, Designer</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0 hover-effect d-flex flex-column">
                        <a href="https://annidaadin.github.io/My-Portofolio/" target="_blank" class="text-decoration-none text-dark">
                            <img src="https://annidaadin.github.io/My-Portofolio/img/1920x1080/Gambar%20WhatsApp%202024-11-20%20pukul%2011.01.31_f92b217f.jpg" class="card-img-top" alt="Annida" style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-person-video"></i>&nbsp; Annida</h5>
                                <p class="card-text">Public Speaking, Communication & Presentation Expert</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0 hover-effect d-flex flex-column">
                        <a href="https://karismaputripratama.github.io/MyPortofolio/" target="_blank" class="text-decoration-none text-dark">
                            <img src="https://karismaputripratama.github.io/MyPortofolio/assets/img/profile-img.jpg" class="card-img-top" alt="Kharisma Putri Pratama" style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-image"></i>&nbsp; Kharisma Putri Pratama</h5>
                                <p class="card-text">Designer and Editor, Creative Director</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>                                
    </section<br>

    <!-- Kesimpulan -->
    <section class=" container mb-4 mt-3">
        <h2 class="h3 text-primary">
            <i class="fas fa-clipboard-check"></i> Kesimpulan
        </h2>
        <p>
            Aplikasi web yang telah dikembangkan ini merupakan hasil kerja keras dan kolaborasi yang erat antara seluruh tim pengembang, mulai dari pengumpulan kebutuhan, perencanaan desain, hingga tahap implementasi dan pengujian. Setiap tahap pengembangan dilakukan dengan penuh perhatian dan kehati-hatian, dengan tujuan utama untuk menyediakan solusi yang efektif dan efisien bagi PT Inovindo Digital Media.
        </p>
        <p>
            Dalam pengembangan aplikasi ini, kami menerapkan metodologi pengembangan perangkat lunak yang terstruktur, mengikuti prinsip-prinsip Agile Development, yang memungkinkan kami untuk beradaptasi dengan cepat terhadap perubahan kebutuhan dan memperbaiki masalah yang muncul dengan responsif. Tim pengembang, yang terdiri dari berbagai keahlian, bekerja dengan semangat kolaboratif, memastikan bahwa setiap fitur dikembangkan dengan baik, serta diuji dengan cermat.
        </p>
        <p>
            Aplikasi ini tidak hanya bertujuan untuk memenuhi kebutuhan fungsional saat ini, tetapi juga dirancang dengan mempertimbangkan skalabilitas dan keberlanjutan di masa depan. Setiap aspek dari aplikasi ini—dari manajemen pelanggan, transaksi, hingga analisis data—diharapkan dapat membantu PT Inovindo Digital Media dalam meningkatkan efisiensi operasional dan memberikan layanan yang lebih baik kepada pelanggan.
        </p>
        <p>
            Meskipun aplikasi ini telah melewati berbagai tahap pengembangan yang ketat, kami menyadari bahwa kesempurnaan hanyalah milik Tuhan. Beberapa tantangan dan bug mungkin masih ada, dan kami berkomitmen untuk terus melakukan pemeliharaan, pembaruan, dan perbaikan demi memberikan pengalaman terbaik bagi penggunanya. Setiap umpan balik dan kritik yang konstruktif akan diterima dengan baik, dan kami akan terus meningkatkan kualitas aplikasi ini.
        </p>
        <p>
            Kami juga berharap, dengan adanya dokumentasi ini, pengembang yang akan datang dapat dengan mudah memahami struktur dan alur kerja aplikasi, serta dapat melanjutkan pengembangan lebih lanjut dengan lebih efisien. Kami berharap aplikasi ini dapat terus berkembang, memberikan manfaat yang lebih besar, dan berkontribusi pada kemajuan PT Inovindo Digital Media di masa yang akan datang.
        </p>
        <p>
            Terima kasih atas dukungan, pengertian, dan kerja keras semua pihak yang telah berkontribusi dalam pengembangan aplikasi ini. Semoga aplikasi ini dapat bermanfaat dan memberikan solusi terbaik bagi perusahaan dan para penggunanya.
        </p>
        <p>
            Wassalamualaikum Wr. Wb.
        </p>
        <!-- WhatsApp Button for Bugs -->
        <div class="mt-4 mb-4 ml-3">
            <a href="https://wa.me/6285719443650" class="btn btn-success btn-md" target="_blank">
                <i class="fab fa-whatsapp"></i>&nbsp; Chat Laporkan (Jika ada Bug)
            </a>
        </div>
    </section>



@endsection