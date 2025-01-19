<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container mt-4">
    <h2 class="text-center mb-4">
        <i class="fas fa-file-invoice-dollar me-2"></i>Laporan Harian Pemasukan
    </h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-user me-2"></i>Nama Pelanggan</th>
                <th><i class="fas fa-motorcycle me-2"></i>Motor</th>
                <th><i class="fas fa-coins me-2"></i>Pemasukan</th>
                <th><i class="fas fa-clock me-2"></i>Waktu</th> <!-- Added column for time -->
                <th><i class="fas fa-cogs me-2"></i>Aksi</th> <!-- Added column for actions -->
            </tr>
        </thead>
        <tbody>
            @foreach ($dailyCustomerData as $data)
                <tr>
                    <td>{{ $data['customer_name'] }}</td>
                    <td>{{ $data['vehicle'] }}</td>
                    <td class="text-success fw-bold">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                    <td>{{ $data['service_time'] }}</td> <!-- Display the service time -->
                    <td>
                        <button class="btn btn-primary btn-sm text-light">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-secondary">
                <td colspan="3" class="text-end fw-bold">Total Pemasukan:</td>
                <td class="text-success fw-bold">Rp {{ number_format($totalDailyIncome, 0, ',', '.') }}</td>
                <td></td> <!-- Empty cell for footer action column -->
            </tr>
        </tfoot>
    </table>

    <!-- Tombol Cetak -->
    <div class="d-flex justify-content-end mt-3">
        <button onclick="window.print()" class="btn btn-success text-light">
            <i class="fas fa-print me-2"></i>Cetak
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>