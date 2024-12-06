@extends('layouts.app')

@section('content')
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

        document.getElementById('greeting').innerHTML = greetingText + ', <span class="fw-bold welcome-text">Halo Dunia</span>';
    </script>
    <!-- partial -->
    <div class="main-paanel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home-tab">
                        <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Gambaran Umum</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiens</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demografi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">Lainnya</a>
                                </li>
                            </ul>
                            <div>
                                <div class="btn-wrapper">
                                    <a href="#" class="btn btn-outline-dark align-items-center" id="shareBtn"><i class="icon-share"></i> Bagikan</a>
                                    <a href="#" class="btn btn-outline-dark" id="printBtn"><i class="icon-printer"></i> Cetak</a>
                                    <a href="#" class="btn btn-primary text-white me-0" id="exportBtn"><i class="icon-download"></i> Ekspor</a>
                                </div>
                                
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const shareBtn = document.getElementById("shareBtn");
                                        shareBtn.addEventListener("click", function(e) {
                                            e.preventDefault();

                                            // Memeriksa apakah browser mendukung Web Share API
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
                                        printBtn.addEventListener("click", function(e) {
                                            e.preventDefault();
                                            
                                            window.print();
                                        });

                                        const exportBtn = document.getElementById("exportBtn");
                                        exportBtn.addEventListener("click", function(e) {
                                            e.preventDefault();
                                            
                                            const doc = new jsPDF();

                                            const content = document.body.innerHTML;

                                            doc.html(content, {
                                                callback: function (doc) {
                                                    doc.save("exported_page.pdf");
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="tab-content tab-content-basic">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <!-- Total Profit -->
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="statistics-details d-flex align-items-center justify-content-between">
                                                <div>
                                                    <p class="statistics-title">Total Keuntungan</p>
                                                    <h3 class="rate-percentage @if($totalProfit < 0) text-danger @else text-success @endif">
                                                        Rp. {{ number_format($totalProfit, 2, ',', '.') }}
                                                    </h3>
                                                    <p class="text-success d-flex">
                                                        <i class="mdi mdi-menu-up"></i>
                                                        <span>{{ $profitPercentage > 0 ? '+' : '' }}{{ number_format($profitPercentage, 2, ',', '.') }}%</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Total Expenses -->
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="statistics-details d-flex align-items-center justify-content-between">
                                                <div>
                                                    <p class="statistics-title">Total Pengeluaran</p>
                                                    <h3 class="rate-percentage @if($totalExpense < 0) text-danger @else text-success @endif">
                                                        Rp. {{ number_format($totalExpense, 2, ',', '.') }}
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
                                                    <p class="statistics-title">Total Belum Dibayar</p>
                                                    <h3 class="rate-percentage @if($totalUnpaid < 0) text-danger @else text-success @endif">
                                                        Rp. {{ number_format($totalUnpaid, 2, ',', '.') }}
                                                    </h3>
                                                    <p class="text-danger d-flex">
                                                        <i class="mdi mdi-menu-down"></i>
                                                        <span>{{ $unpaidPercentage > 0 ? '-' : '' }}{{ number_format($unpaidPercentage, 2, ',', '.') }}%</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Average Profit Per Customer -->
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="statistics-details d-flex align-items-center justify-content-between">
                                                <div>
                                                    <p class="statistics-title">Keuntungan Rata-rata per Pelanggan</p>
                                                    <h3 class="rate-percentage @if($averageProfitPerCustomer < 0) text-danger @else text-success @endif">
                                                        Rp. {{ number_format($averageProfitPerCustomer, 2, ',', '.') }}
                                                    </h3>
                                                    <p class="text-success d-flex">
                                                        <i class="mdi mdi-menu-up"></i>
                                                        <span>
                                                            Rp. {{ number_format($averageProfitPerCustomer, 2, ',', '.') }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card card-rounded" style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-radius: 10px;">
                                                    <div class="card-body">
                                                        <div class="d-sm-flex align-items-center">
                                                            <div>
                                                                <h4 id="grafikKinerja" class="card-title card-title-dash text-dark">Grafik Kinerja</h4>
                                                                <h5 id="grafikKinerjaSub" class="card-subtitle card-subtitle-dash">Grafik keuntungan harian berdasarkan servis</h5>
                                                            </div>
                                                            <!-- Language Selector with Icons -->
                                                            <div class="mb-4 ml-auto">
                                                                <select id="languageSelect" class="form-select form-select-sm" aria-label="Language Select" 
                                                                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 5px;">
                                                                    <option value="id" selected>Bahasa Indonesia</option>
                                                                    <option value="jp">日本語 (Japanese)</option>
                                                                    <option value="en">English</option>
                                                                    <option value="su">Sunda</option>
                                                                </select>
                                                            </div>
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
                                                        </script>                                                                                                              
                                                        <!-- Chart -->
                                                        <div class="chartjs-wrapper mt-4">
                                                            <canvas id="performanceLine" width="400" height="200"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        const chartLabels = @json($chartLabels);
                                        const chartValues = @json($chartValues);
                                        const monthlyChartValues = @json($monthlyChartValues); // Add monthly values here
                                    </script>
                                    <div class="col-lg-6 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                <div class="card card-rounded" id="vehicle-card"
                                                     style="background-image: url('https://www.madornomad.com/wp-content/uploads/2019/09/japan-riding-17.jpg'); 
                                                            background-size: cover; 
                                                            background-position: center; 
                                                            color: white; 
                                                            border-radius: 20px; 
                                                            overflow: hidden; 
                                                            transition: transform 0.3s ease, box-shadow 0.3s ease; 
                                                            position: relative;" onclick="changeImageRandomly()">
                                                    
                                                    <div class="card-body" style="padding: 20px; position: relative; z-index: 1;">
                                                        <div class="d-flex justify-content-between align-items-center bike-card">
                                                            <div>
                                                                <p class="text-small mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                                    Total Kendaraan / 車両合計
                                                                </p>
                                                                <h4 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                    {{ $totalVehicles }} / {{ $totalVehicles }}台
                                                                </h4>
                                                            </div>
                                                            <i class="fas fa-car-side fa-2x" style="color: white;"></i>
                                                        </div>
                                                    </div>
                                            
                                                    <svg class="sakura-hover" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"
                                                         style="position: absolute; top: 10%; left: 10%; opacity: 0; transition: opacity 0.3s ease;">
                                                        <circle cx="20" cy="20" r="6" fill="pink"/>
                                                        <circle cx="40" cy="30" r="6" fill="pink"/>
                                                        <circle cx="60" cy="20" r="6" fill="pink"/>
                                                        <circle cx="80" cy="40" r="6" fill="pink"/>
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

                                                    setInterval(changeImageRandomly, 4000); // Gambar akan berubah setiap 4 detik

                                                    cardElement.addEventListener('click', function() {
                                                        changeImageRandomly(); // Panggil fungsi untuk mengganti gambar saat diklik
                                                    });
                                                });
                                            </script>
                                        <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                            <div class="card card-rounded"
                                                style="background-image: url('https://st3.depositphotos.com/4080643/17799/i/450/depositphotos_177995342-stock-photo-fuji-mountain-and-cherry-blossoms.jpg'); 
                                                        background-size: cover; 
                                                        background-position: center; 
                                                        color: white; 
                                                        border-radius: 20px; 
                                                        overflow: hidden; 
                                                        transition: transform 0.5s ease, box-shadow 0.5s ease, filter 0.3s ease; 
                                                        height: 160px; width: 100%;" id="visitor-card">
                                                
                                                <div class="card-body" style="padding: 10px 15px; position: relative; z-index: 1;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <p class="text-small mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                                Total Pengunjung / 訪問者数
                                                            </p>
                                                            <h4 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                {{ $totalVisitors }} / {{ $totalVisitors }}人
                                                            </h4> <!-- Displaying number in both languages -->
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-users fa-2x group-hover:scale-125 group-hover:text-yellow-400 transition-all duration-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const backgroundImages = [
                                                    'https://wallpapercave.com/wp/wp5753394.jpg',
                                                    'https://wallpapercave.com/wp/wp5753409.jpg',
                                                    'https://cdn.wallpapersafari.com/15/15/djZk0c.jpg',
                                                    'https://wallpapers.com/images/hd/night-cherry-blossom-pc4o7liqwpqdm894.jpg',
                                                    'https://farm4.staticflickr.com/3406/3537584665_d2d1858a0a_b.jpg',
                                                    // 'https://thumbs.dreamstime.com/b/symbols-icons-asian-culture-traditional-pattern-template-design-geometric-icon-logo-set-hand-drawn-trees-310769156.jpg',
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
                                                        <p class="card-subtitle card-subtitle-dash text-white">Pantau perkembangan pengunjung, sparepart, dan kendaraan untuk memastikan pertumbuhan dan efisiensi yang optimal.</p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover text-white" style="background-color: transparent; border-radius: 15px; overflow: hidden;">
                                                        <thead>
                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <th>Pengunjung Hari Ini</th>
                                                                <th>Jumlah Sparepart</th>
                                                                <th>Pertumbuhan</th>
                                                                <th colspan="3" class="text-center">Rata-rata Jumlah Kendaraan per Pelanggan</th>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection