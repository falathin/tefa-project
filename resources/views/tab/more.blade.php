<div class="container">
    <h2 class="text-center mb-4">
        <i class="fas fa-file-invoice-dollar text-warning me-2"></i> Laporan Pemasukan Servis
    </h2>
    @if (Gate::allows('isBendahara'))
        <form method="GET" action="{{ route('dashboard') }}" class="mb-3 row g-3">
            <div class="col-12 col-md-3">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-primary text-light">
                        <i class="fas fa-credit-card"></i>
                    </span>
                    <select name="filterJurusan" id="filterJurusan" class="form-select" onchange="this.form.submit()">
                        <option value="semua" {{ request('filterJurusan', 'semua') === 'semua' ? 'selected' : '' }}>
                            Semua Jurusan</option>
                        <option value="TSM" {{ request('filterJurusan') === 'TSM' ? 'selected' : '' }}>TSM</option>
                        <option value="TKRO" {{ request('filterJurusan') === 'TKRO' ? 'selected' : '' }}>TKRO</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-success text-light">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <input type="month" name="filterBulan" id="filterBulan" class="form-control"
                        value="{{ request('filterBulan', now()->format('Y-m')) }}" onchange="this.form.submit()">
                </div>
            </div>
        </form>
    @endif
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active text-dark fw-bold" data-bs-toggle="tab" href="#daily">
                <i class="fas fa-calendar-day text-info"></i> Harian
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-dark fw-bold" data-bs-toggle="tab" href="#monthly">
                <i class="fas fa-calendar-alt text-danger"></i> Bulanan
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="daily">
            <table class="table table-bordered table-hover shadow-sm">
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
                            <td>
                                <a href="{{ route('service.show', $data['id']) }}"
                                    class="btn btn-primary text-light btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total Pemasukan:</td>
                        <td class="text-success fw-bold">Rp {{ number_format($totalDailyIncome, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="tab-pane fade" id="monthly">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Bulan</th>
                        <th>Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
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
                        $totalAllMonths = 0;
                        $selectedYear = request()->input('filterTahun', date('Y'));
                    @endphp

                    @foreach ($allMonths as $index => $month)
                        @php
                            $monthKey = $index + 1;
                            $income = $monthlyCustomerData[$monthKey]->total_income ?? 0;
                            $totalAllMonths += $income;
                        @endphp
                        <tr>
                            <td>{{ $month }} {{ $selectedYear }}</td>
                            <td class="{{ $income > 0 ? 'text-success fw-bold' : 'text-muted' }}">
                                Rp {{ number_format($income, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td class="text-end fw-bold">Total Keseluruhan ({{ $selectedYear }}):</td>
                        <td class="text-success fw-bold">Rp {{ number_format($totalAllMonths, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if (Gate::allows('isBendahara'))
        <form
            action="{{ route('export.service', [
                'category' => 'TKRO
                                    ',
            ]) }}"
            method="GET" class="mt-4 p-3 shadow-sm border rounded">
            <h5><i class="fas fa-download text-success"></i> Download Laporan TKRO</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3 w-100">
                <i class="fas fa-file-excel text-light"></i> <span class='text-light'>Download Laporan</span>
            </button>
        </form>
        <form action="{{ route('export.service', ['category' => 'TSM']) }}" method="GET"
            class="mt-4 p-3 shadow-sm border rounded">
            <h5><i class="fas fa-download text-primary"></i> Download Laporan TSM</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 w-100">
                <i class="fas fa-file-excel text-light"></i> <span class="text-light">Download Laporan</span>
            </button>
        </form>
    @elseif (Auth::user()->jurusan == 'TKRO')
        <form
            action="{{ route('export.service', [
                'category' => 'TKRO
                                    ',
            ]) }}"
            method="GET" class="mt-4 p-3 shadow-sm border rounded">
            <h5><i class="fas fa-download text-success"></i> Download Laporan TKRO</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3 w-100">
                <i class="fas fa-file-excel text-light"></i> <span class='text-light'>Download Laporan</span>
            </button>
        </form>
    @elseif (Auth::user()->jurusan == 'TSM')
        <form action="{{ route('export.service', ['category' => 'TSM']) }}" method="GET"
            class="mt-4 p-3 shadow-sm border rounded">
            <h5><i class="fas fa-download text-primary"></i> Download Laporan TSM</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 w-100">
                <i class="fas fa-file-excel text-light"></i> <span class="text-light">Download Laporan</span>
            </button>
        </form>
    @endif

</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Menyimpan tab yang terakhir diklik
        let activeTab = localStorage.getItem("activeTab");
        if (activeTab) {
            let tabElement = document.querySelector(`a[href='${activeTab}']`);
            if (tabElement) {
                let tab = new bootstrap.Tab(tabElement);
                tab.show();
            }
        }

        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener("shown.bs.tab", function(event) {
                localStorage.setItem("activeTab", event.target.getAttribute("href"));
            });
        });
    });
</script>
