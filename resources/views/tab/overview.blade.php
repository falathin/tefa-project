<div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
    <div class="col-sm-12">
        <!-- Internal CSS -->
        <style>
            .tooltip-inner {
                font-size: 0.9rem;
                background-color: #000;
                color: #fff;
                padding: 10px;
                border-radius: 5px;
            }

            .tooltip-arrow {
                border-color: #000;
            }
        </style>

        <!-- Internal JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inisialisasi semua elemen dengan tooltip
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>

        <!-- Internal HTML -->
        <div class="row">
            <!-- Total Daily Profit -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="statistics-details d-flex align-items-center justify-content-between">
                    <div>
                        <p class="statistics-title" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Pemasukan Hari Ini dihitung berdasarkan transaksi servis yang selesai pada hari ini.">
                            Pemasukan Hari Ini
                        </p>
                        <h3
                            class="rate-percentage @if ($serviceIncomeDaily < 0) text-danger @else text-success @endif">
                            Rp. {{ number_format($serviceIncomeDaily, 2, ',', '.') }}
                        </h3>
                        <p class="text-success d-flex">
                            <i class="mdi mdi-menu-up"></i>
                            <span>{{ $profitPercentage > 0 ? '+' : '' }}{{ number_format($profitPercentage, 2, ',', '.') }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Monthly Income -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="statistics-details d-flex align-items-center justify-content-between">
                    <div>
                        <p class="statistics-title" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Pemasukan Bulanan dihitung berdasarkan semua pemasukan dari transaksi servis selama bulan ini.">
                            Pemasukkan Bulanan
                        </p>
                        <h3
                            class="rate-percentage @if ($serviceIncomeMonthly < 0) text-danger @else text-success @endif">
                            Rp. {{ number_format($serviceIncomeMonthly, 2, ',', '.') }}
                        </h3>
                        <p class="text-danger d-flex">
                            <i class="mdi mdi-menu-down"></i>
                            <span>{{ $expensePercentage > 0 ? '-' : '' }}{{ number_format($expensePercentage, 2, ',', '.') }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Unpaid -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="statistics-details d-flex align-items-center justify-content-between">
                    <div>
                        <p class="statistics-title" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Total Belum Dibayar adalah jumlah tagihan yang belum dilunasi pelanggan.">
                            Total Belum Dibayar
                        </p>
                        <h3
                            class="rate-percentage text-danger @if ($totalUnpaid < 0) text-danger @else text-success @endif">
                            Rp. {{ number_format($totalUnpaid, 2, ',', '.') }}
                        </h3>
                        <p class="text-danger d-flex">
                            <i class="mdi mdi-menu-down"></i>
                            <span>{{ $unpaidPercentage > 0 ? '-' : '' }}{{ number_format($unpaidPercentage, 2, ',', '.') }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Yearly Income -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="statistics-details d-flex align-items-center justify-content-between">
                    <div>
                        <p class="statistics-title" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Pemasukan Tahunan dihitung dari total semua transaksi servis sepanjang tahun ini.">
                            Pemasukkan Tahunan
                        </p>
                        <h3
                            class="rate-percentage @if ($serviceIncomeYearly < 0) text-danger @else text-success @endif">
                            Rp. {{ number_format($serviceIncomeYearly, 2, ',', '.') }}
                        </h3>
                        <p class="text-success d-flex">
                            <i class="mdi mdi-menu-up"></i>
                            <span>
                                Rp. {{ number_format($serviceIncomeYearly, 2, ',', '.') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <hr style="border: 1px solid rgb(171, 171, 171);"> <br>
    <div class="row">
        <div class="col-lg-6 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card card-rounded hover-effect"
                        style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-radius: 10px;">
                        <div class="card-body">
                            <div class="d-sm-flex justify-content-between align-items-center">
                                <div>
                                    <h4 id="grafikKinerja" class="card-title card-title-dash text-dark">Grafik
                                        Kinerja</h4>
                                    <h5 id="grafikKinerjaSub" class="card-subtitle card-subtitle-dash">Grafik
                                        keuntungan harian berdasarkan servis</h5>
                                </div> <!-- Language Selector with Icons -->
                                <div class="mb-4"> <select id="languageSelect" class="form-select form-select-sm"
                                        aria-label="Language Select"
                                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 5px;">
                                        <option value="id" selected>Bahasa Indonesia
                                        </option>
                                        <option value="jp">日本語 (Japanese)</option>
                                        <option value="en">English</option>
                                        <option value="su">Sunda</option>
                                    </select> </div>
                            </div>
                            <script>
                                const languageSelect = document.getElementById('languageSelect');
                                const grafikKinerja = document.getElementById('grafikKinerja');
                                const grafikKinerjaSub = document.getElementById('grafikKinerjaSub');
                                languageSelect.addEventListener('change', function() {
                                    const selectedLanguage = languageSelect.value;
                                    switch (selectedLanguage) {
                                        case 'id':
                                            grafikKinerja.textContent = 'Grafik Kinerja';
                                            grafikKinerjaSub.textContent = 'Grafik keuntungan harian berdasarkan servis';
                                            break;
                                        case 'jp':
                                            grafikKinerja.textContent = 'パフォーマンス グラフ';
                                            grafikKinerjaSub.textContent = 'サービスに基づく日別利益グラフ';
                                            break;
                                        case 'en':
                                            grafikKinerja.textContent = 'Performance Graph';
                                            grafikKinerjaSub.textContent = 'Daily profit graph based on services';
                                            break;
                                        case 'su':
                                            grafikKinerja.textContent = 'Grafik Kinerja';
                                            grafikKinerjaSub.textContent = 'Grafik kauntungan sapopoé dumasar kana layanan';
                                            break;
                                    }
                                });
                            </script> <!-- Chart -->
                            <div class="chartjs-wrapper mt-4"> <canvas id="performanceLine" width="400"
                                    height="200"></canvas> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>

            const chartLabels = @json($chartLabels);
            const chartValues = @json($chartValues);
            const monthlyChartValues = @json($monthlyChartValues);
        </script>
        <div class="col-lg-6 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                    <div class="card card-rounded hover-effect" id="vehicle-card"
                        style="background-image: url('https://www.madornomad.com/wp-content/uploads/2019/09/japan-riding-17.jpg'); background-size: cover; background-position: center; color: white; border-radius: 20px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative;"
                        onclick="changeImageRandomly()">
                        <div class="card-body" style="padding: 20px; position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-center bike-card">
                                <div>
                                    <p class="text-small mb-2"
                                        style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        Total Kendaraan / 車両合計 </p>
                                    <h4 class="mb-0 fw-bold"
                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $totalVehicles }} / {{ $totalVehicles }}台 </h4>
                                </div> <i class="fas fa-car-side fa-2x" style="color: white;"></i>
                            </div>
                        </div> <svg class="sakura-hover" xmlns="http://www.w3.org/2000/svg" width="100"
                            height="100" viewBox="0 0 100 100"
                            style="position: absolute; top: 10%; left: 10%; opacity: 0; transition: opacity 0.3s ease;">
                            <circle cx="20" cy="20" r="6" fill="pink" />
                            <circle cx="40" cy="30" r="6" fill="pink" />
                            <circle cx="60" cy="20" r="6" fill="pink" />
                            <circle cx="80" cy="40" r="6" fill="pink" />
                        </svg>
                    </div>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const defaultImage = 'https://www.madornomad.com/wp-content/uploads/2019/09/japan-riding-17.jpg';
                        const vehicleImages = [
                            defaultImage,
                            @foreach ($vehicles as $vehicle)
                                '{{ $vehicle->image ? asset('storage/' . $vehicle->image) : '' }}',
                            @endforeach
                        ];

                        const validVehicleImages = vehicleImages.filter(image => image !== '');
                        const imagesToCycle = validVehicleImages.length > 0 ? validVehicleImages : [defaultImage];
                        const cardElement = document.getElementById('vehicle-card');
                        let currentIndex = 0;

                        function changeImageRandomly() {
                            currentIndex = (currentIndex + 1) % imagesToCycle.length;
                            cardElement.style.backgroundImage = `url('${imagesToCycle[currentIndex]}')`;
                        }

                        setInterval(changeImageRandomly, 4000);

                        cardElement.addEventListener('click', function() {
                            changeImageRandomly();
                        });
                    });
                </script>
                
                <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                    <div class="card card-rounded hover-effect"
                        style="background-image: url('https://st3.depositphotos.com/4080643/17799/i/450/depositphotos_177995342-stock-photo-fuji-mountain-and-cherry-blossoms.jpg'); background-size: cover; background-position: center; color: white; border-radius: 20px; overflow: hidden; transition: transform 0.5s ease, box-shadow 0.5s ease, filter 0.3s ease; height: 160px; width: 100%;"
                        id="visitor-card">
                        <div class="card-body" style="padding: 10px 15px; position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-small mb-2"
                                        style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        Total Pengunjung / 訪問者数 </p>
                                    <h4 class="mb-0 fw-bold"
                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $totalVisitors }} / {{ $totalVisitors }}人 </h4>
                                </div>
                                <div> <i
                                        class="fas fa-users fa-2x group-hover:scale-125 group-hover:text-yellow-400 transition-all duration-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const backgroundImages = ['https://wallpapercave.com/wp/wp5753394.jpg',
                            'https://wallpapercave.com/wp/wp5753409.jpg',
                            'https://cdn.wallpapersafari.com/15/15/djZk0c.jpg',
                            'https://wallpapers.com/images/hd/night-cherry-blossom-pc4o7liqwpqdm894.jpg',
                            'https://farm4.staticflickr.com/3406/3537584665_d2d1858a0a_b.jpg',
                            'https://www.teahub.io/photos/full/62-623582_cherry-blossom-wallpaper-japanese-hd-cherry-blossom-wallpaper.jpg',
                            'https://i.pinimg.com/originals/14/84/5a/14845a1a6c528de17aca14878e965c84.gif',
                            'https://media.giphy.com/media/Xl0oVz3eb9mfu/giphy.gif',
                            'https://i.pinimg.com/originals/94/fa/4b/94fa4b126901a1a2b79951f0a62d6a6c.gif',
                        ];
                        const cardElement = document.getElementById('visitor-card');

                        function changeImageRandomly() {
                            const randomIndex = Math.floor(Math.random() * backgroundImages.length);
                            cardElement.style.backgroundImage = `url('${backgroundImages[randomIndex]}')`;
                        }
                        setInterval(changeImageRandomly, 4000);
                        cardElement.addEventListener('click', changeImageRandomly);
                    });
                </script>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card card-rounded table-darkBGImg">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title card-title-dash text-white">Statistik Kinerja</h4>
                            <p class="card-subtitle card-subtitle-dash text-white">
                                Pantau perkembangan pengunjung, sparepart, dan kendaraan untuk memastikan pertumbuhan
                                dan efisiensi yang optimal.
                            </p>
                        </div>
                        <!-- Tombol dengan ikon -->
                        {{-- <button class="btn btn-warning text-white" onclick="printDailyReport()">
                            <i class="fa fa-print"></i> Print Laporan Umum
                        </button> --}}

                        <a href="{{ route('transactions.export') }}" class="btn btn-success text-light">
                            <i class="bi bi-file-earmark-excel"></i> Export Transaksi Sparepart
                        </a>
                    </div>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered table-hover text-white"
                            style="background-color: transparent; border-radius: 15px; overflow: hidden;">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pengunjung Hari Ini</th>
                                    <th>Jumlah Jenis Sparepart</th>
                                    <th>Pertumbuhan</th>
                                    <th colspan="3" class="text-center">Rata-rata Jumlah Kendaraan per Pelanggan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{ $today }}</td>
                                    <td class="text-center">{{ $totalVisitorsToday }}</td>
                                    <td class="text-center">{{ $totalSpareparts }}</td>
                                    <td class="text-center">{{ number_format($data['total_profit'], 2) }}%</td>
                                    <td class="text-center">{{ number_format($averageVehiclesPerCustomer, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        
        function printDailyReport() {
            const reportContent = `
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 20px;
                        background-color: #f5f5f5;
                    }
                    .nota-container {
                        max-width: 400px;
                        margin: auto;
                        background: white;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        border: 1px solid #ddd;
                    }
                    .nota-header {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .nota-header img {
                        width: 60px;
                        height: 60px;
                    }
                    .nota-header h3 {
                        margin: 10px 0 5px;
                    }
                    .nota-header p {
                        font-size: 12px;
                        color: #555;
                        margin: 0;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    table th, table td {
                        text-align: left;
                        padding: 8px;
                        border-bottom: 1px solid #ddd;
                    }
                    table th {
                        background-color: #f9f9f9;
                        font-size: 14px;
                    }
                    table td {
                        font-size: 13px;
                    }
                    .nota-footer {
                        text-align: center;
                        font-size: 12px;
                        color: #777;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="nota-container">
                    <div class="nota-header">
                        <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="Logo Laporan">
                        <h3>Laporan Servis Umum</h3>
                        <p>Tanggal: {{ $today }}</p>
                    </div>
                    <table>
                        <tr>
                            <th>Deskripsi</th>
                            <th>Detail</th>
                        </tr>
                        <tr>
                            <td>Pengunjung Hari Ini</td>
                            <td>{{ $totalVisitorsToday }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Sparepart</td>
                            <td>{{ $totalSpareparts }}</td>
                        </tr>
                        <tr>
                            <td>Sparepart Digunakan</td>
                            <td>{{ $totalSparepartsUsed }}</td>
                        </tr>
                        <tr>
                            <td>Keuntungan Total</td>
                            <td>Rp. {{ number_format($totalProfit, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Kendaraan per Pelanggan</td>
                            <td>{{ number_format($averageVehiclesPerCustomer, 2) }}</td>
                        </tr>
                    </table>
                    <div class="nota-footer">
                        <p>Terima kasih telah menggunakan layanan kami!</p>
                        <p>&copy; 2025 Bengkel TeFa</p>
                    </div>
                </div>
            </body>
            </html>
        `;
            const newWindow = window.open('', '_blank');
            newWindow.document.write(reportContent);
            newWindow.document.close();
            // newWindow.print();
        }
    </script>

</div>


<div class="tab-pane fade" id="audiences" role="tabpanel" aria-labelledby="profile-tab">
    @include('tab.audiences')
    
</div>
{{-- <div class="tab-pane fade" id="demographics" role="tabpanel" aria-labelledby="contact-tab">
    @include('tab.demographics')
    
</div> --}}
<div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="more-tab">
    @include('tab.more')
</div>