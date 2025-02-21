<div class="container animate__animated animate__fadeIn">
    <h2 class="text-center mb-4">
        <i class="fas fa-file-invoice-dollar me-2"></i> Laporan Pemasukan Servis
    </h2>

    @if (Gate::allows('isBendahara'))
        <form method="GET" action="{{ route('dashboard') }}" class="mb-3 row g-3">
            <div class="col-md-3 mb-4 animate__animated animate__fadeInLeft">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                    <select name="filterJurusan" class="form-select" onchange="this.form.submit()">
                        <option value="semua" {{ request('filterJurusan', 'semua') === 'semua' ? 'selected' : '' }}>🔄
                            Semua Jurusan</option>
                        <option value="TSM" {{ request('filterJurusan') === 'TSM' ? 'selected' : '' }}>🏍️ TSM
                        </option>
                        <option value="TKRO" {{ request('filterJurusan') === 'TKRO' ? 'selected' : '' }}>🚗 TKRO
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-4 animate__animated animate__fadeInRight">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="month" name="filterBulan" class="form-control"
                        value="{{ request('filterBulan', now()->format('Y-m')) }}" onchange="this.form.submit()">
                </div>
            </div>
        </form>
    @endif

    <ul class="nav nav-tabs mb-3 animate__animated animate__bounce">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#daily"><i class="fas fa-calendar-day"></i>
                Harian</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#monthly"><i class="fas fa-calendar-alt"></i> Bulanan</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- TABEL HARIAN -->
        <div class="tab-pane fade show active" id="daily">
            <table class="table table-bordered table-hover animate__animated animate__fadeInUp">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Pemasukan</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dailyCustomerData as $data)
                        <tr>
                            <td>{{ $data['customer_name'] }}</td>
                            <td>{{ $data['vehicle'] }}</td>
                            <td class="text-success fw-bold">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                            <td>{{ $data['service_time'] }}</td>
                            <td><a href="{{ route('service.show', $data['id']) }}" class="btn btn-primary btn-sm"><i
                                        class="fas fa-eye"></i> Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <td colspan="3" class="text-end fw-bold">Total Pemasukan:</td>
                        <td class="text-success fw-bold">Rp {{ number_format($totalDailyIncome, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- TABEL BULANAN -->
        <div class="tab-pane fade" id="monthly">
            <table class="table table-bordered table-hover animate__animated animate__fadeInUp">
                <thead class="table-dark">
                    <tr>
                        <th>Bulan</th>
                        <th>Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $selectedYear = request('filterBulan')
                            ? date('Y', strtotime(request('filterBulan')))
                            : date('Y');
                        $totalAllMonths = 0;
                        $allMonths = [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember',
                        ];
                    @endphp
                    @foreach ($allMonths as $index => $month)
                        @php
                            $monthKey = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                            $income = $filteredMonthlyCustomerData[$monthKey] ?? 0;
                            $totalAllMonths += $income;
                        @endphp
                        <tr>
                            <td>📆 {{ $month }} {{ $selectedYear }}</td>
                            <td class="text-success fw-bold">Rp {{ number_format($income, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <td class="text-end fw-bold">Total Keseluruhan ({{ $selectedYear }}):</td>
                        <td class="text-success fw-bold">Rp {{ number_format($totalAllMonths, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3 animate__animated animate__zoomIn">
        <button onclick="printTable()" class="btn btn-success text-light animate__animated animate__rubberBand">
            <i class="fas fa-print me-2"></i> Cetak
        </button>
    </div>
</div>

<script>
    function printTable() {
        window.print();
    }

    document.addEventListener("DOMContentLoaded", function() {
        let activeTab = localStorage.getItem("activeTab");
        if (activeTab) {
            let tabElement = new bootstrap.Tab(document.querySelector(`[href="${activeTab}"]`));
            tabElement.show();
        }
        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener("shown.bs.tab", function(event) {
                localStorage.setItem("activeTab", event.target.getAttribute("href"));
            });
        });
    });
</script>