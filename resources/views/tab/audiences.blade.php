<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm hover-effect" id="card1" style="background-color: white;">
                <div class="card-header d-flex align-items-center" style="color: black; background-color: #f8f9fa;">
                    <i class="bi bi-car-front me-2" style="font-size: 1.5rem;"></i>
                    <h5 class="card-title mb-0">Jumlah Kendaraan per Pelanggan</h5>
                </div>
                <div class="card-body" style="color: black;">
                    <canvas id="vehiclesPerCustomerChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm hover-effect" id="card2" style="background-color: white;">
                <div class="card-header d-flex align-items-center" style="color: black; background-color: #f8f9fa;">
                    <i class="bi bi-person-lines-fill me-2" style="font-size: 1.5rem;"></i>
                    <h5 class="card-title mb-0">Jumlah Rata-Rata Kendaraan per Pelanggan</h5>
                </div>
                <div class="card-body" style="color: black;">
                    <canvas id="averageVehiclesPerCustomerChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-4" style="color: black;">
        <p id="currentDate"></p>
    </div>
</div>
<script>
    const vehiclesPerCustomerData = {
        labels: @json($customers->pluck('id')),
        datasets: [{
            label: 'Jumlah Kendaraan per Pelanggan',
            data: @json($customers->map(function($customer) {
                return $customer->vehicles ? $customer->vehicles->count() : 0;
            })),
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            borderColor: 'rgb(0,255,255)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };

    const vehiclesPerCustomerChart = new Chart(document.getElementById('vehiclesPerCustomerChart'), {
        type: 'line',
        data: vehiclesPerCustomerData,
        options: getChartOptions()
    });

    const vehiclesPerPeriodData = {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
            label: 'Jumlah Kendaraan per Pelanggan (Minggu Ini)',
            data: @json($vehiclesPerWeekData),
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
        options: getChartOptions()
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
            borderColor: 'rgb(0,191,255)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };

    const averageVehiclesPerCustomerChart = new Chart(document.getElementById('averageVehiclesPerCustomerChart'), {
        type: 'line',
        data: averageVehiclesPerCustomerData,
        options: getChartOptions()
    });

    function getChartOptions() {
        return {
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
                            color: '#fff' // Set y-axis text color to white
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderDash: [5, 5]
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 14,
                            weight: 'bold',
                            color: '#fff' // Set x-axis text color to white
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
        };
    }

    // Display current date in white color
    const currentDate = new Date();
    const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const monthsOfYear = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const formattedDate = `${daysOfWeek[currentDate.getDay()]}, ${currentDate.getDate()} ${monthsOfYear[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    
    document.getElementById('currentDate').innerHTML = `
        <p style="text-align: center; font-family: 'Arial', sans-serif; font-size: 14px; color: #fff;">
            ${formattedDate}
        </p>`;
</script>