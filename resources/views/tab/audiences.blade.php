<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm hover-effect" id="card1" style="background-image: url('https://repository-images.githubusercontent.com/323802795/a4775800-4496-11eb-974f-29a5fef050c1'); background-size: cover; background-position: center; background-color: rgba(0, 0, 0, 0.7);">
                <div class="card-header d-flex align-items-center" style="color: white;">
                    <i class="bi bi-car-front me-2" style="font-size: 1.5rem;"></i>
                    <h5 class="card-title mb-0" style="color: rgb(255, 255, 255);">Jumlah Kendaraan per Pelanggan</h5>
                </div>
                <div class="card-body" style="color: rgb(255, 255, 255);">
                    <canvas id="vehiclesPerCustomerChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm hover-effect" id="card2" style="background-image: url('https://i.redd.it/58dbg2g0d7w41.jpg'); background-size: cover; background-position: center; background-color: rgba(0, 0, 0, 0.7);">
                <div class="card-header d-flex align-items-center" style="color: white;">
                    <i class="bi bi-person-lines-fill me-2" style="font-size: 1.5rem;"></i>
                    <h5 class="card-title mb-0" style="color: rgb(255, 255, 255);">Jumlah Rata-Rata Kendaraan per Pelanggan</h5>
                </div>
                <div class="card-body" style="color: rgb(255, 255, 255);">
                    <canvas id="averageVehiclesPerCustomerChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-4" style="color: white;">
        <p id="currentDate"></p>
    </div>
</div>

<script>
    var images = [
        'https://c4.wallpaperflare.com/wallpaper/291/819/697/illustration-city-anime-painting-wallpaper-preview.jpg',
        'https://wallpapercave.com/wp/wp4982777.jpg',
        'https://wallpapercrafter.com/desktop/328207-Anime-Weathering-With-You-Phone-Wallpaper.jpg',
        'https://wallpapercave.com/wp/wp6371069.jpg',
        'https://wallpapercave.com/wp/wp4979765.jpg',
        'https://wallpaperaccess.com/full/87241.jpg',
        'https://wallpapercave.com/wp/wp5393079.jpg',
        'https://th.bing.com/th/id/OIP.p0nxZ5M7f8Scm7IhR4Ts0gHaNK?pid=ImgDet&w=184&h=325&c=7&dpr=1,3',
    ];

    var currentIndex = 0;

    function changeBackgroundImage() {
        currentIndex = (currentIndex + 1) % images.length;
        document.getElementById('card1').style.backgroundImage = 'url(' + images[currentIndex] + ')';
        document.getElementById('card2').style.backgroundImage = 'url(' + images[(currentIndex + 1) % images.length] + ')';
    }

    setInterval(changeBackgroundImage, 5000);
</script>
<script>
    const vehiclesPerCustomerData = {
        labels: @json($customers->pluck('id')),
        datasets: [{
            label: 'Jumlah Kendaraan per Pelanggan',
            data: @json($customers->map(function($customer) {
                return $customer->vehicles ? $customer->vehicles->count() : 0;
            })),
            backgroundColor: 'rgba(0, 123, 255, 0.2)',  // Warna biru cerah
            borderColor: 'rgb(0,255,255)',  // Warna biru cerah
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };

    const vehiclesPerCustomerChart = new Chart(document.getElementById('vehiclesPerCustomerChart'), {
        type: 'line',
        data: vehiclesPerCustomerData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            family: 'Arial, sans-serif',
                            size: 14,
                            weight: 'bold',
                            color: '#fff' // Set text color to white
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderDash: [5, 5]
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 14,
                            weight: 'bold',
                            color: '#fff' // Set text color to white
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    bodyFont: {
                        family: 'Arial, sans-serif',
                        size: 14
                    },
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw + ' kendaraan';
                        }
                    }
                },
                legend: {
                    labels: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 16,
                            weight: 'bold',
                            color: '#fff' // Set legend text color to white
                        }
                    }
                }
            }
        }
    });

    // Breakdown of vehicles by time periods
    const vehiclesPerPeriodData = {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], // Weekdays
        datasets: [{
            label: 'Jumlah Kendaraan per Pelanggan (Minggu Ini)',
            data: @json($vehiclesPerWeekData), // Assuming you pass week data here (e.g., count per day of the week)
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(127,255,212)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };

    const vehiclesPerPeriodChart = new Chart(document.getElementById('vehiclesPerPeriodChart'), {
        type: 'line',
        data: vehiclesPerPeriodData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            family: 'Arial, sans-serif',
                            size: 14,
                            weight: 'bold',
                            color: '#fff' // Set text color to white
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderDash: [5, 5]
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 14,
                            weight: 'bold',
                            color: '#fff' // Set text color to white
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    bodyFont: {
                        family: 'Arial, sans-serif',
                        size: 14
                    },
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw + ' kendaraan';
                        }
                    }
                },
                legend: {
                    labels: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 16,
                            weight: 'bold',
                            color: '#fff' // Set legend text color to white
                        }
                    }
                }
            }
        }
    });

    const averageVehiclesPerCustomerData = {
        labels: ['Hari Ini', 'Minggu Ini', 'Bulan Ini', 'Tahun Ini'],
        datasets: [{
            label: 'Jumlah Rata-Rata Kendaraan per Pelanggan',
            data: [
                @json($averageVehiclesPerCustomer['today'] ?? 0),
                @json($averageVehiclesPerCustomer['thisWeek'] ?? 0),
                @json($averageVehiclesPerCustomer['thisMonth'] ?? 0),
                @json($averageVehiclesPerCustomer['thisYear'] ?? 0)
            ],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: '	rgb(0,191,255)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };

    const averageVehiclesPerCustomerChart = new Chart(document.getElementById('averageVehiclesPerCustomerChart'), {
        type: 'line',
        data: averageVehiclesPerCustomerData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            family: 'Arial, sans-serif',
                            size: 14,
                            weight: 'bold',
                            color: '#fff' // Set text color to white
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderDash: [5, 5]
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 14,
                            weight: 'bold',
                            color: '#fff' // Set text color to white
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    bodyFont: {
                        family: 'Arial, sans-serif',
                        size: 14
                    },
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw + ' kendaraan';
                        }
                    }
                },
                legend: {
                    labels: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 16,
                            weight: 'bold',
                            color: '#fff' // Set legend text color to white
                        }
                    }
                }
            }
        }
    });

    const currentDate = new Date();
    const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const monthsOfYear = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const formattedDate = `${daysOfWeek[currentDate.getDay()]}, ${currentDate.getDate()} ${monthsOfYear[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    
    document.getElementById('currentDate').innerHTML = `<p style="text-align: center; font-family: 'Arial', sans-serif; font-size: 14px; color: #fff;">${formattedDate}</p>`; // Set date text color to white
</script>