@extends('layouts.app') @section('content')
    <h1 class="welcome-text" id="greeting">Selamat Pagi, <span class="fw-bold welcome-text">Halo Dunia</span></h1>
    <h3 class="welcome-sub-text">Ringkasan kinerja Anda minggu ini</h3>
    <script>
        const hour = new Date().getHours();
        let greetingText = '';
        if (hour < 12) {
            greetingText = 'Selamat Pagi';
        } else if (hour < 18) {
            greetingText = 'Selamat Siang';
        } else {
            greetingText = 'Selamat Malam';
        }
        document.getElementById('greeting').innerHTML = greetingText +
            ', <span class="fw-bold welcome-text">Halo Dunia</span>';
    </script> <!-- partial -->
    <div class="main-paanel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home-tab">
                        <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">
                                        <i class="fas fa-home"></i> Gambaran Umum
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">
                                        <i class="fas fa-users"></i> Audiens
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">
                                        <i class="fas fa-chart-bar"></i> Demografi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">
                                        <i class="fas fa-ellipsis-h"></i> Lainnya
                                    </a>
                                </li>
                            </ul>                            
                            <div>
                                <div class="btn-wrapper">
                                    <a href="#" class="btn btn-outline-dark align-items-center" id="shareBtn">
                                        <i class="icon-share"></i> Bagikan
                                    </a>
                                    <a href="#" class="btn btn-outline-dark" id="printBtn">
                                        <i class="icon-printer"></i> Cetak
                                    </a>
                                    <a href="#" class="btn btn-primary text-white me-0" id="exportBtn"
                                        style="text-decoration: line-through;">
                                        <i class="icon-download"></i> Ekspor</span>
                                    </a>
                                    <a href="#" class="btn btn-info text-white" style="text-decoration: line-through;"
                                        id="screenshotBtn">
                                        <i class="icon-camera"></i> Screenshot</span>
                                    </a>
                                </div>

                                <script>
                                    document.getElementById('screenshotBtn').addEventListener('click', function(event) {
                                        event.preventDefault();
                                        alert('📸 Fitur screenshot belum tersedia.');
                                    });

                                    document.getElementById('exportBtn').addEventListener('click', function(event) {
                                        event.preventDefault();
                                        alert('📤 Fitur ekspor belum tersedia.');
                                    });
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const shareBtn = document.getElementById("shareBtn");
                                        shareBtn.addEventListener("click", function(e) {
                                            e.preventDefault();
                                            if (navigator.share) {
                                                navigator.share({
                                                    title: document.title,
                                                    text: "Check this page out!",
                                                    url: window.location.href
                                                }).then(() => {
                                                    console.log("Page shared successfully");
                                                }).catch((err) => {
                                                    console.log("Error sharing page", err);
                                                });
                                            } else {
                                                alert("Web Share API is not supported in this browser.");
                                            }
                                        });

                                        const printBtn = document.getElementById("printBtn");
                                        printBtn.addEventListener("click", function() {
                                            const userName = prompt("Masukkan nama Anda:");
                                            const footerContent = userName ? `Nama: ${userName}` : "Nama tidak diberikan";

                                            const printWindow = window.open('', '', 'height=1130,width=850');
                                            printWindow.document.write('<html><head><title>Print Preview</title>');
                                            printWindow.document.write(
                                                '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">'
                                                );
                                            printWindow.document.write('<style>');
                                            printWindow.document.write('@page { size: A4; margin: 0; padding: 0; }');
                                            printWindow.document.write(
                                                'body { font-family: "Roboto", sans-serif; margin: 20px; padding: 20px; background-color: #f5f5f5; }'
                                                );
                                            printWindow.document.write(
                                                'h2 { text-align: center; color: #4CAF50; font-size: 28px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }'
                                                );
                                            printWindow.document.write(
                                                'table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
                                            printWindow.document.write(
                                                'th, td { padding: 12px; text-align: center; border: 1px solid #ddd; font-size: 14px; color: #333; }'
                                                );
                                            printWindow.document.write(
                                                'th { background-color: #4CAF50; color: white; font-weight: 500; }');
                                            printWindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
                                            printWindow.document.write(
                                                '.statistics-details { padding: 15px; font-size: 14px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); margin-top: 20px; }'
                                                );
                                            printWindow.document.write('</style>');
                                            printWindow.document.write('</head><body>');
                                            printWindow.document.write(printContent);
                                            printWindow.document.write('</body></html>');
                                            printWindow.document.close();
                                            printWindow.print();
                                        });
                                    });
                                </script>
                            </div>
                        </div> <br>
                        <div class="tab-content tab-content-basic">
                            @include('tab.overview')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection