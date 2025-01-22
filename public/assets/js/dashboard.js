(function($) {
    'use strict';
    $(function() {
        const ctx = document.getElementById('performanceLine').getContext('2d');

        // Gradients for weekly and monthly data
        var graphGradient = ctx.createLinearGradient(5, 0, 5, 100);
        graphGradient.addColorStop(0, 'rgba(26, 115, 232, 0.18)');
        graphGradient.addColorStop(1, 'rgba(26, 115, 232, 0.02)');

        var graphGradient2 = ctx.createLinearGradient(100, 0, 50, 150);
        graphGradient2.addColorStop(0, 'rgba(0, 208, 255, 0.19)');
        graphGradient2.addColorStop(1, 'rgba(0, 208, 255, 0.03)');

        // New gradient for monthly data (purple)
        var graphGradientMonth = ctx.createLinearGradient(5, 0, 5, 100);
        graphGradientMonth.addColorStop(0, 'rgba(128, 0, 128, 0.18)'); // Purple color
        graphGradientMonth.addColorStop(1, 'rgba(128, 0, 128, 0.02)'); // Lighter purple color

        const chartData = {
            id: {
                labels: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
                datasets: [{
                    label: 'Kinerja Minggu Ini',
                    data: chartValues,
                    backgroundColor: graphGradient,
                    borderColor: '#1F3BB3',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#1F3BB3',
                    pointBorderColor: '#fff',
                }, {
                    label: 'Kinerja Minggu Lalu',
                    data: chartValues,
                    backgroundColor: graphGradient2,
                    borderColor: '#52CDFF',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#52CDFF',
                    pointBorderColor: '#fff',
                }, {
                    label: 'Kinerja Per Bulan (Ungu)',
                    data: monthlyChartValues, // Data bulanan
                    backgroundColor: graphGradientMonth,
                    borderColor: '#800080', // Ungu
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#800080', // Ungu
                    pointBorderColor: '#fff',
                }]
            },
            jp: {
                labels: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"],
                datasets: [{
                    label: '今週のパフォーマンス',
                    data: chartValues,
                    backgroundColor: graphGradient,
                    borderColor: '#1F3BB3',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#1F3BB3',
                    pointBorderColor: '#fff',
                }, {
                    label: '先週のパフォーマンス',
                    data: chartValues,
                    backgroundColor: graphGradient2,
                    borderColor: '#52CDFF',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#52CDFF',
                    pointBorderColor: '#fff',
                }, {
                    label: '月間パフォーマンス (紫)',
                    data: monthlyChartValues, // Data bulanan
                    backgroundColor: graphGradientMonth,
                    borderColor: '#800080', // Ungu
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#800080', // Ungu
                    pointBorderColor: '#fff',
                }]
            },
            en: {
                labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                datasets: [{
                    label: 'This Week\'s Performance',
                    data: chartValues,
                    backgroundColor: graphGradient,
                    borderColor: '#1F3BB3',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#1F3BB3',
                    pointBorderColor: '#fff',
                }, {
                    label: 'Last Week\'s Performance',
                    data: chartValues,
                    backgroundColor: graphGradient2,
                    borderColor: '#52CDFF',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#52CDFF',
                    pointBorderColor: '#fff',
                }, {
                    label: 'Monthly Performance (Purple)',
                    data: monthlyChartValues, // Data bulanan
                    backgroundColor: graphGradientMonth,
                    borderColor: '#800080', // Ungu
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#800080', // Ungu
                    pointBorderColor: '#fff',
                }]
            },
            su: {
                labels: ["Minggu", "Senén", "Salasa", "Rebo", "Kemis", "Juma'at", "Saptu"],
                datasets: [{
                    label: 'Kinerja Minggu Ieu',
                    data: chartValues,
                    backgroundColor: graphGradient,
                    borderColor: '#1F3BB3',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#1F3BB3',
                    pointBorderColor: '#fff',
                }, {
                    label: 'Kinerja Minggu Kamari',
                    data: chartValues,
                    backgroundColor: graphGradient2,
                    borderColor: '#52CDFF',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#52CDFF',
                    pointBorderColor: '#fff',
                }, {
                    label: 'Kinerja Per Bulan (Ungu)',
                    data: monthlyChartValues, // Data bulanan
                    backgroundColor: graphGradientMonth,
                    borderColor: '#800080', // Ungu
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBorderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: '#800080', // Ungu
                    pointBorderColor: '#fff',
                }]
            }
        };

        let chart = new Chart(ctx, {
            type: 'line',
            data: chartData.id, // Set default data to Indonesian (id)
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: { tension: 0.4 }
                },
                scales: {
                    y: {
                        border: { display: false },
                        grid: { display: true, color: "#E0E0E0" },
                        ticks: {
                            beginAtZero: false,
                            autoSkip: true,
                            maxTicksLimit: 4,
                            color: "#6B778C",
                            font: { size: 12 },
                            padding: 10
                        }
                    },
                    x: {
                        border: { display: false },
                        grid: { display: false },
                        ticks: {
                            beginAtZero: false,
                            autoSkip: true,
                            maxTicksLimit: 7,
                            color: "#6B778C",
                            font: { size: 12 },
                            padding: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: "#6B778C",
                            font: { size: 12 },
                        }
                    }
                }
            }
        });

        // Language switch logic
        $('#languageSelect').on('change', function() {
            const selectedLanguage = $(this).val();
            chart.data = chartData[selectedLanguage];
            chart.update();
        });

        // Set default language to Indonesian (id)
        const defaultLang = 'id';
        $('#languageSelect').val(defaultLang).trigger('change');
    });
})(jQuery);