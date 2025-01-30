<div class="container mt-4">
    <h2 class="text-center mb-4">
        <i class="fas fa-file-invoice-dollar me-2"></i>Laporan Harian Pemasukan
    </h2>

    <table class="table table-bordered table-hover" id="incomeTable">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-user me-2"></i>Nama Pelanggan</th>
                <th><i class="fas fa-motorcycle me-2"></i>
                    @if (Auth::user()->jurusan == 'TSM')
                        Motor
                    @elseif (Auth::user()->jurusan == 'TKRO')
                        Mobil
                    @endif
                </th>
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
                    <td><i class="fas fa-user"></i> ${customerName}</td>
                    <td><i class="fas fa-motorcycle"></i> ${vehicle}</td>
                    <td><i class="fas fa-money-bill-wave"></i> ${income}</td>
                    <td><i class="fas fa-clock"></i> ${serviceTime}</td>
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
                @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
                body {
                    font-family: 'Arial', sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f9;
                    color: #333;
                    line-height: 1.6;
                }
                .report-container {
                    padding: 30px;
                    margin: 30px auto;
                    max-width: 900px;
                    border: 2px solid #3498db;
                    border-radius: 15px;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                    background-color: #fff;
                    transition: all 0.3s ease-in-out;
                }
                .report-container:hover {
                    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.15);
                    transform: translateY(-10px);
                }
                h2 {
                    text-align: center;
                    font-size: 2rem;
                    margin-bottom: 20px;
                    color: #3498db;
                    border-bottom: 3px solid #3498db;
                    padding-bottom: 10px;
                    font-weight: bold;
                    text-transform: uppercase;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 30px;
                    box-sizing: border-box;
                }
                thead {
                    background-color: #3498db;
                    color: white;
                    font-size: 1.2rem;
                    text-align: left;
                    padding: 12px;
                    border-radius: 10px;
                }
                thead th {
                    padding: 12px;
                    font-size: 1.1rem;
                }
                tbody td {
                    padding: 15px;
                    border-bottom: 1px solid #ddd;
                    font-size: 1rem;
                    text-align: left;
                }
                tbody tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                tbody tr:nth-child(odd) {
                    background-color: #ffffff;
                }
                tbody tr:hover {
                    background-color: #e9ecef;
                    transition: background-color 0.3s;
                }
                tfoot {
                    background-color: #eaf0f9;
                }
                tfoot td {
                    padding: 15px;
                    font-weight: bold;
                    font-size: 1.1rem;
                    text-align: right;
                }
                .signature {
                    margin-top: 40px;
                    display: flex;
                    justify-content: space-between;
                    font-size: 1rem;
                    text-align: center;
                }
                .signature div {
                    width: 30%;
                    padding-top: 10px;
                    font-size: 0.9rem;
                    border-top: 1px solid #000;
                    color: #333;
                }
                .fa-user, .fa-motorcycle, .fa-money-bill-wave, .fa-clock {
                    margin-right: 8px;
                }
                .fa-user {
                    color: #3498db;
                }
                .fa-motorcycle {
                    color: #2ecc71;
                }
                .fa-money-bill-wave {
                    color: #f39c12;
                }
                .fa-clock {
                    color: #e74c3c;
                }
                .btn {
                    display: inline-block;
                    background-color: #3498db;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 5px;
                    text-decoration: none;
                    transition: background-color 0.3s ease;
                }
                .btn:hover {
                    background-color: #2980b9;
                }
                .page-footer {
                    text-align: center;
                    padding: 20px 0;
                    background-color: #f1f1f1;
                    font-size: 1rem;
                    color: #888;
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                }
                @media print {
                    .report-container {
                        box-shadow: none;
                        margin: 0;
                    }
                    table {
                        border: none;
                    }
                    h2 {
                        font-size: 1.6rem;
                    }
                    .signature div {
                        font-size: 1rem;
                    }
                    .btn {
                        display: none;
                    }
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
