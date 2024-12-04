@extends('layouts.app')

@section('content')
    <h1 class="welcome-text">Selamat Pagi, <span class="text-black fw-bold">Halo Dunia</span></h1>
    <h3 class="welcome-sub-text">Ringkasan kinerja Anda minggu ini</h3>

    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home-tab">
                        <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demographics</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">More</a>
                                </li>
                            </ul>
                            <div>
                                <div class="btn-wrapper">
                                    <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
                                    <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                                    <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content tab-content-basic">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="statistics-details d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="statistics-title">Bounce Rate</p>
                                                <h3 class="rate-percentage">{{ $bounceRate }}%</h3>
                                                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>{{ $bounceRateChange }}%</span></p>
                                            </div>
                                            <div>
                                                <p class="statistics-title">Page Views</p>
                                                <h3 class="rate-percentage">{{ $pageViews }}</h3>
                                                <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>{{ $pageViewsChange }}%</span></p>
                                            </div>
                                            <div>
                                                <p class="statistics-title">New Sessions</p>
                                                <h3 class="rate-percentage">{{ $newSessions }}</h3>
                                                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>{{ $newSessionsChange }}%</span></p>
                                            </div>
                                            <div class="d-none d-md-block">
                                                <p class="statistics-title">Avg. Time on Site</p>
                                                <h3 class="rate-percentage">{{ $avgTimeOnSite }}</h3>
                                                <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>{{ $avgTimeChange }}%</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div class="d-sm-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h4 class="card-title card-title-dash">Performance Line Chart</h4>
                                                                <h5 class="card-subtitle card-subtitle-dash">Lorem Ipsum is simply dummy text of the printing</h5>
                                                            </div>
                                                            <div id="performanceLine-legend"></div>
                                                        </div>
                                                        <div class="chartjs-wrapper mt-4">
                                                            <canvas id="performanceLine" width=""></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                <div class="card bg-primary card-rounded">
                                                    <div class="card-body pb-0">
                                                        <h4 class="card-title card-title-dash text-white mb-4">Status Summary</h4>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <p class="status-summary-ight-white mb-1">Closed Value</p>
                                                                <h2 class="text-info">357</h2>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="status-summary-chart-wrapper pb-4">
                                                                    <canvas id="status-summary"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="d-flex justify-content-between align-items-center mb-2 mb-sm-0">
                                                                    <div class="circle-progress-width">
                                                                        <div id="totalVisitors" class="progressbar-js-circle pr-2"></div>
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-small mb-2">Total Visitors</p>
                                                                        <h4 class="mb-0 fw-bold">26.80%</h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div class="circle-progress-width">
                                                                        <div id="visitperday" class="progressbar-js-circle pr-2"></div>
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-small mb-2">Visits per day</p>
                                                                        <h4 class="mb-0 fw-bold">9065</h4>
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

                                <div class="row">
                                    <div class="col-lg-8 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div class="d-sm-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h4 class="card-title card-title-dash">Market Overview</h4>
                                                                <p class="card-subtitle card-subtitle-dash">Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
                                                            </div>
                                                            <div>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-light dropdown-toggle toggle-dark btn-lg mb-0 me-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> This month </button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                                        <h6 class="dropdown-header">Settings</h6>
                                                                        <a class="dropdown-item" href="#">Action</a>
                                                                        <a class="dropdown-item" href="#">Another action</a>
                                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                                        <div class="dropdown-divider"></div>
                                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-sm-flex align-items-center mt-1 justify-content-between">
                                                            <div class="d-sm-flex align-items-center mt-4 justify-content-between">
                                                                <h2 class="me-2 fw-bold">$36,2531.00</h2>
                                                                <h4 class="me-2">USD</h4>
                                                                <h4 class="text-success">(+1.37%)</h4>
                                                            </div>
                                                            <div class="me-3">
                                                                <div id="marketingOverview-legend"></div>
                                                            </div>
                                                        </div>
                                                        <div class="chartjs-bar-wrapper mt-3">
                                                            <canvas id="marketingOverview"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card card-rounded table-darkBGImg">
                                                    <div class="card-body">
                                                        <div class="d-sm-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h4 class="card-title card-title-dash text-white">Statistics</h4>
                                                                <p class="card-subtitle card-subtitle-dash text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover text-white" style="background-color: transparent; border-radius: 15px; overflow: hidden;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Lead</th>
                                                                        <th>Visitors</th>
                                                                        <th>Growth</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr class="hover-effeft">
                                                                        <td>2023-09-10</td>
                                                                        <td>230</td>
                                                                        <td>897</td>
                                                                        <td>15%</td>
                                                                    </tr>
                                                                    <tr class="hover-effeft">
                                                                        <td>2023-09-11</td>
                                                                        <td>150</td>
                                                                        <td>920</td>
                                                                        <td>19%</td>
                                                                    </tr>
                                                                    <tr class="hover-effeft">
                                                                        <td>2023-09-12</td>
                                                                        <td>188</td>
                                                                        <td>870</td>
                                                                        <td>12%</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="col-lg-4 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-sm-flex justify-content-between align-items-center">
                                                                <div class="circle-progress-width">
                                                                    <div id="totalVisitors" class="progressbar-js-circle pr-2"></div>
                                                                </div>
                                                                <div>
                                                                    <p class="text-small mb-2">Total Visitors</p>
                                                                    <h4 class="mb-0 fw-bold">26.80%</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                <div class="card bg-primary card-rounded">
                                                    <div class="card-body pb-0">
                                                        <h4 class="card-title card-title-dash text-white mb-4">Status Summary</h4>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <p class="status-summary-ight-white mb-1">Closed Value</p>
                                                                <h2 class="text-info">357</h2>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="status-summary-chart-wrapper pb-4">
                                                                    <canvas id="status-summary"></canvas>
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
                </div>
            </div>
        </div>
    </div>
@endsection