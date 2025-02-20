<h2 class="text-center mb-4">
    <i class="fas fa-file-invoice-dollar me-2"></i>Laporan Pemasukan Servis
</h2>

@if (Gate::allows('isBendahara'))
<form method="GET" action="{{ route('dashboard') }}" class="mb-3 row g-3">
    <div class="col-12 col-md-3 mb-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
            <select name="filterJurusan" id="filterJurusan" class="form-select" onchange="this.form.submit()">
                <option value="semua" {{ session('filterJurusan') === 'semua' ? 'selected' : '' }}>Filter semua jurusan</option>
                <option value="TSM" {{ session('filterJurusan') === 'TSM' ? 'selected' : '' }}>Filter jurusan TSM</option>
                <option value="TKRO" {{ session('filterJurusan') === 'TKRO' ? 'selected' : '' }}>Filter jurusan TKRO</option>
            </select>
        </div>
    </div>
    <div class="col-12 col-md-3 mb-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            <input type="month" name="filterBulan" id="filterBulan" class="form-control" value="{{ session('filterBulan', now()->format('Y-m')) }}" onchange="this.form.submit()">
        </div>
    </div>
</form>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    let activeTab = localStorage.getItem("activeTab");
    if (activeTab) {
        let tabElement = new bootstrap.Tab(document.querySelector(`[href="${activeTab}"]`));
        tabElement.show();
    }
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener("shown.bs.tab", function (event) {
            localStorage.setItem("activeTab", event.target.getAttribute("href"));
        });
    });
});
</script>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#daily">Harian</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#monthly">Bulanan</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="daily">
        <table class="table table-bordered table-hover">
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
                            <a href="{{ route('service.show', $data['id']) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
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

    <div class="tab-pane fade" id="monthly">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Bulan</th>
                    <th>Total Pemasukan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyIncome as $month => $income)
                    <tr>
                        <td>{{ $month }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($income, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-secondary">
                    <td class="text-end fw-bold">Total Keseluruhan:</td>
                    <td class="text-success fw-bold">Rp {{ number_format($totalMonthlyIncome, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="d-flex justify-content-end mt-3">
    <button onclick="printTable()" class="btn btn-success text-light">
        <i class="fas fa-print me-2"></i>Cetak
    </button>
</div>

<script>
function printTable() {
    window.print();
}
</script>