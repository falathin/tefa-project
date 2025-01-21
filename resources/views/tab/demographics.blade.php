<div class="container mt-4">
    <h2 class="text-center mb-4">
        <i class="fas fa-file-invoice-dollar me-2"></i>Laporan Harian Pemasukan
    </h2>

    <table class="table table-bordered table-hover" id="incomeTable">
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
        <button onclick="printTable()" class="btn btn-success text-light">
            <i class="fas fa-print me-2"></i>Cetak
        </button>
    </div>
</div>

<script>
    function printTable() {
        // Mengambil data dari tabel
        let table = document.getElementById('incomeTable');
        let rows = table.getElementsByTagName('tr');

        // Memulai pembuatan konten untuk laporan
        let content = `
            <div class="report-container">
                <h2>Laporan Harian Pemasukan Bengkel</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Motor</th>
                            <th>Pemasukan</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Menambahkan data ke dalam tabel laporan
        for (let i = 1; i < rows.length - 1; i++) { // Skip header and footer row
            let cells = rows[i].getElementsByTagName('td');
            let customerName = cells[0].textContent.trim();
            let vehicle = cells[1].textContent.trim();
            let income = cells[2].textContent.trim();
            let serviceTime = cells[3].textContent.trim();

            content += `
                <tr>
                    <td>${customerName}</td>
                    <td>${vehicle}</td>
                    <td>${income}</td>
                    <td>${serviceTime}</td>
                </tr>
            `;
        }

        // Menambahkan footer (total pemasukan)
        let totalIncome = document.querySelector('tfoot td.text-success').textContent.trim();
        content += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end">Total Pemasukan:</td>
                            <td>${totalIncome}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="signature">
                    <div>
                        Tanda Tangan<br>Admin Bengkel
                    </div>
                    <div>
                        Tanda Tangan<br>Bendahara
                    </div>
                </div>
            </div>
        `;

        // Gaya CSS untuk tampilan tabel laporan yang menarik
        const style = `
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                }
                .report-container {
                    padding: 20px;
                    margin: auto;
                    max-width: 800px;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                }
                h2 {
                    text-align: center;
                    margin-bottom: 20px;
                    font-size: 1.5rem;
                    color: #333;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                thead {
                    background-color: #f4f4f4;
                }
                thead th {
                    text-align: left;
                    padding: 10px;
                    border-bottom: 2px solid #ddd;
                }
                tbody td {
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                }
                tbody tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tfoot {
                    background-color: #f4f4f4;
                }
                tfoot td {
                    padding: 10px;
                    font-weight: bold;
                }
                .signature {
                    margin-top: 40px;
                    display: flex;
                    justify-content: space-between;
                }
                .signature div {
                    width: 30%;
                    text-align: center;
                    border-top: 1px solid #000;
                    padding-top: 10px;
                    font-size: 0.9rem;
                }
            </style>
        `;

        // Membuka jendela baru untuk mencetak
        const printWindow = window.open('', '_blank');
        printWindow.document.write(style);
        printWindow.document.write(content);
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    }
</script>