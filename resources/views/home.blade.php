@extends('layouts.app')

@section('content')
    <h1 class="welcome-text">Selamat Pagi, <span class="text-black fw-bold">Halo Dunia</span></h1>
    <h3 class="welcome-sub-text">Ringkasan kinerja Anda minggu ini</h3>

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
                                    <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Bagikan</a>
                                    <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Cetak</a>
                                    <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Ekspor</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content tab-content-basic">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="statistics-details d-flex align-items-center justify-content-between">
                                            <!-- Total Profit -->
                                            <div>
                                                <p class="statistics-title">Total Keuntungan</p>
                                                <h3 class="rate-percentage @if($totalProfit < 0) text-danger @else text-success @endif">
                                                    Rp. {{ number_format($totalProfit, 2, ',', '.') }}
                                                </h3>
                                                <p class="text-success d-flex">
                                                    <i class="mdi mdi-menu-up"></i>
                                                    <span>{{ $totalProfit > 0 ? '+' : '' }}{{ number_format($totalProfit, 2, ',', '.') }}%</span>
                                                </p>
                                            </div>
                                
                                            <!-- Total Expenses -->
                                            <div>
                                                <p class="statistics-title">Total Pengeluaran</p>
                                                <h3 class="rate-percentage @if($totalExpense < 0) text-danger @else text-success @endif">
                                                    Rp. {{ number_format($totalExpense, 2, ',', '.') }}
                                                </h3>
                                                <p class="text-danger d-flex">
                                                    <i class="mdi mdi-menu-up"></i>
                                                    <span>{{ $totalExpense > 0 ? '+' : '' }}{{ number_format($totalExpense, 2, ',', '.') }}%</span>
                                                </p>
                                            </div>
                                
                                            <!-- Total Unpaid -->
                                            <div>
                                                <p class="statistics-title">Total Belum Dibayar</p>
                                                <h3 class="rate-percentage @if($totalUnpaid < 0) text-danger @else text-success @endif">
                                                    Rp. {{ number_format($totalUnpaid, 2, ',', '.') }}
                                                </h3>
                                                <p class="text-danger d-flex">
                                                    <i class="mdi mdi-menu-up"></i>
                                                    <span>{{ $totalUnpaid > 0 ? '+' : '' }}{{ number_format($totalUnpaid, 2, ',', '.') }}%</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div class="d-sm-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h4 class="card-title card-title-dash text-dark">Grafik Kinerja</h4>
                                                                <h5 class="card-subtitle card-subtitle-dash">Grafik keuntungan harian berdasarkan servis</h5>
                                                            </div>
                                                        </div>
                                                        <div class="chartjs-wrapper mt-4">
                                                            <canvas id="performanceLine" width="400" height="200"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <!-- Card for Total Kendaraan -->
                                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                <div class="card card-rounded"
                                                    style="background-image: url('https://www.madornomad.com/wp-content/uploads/2019/09/japan-riding-17.jpg'); 
                                                            background-size: cover; 
                                                            background-position: center; 
                                                            color: white; 
                                                            border-radius: 20px; 
                                                            overflow: hidden; 
                                                            transition: transform 0.3s ease, box-shadow 0.3s ease; 
                                                            position: relative;">
                                                    <div class="card-body"
                                                                padding: 20px; 
                                                                position: relative; 
                                                                z-index: 1;">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <p class="text-small mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                                    Total Kendaraan / 車両合計
                                                                </p> <!-- Indonesian and Japanese Text -->
                                                                <h4 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                    {{ $totalVehicles }} / {{ $totalVehicles }}台
                                                                </h4> <!-- Displaying total vehicles in both languages -->
                                                            </div>

                                                            <!-- Icon added to the right -->
                                                            <i class="fas fa-car-side fa-2x" style="color: white;"></i> <!-- Car icon -->
                                                        </div>
                                                    </div>

                                                    <!-- Bunga Sakura SVG (Sekarang muncul saat hover) -->
                                                    <svg class="sakura-hover" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"
                                                        style="position: absolute; top: 10%; left: 10%; opacity: 0; transition: opacity 0.3s ease;">
                                                        <circle cx="20" cy="20" r="6" fill="pink"/>
                                                        <circle cx="40" cy="30" r="6" fill="pink"/>
                                                        <circle cx="60" cy="20" r="6" fill="pink"/>
                                                        <circle cx="80" cy="40" r="6" fill="pink"/>
                                                    </svg>

                                                    <!-- Hover Effect using Inline CSS -->
                                                    <style>
                                                        .card:hover {
                                                            transform: scale(1.05);  /* Membesarkan card */
                                                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Menambahkan bayangan */
                                                        }

                                                        .card:hover .sakura-hover {
                                                            opacity: 1;  /* Menampilkan bunga sakura saat hover */
                                                        }
                                                    </style>
                                                </div>
                                            </div>
                                            <!-- Card with Indonesian and Japanese Text, Including Number Example -->
                                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                <div class="card card-rounded"
                                                    style="background-image: url('https://st3.depositphotos.com/4080643/17799/i/450/depositphotos_177995342-stock-photo-fuji-mountain-and-cherry-blossoms.jpg'); 
                                                            background-size: cover; 
                                                            background-position: center; 
                                                            color: white; 
                                                            border-radius: 20px; 
                                                            overflow: hidden; 
                                                            transition: transform 0.5s ease, box-shadow 0.5s ease, filter 0.3s ease; 
                                                            height: 160px; width: 100%;">
                                                    <div class="card-body" style="padding: 10px 15px; position: relative; z-index: 1;">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <!-- Indonesian and Japanese Text with Number Example -->
                                                                <p class="text-small mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                                    Total Pengunjung / 訪問者数
                                                                </p>
                                                                <h4 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                    {{ $totalVisitors }} / {{ $totalVisitors }}人
                                                                </h4> <!-- Example: Displaying number in both languages -->
                                                            </div>
                                                            <!-- Icon with hover effect -->
                                                            <div>
                                                                <i class="fas fa-users fa-2x group-hover:scale-125 group-hover:text-yellow-400 transition-all duration-300"></i> <!-- FontAwesome Icon -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded table-darkBGImg">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="card-title card-title-dash text-white">Statistik / 統計</h4>
                                                        <p class="card-subtitle card-subtitle-dash text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit / ロレム・イプサム・ドル・シット・アメット・コンセクテトゥア・アディピシング・エリット</p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover text-white" style="background-color: transparent; border-radius: 15px; overflow: hidden;">
                                                        <thead>
                                                            <tr>
                                                                <th>Tanggal / 日付</th>
                                                                <th>Lead / リード</th>
                                                                <th>Pengunjung / 訪問者</th>
                                                                <th>Pertumbuhan / 成長</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($chartData as $data)
                                                                <tr>
                                                                    <td>{{ $data['service_date'] }}</td>
                                                                    <td>--</td>
                                                                    <td>--</td>
                                                                    <td>{{ number_format($data['total_profit'], 2) }}%</td>
                                                                </tr>
                                                            @endforeach
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