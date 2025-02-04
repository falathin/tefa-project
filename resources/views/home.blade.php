@extends('layouts.app') @section('content')
@if (session('statusBerhasil'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('statusBerhasil') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
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
            ', <span class="fw-bold welcome-text">{{ Auth::user()->name }}</span>';
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
                                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-controls="demographics" aria-selected="false">
                                        <i class="fas fa-chart-line"></i> Laporan Harian Servis
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
                                    
                                    <script>
                                        document.getElementById("printBtn").addEventListener("click", function() {
                                            const userName = prompt("Masukkan nama Anda:");
                                            const footerContent = userName ? `Nama: ${userName}` : "Nama tidak diberikan";
                                            
                                            const dailyIncome = "Rp. {{ number_format($serviceIncomeDaily, 2, ',', '.') }}";
                                            const monthlyIncome = "Rp. {{ number_format($serviceIncomeMonthly, 2, ',', '.') }}";
                                            const totalUnpaid = "Rp. {{ number_format($totalUnpaid, 2, ',', '.') }}";
                                            const yearlyIncome = "Rp. {{ number_format($serviceIncomeYearly, 2, ',', '.') }}";
                                            
                                            const printContent = `
                                                <h2 style="text-align:center; color:#007bff; font-size:24px; font-weight:700; margin:0;">Detail Cetak</h2>
                                                <img src="{{ asset('assets/images/logo-mini.svg')}}" alt="Logo" style="display:block; margin:0 auto; width:150px; height:auto; margin-top:10px;">
                                                <div style="margin: 20px 0; padding: 10px; text-align:center; font-size: 16px; border-bottom: 2px dashed #007bff;">
                                                    <strong>Nama Pengguna</strong>: ${userName}
                                                </div>
                                                <table style="width:100%; border: none; margin-top:10px; font-size:14px;">
                                                    <tr style="border-bottom: 1px solid #ddd;">
                                                        <td style="padding:10px; text-align:left;">Nama Pengguna</td>
                                                        <td style="padding:10px; text-align:right;">${userName}</td>
                                                    </tr>
                                                    <tr style="border-bottom: 1px solid #ddd;">
                                                        <td style="padding:10px; text-align:left;">Footer</td>
                                                        <td style="padding:10px; text-align:right;">${footerContent}</td>
                                                    </tr>
                                                    <tr style="border-bottom: 1px solid #ddd;">
                                                        <td style="padding:10px; text-align:left;">Pemasukan Hari Ini</td>
                                                        <td style="padding:10px; text-align:right;">${dailyIncome}</td>
                                                    </tr>
                                                    <tr style="border-bottom: 1px solid #ddd;">
                                                        <td style="padding:10px; text-align:left;">Pemasukkan Bulanan</td>
                                                        <td style="padding:10px; text-align:right;">${monthlyIncome}</td>
                                                    </tr>
                                                    <tr style="border-bottom: 1px solid #ddd;">
                                                        <td style="padding:10px; text-align:left;">Total Belum Dibayar</td>
                                                        <td style="padding:10px; text-align:right;">${totalUnpaid}</td>
                                                    </tr>
                                                    <tr style="border-bottom: 1px solid #ddd;">
                                                        <td style="padding:10px; text-align:left;">Pemasukkan Tahunan</td>
                                                        <td style="padding:10px; text-align:right;">${yearlyIncome}</td>
                                                    </tr>
                                                </table>
                                                <div style="margin-top: 20px; padding: 10px; font-size: 14px; text-align:center; border-top: 2px dashed #007bff;">
                                                    Terima Kasih atas kunjungan Anda!
                                                </div>
                                            `;
                                    
                                            const printWindow = window.open('', '', 'height=1130,width=850');
                                            printWindow.document.write(`
                                                <html><head><title>Print Preview</title>
                                                <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
                                                <style>
                                                    body { font-family: "Roboto", sans-serif; margin:0; padding: 0; width: 210mm; height: 297mm; background-color:#f5f5f5; }
                                                    h2 { color:#007bff; font-size:24px; font-weight:700; margin: 0; text-align:center; }
                                                    img { display:block; margin:0 auto; width:150px; height:auto; margin-top:10px; }
                                                    table { width:100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
                                                    td { padding:10px; text-align:left; }
                                                    th, td { border-bottom: 1px solid #ddd; }
                                                    td:last-child { text-align:right; }
                                                    .footer { margin-top: 20px; padding: 10px; font-size: 14px; text-align:center; }
                                                    .footer strong { font-weight:700; }
                                                    @media print {
                                                        body { width: 100%; height: auto; }
                                                        h2 { font-size: 20px; }
                                                        .footer { font-size: 12px; }
                                                        table { width: 100%; }
                                                    }
                                                </style></head>
                                                <body>${printContent}</body></html>
                                            `);
                                            printWindow.document.close();
                                            printWindow.print();
                                        });
                                    </script>
                                </div>

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