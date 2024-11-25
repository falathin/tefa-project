@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Title Section -->
    <h1 class="mb-4 text-center">{{ $sparepart->nama_sparepart }}</h1>

    <!-- Card for Sparepart Details -->
    <div class="container my-4">
        <div class="card shadow-lg border-primary">
            <div class="card-header bg-gradient-primary text-white text-center">
                <h5 class="mb-0">Detail Sparepart</h5>
            </div>
            <div class="card-body">
                <!-- Informasi Umum -->
                <h5 class="card-title text-primary border-bottom pb-2 mb-3">Informasi Umum</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>ID:</strong> <span>{{ $sparepart->id_sparepart }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Jumlah:</strong> <span>{{ number_format($sparepart->jumlah, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Harga Beli:</strong> <span>Rp. {{ number_format($sparepart->harga_beli, 2, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Harga Jual:</strong> <span>Rp. {{ number_format($sparepart->harga_jual, 2, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Keuntungan:</strong> <span>Rp. {{ number_format($sparepart->harga_jual - $sparepart->harga_beli, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Total Keuntungan:</strong> 
                        <span id="totalKeuntungan">Rp. {{ number_format(($sparepart->harga_jual - $sparepart->harga_beli) * $sparepart->jumlah, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Tanggal Masuk:</strong> <span>{{ \Carbon\Carbon::parse($sparepart->tanggal_masuk)->format('d-m-Y') }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Deskripsi:</strong>
                        <p class="mt-2">{{ $sparepart->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </li>
                </ul>
            </div>
    
            <!-- Tombol Aksi -->
            <div class="card-footer d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('sparepart.index') }}" class="btn btn-secondary btn-sm shadow-sm hover-effect">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
                <a href="{{ route('sparepart.edit', $sparepart->id_sparepart) }}" class="btn btn-warning btn-sm shadow-sm hover-effect">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ route('sparepart.destroy', $sparepart->id_sparepart) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm shadow-sm hover-effect" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
                <button id="copyBtn" class="btn btn-info btn-sm shadow-sm hover-effect">
                    <i class="bi bi-clipboard"></i> Salin
                </button>
                <button id="downloadBtn" class="btn btn-success btn-sm shadow-sm hover-effect">
                    <i class="bi bi-download"></i> Download
                </button>
                <button id="excelBtn" class="btn btn-primary btn-sm shadow-sm hover-effect">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export to Excel
                </button>
                <button id="printBtn" class="btn btn-dark btn-sm shadow-sm hover-effect">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>
    </div>
    
</div>

<!-- JavaScript for additional features -->
<script>
    // Copy functionality
    document.getElementById('copyBtn').addEventListener('click', function() {
        const text = document.querySelector('.card-body').innerText;
        navigator.clipboard.writeText(text).then(function() {
            alert('Informasi telah disalin ke clipboard!');
        }).catch(function(err) {
            alert('Gagal menyalin: ' + err);
        });
    });

      // Download Button functionality (download as CSV)
    document.getElementById('downloadBtn').addEventListener('click', function() {
        const data = [
            ['Nama Sparepart', '{{ $sparepart->nama_sparepart }}'],
            ['Jumlah', '{{ number_format($sparepart->jumlah, 0, ',', '.') }}'],
            ['Harga Beli', 'Rp. {{ number_format($sparepart->harga_beli, 2, ',', '.') }}'],
            ['Harga Jual', 'Rp. {{ number_format($sparepart->harga_jual, 2, ',', '.') }}'],
            ['Keuntungan', 'Rp. {{ number_format($sparepart->harga_jual - $sparepart->harga_beli, 0, ',', '.') }}'],
            ['Tanggal Masuk', '{{ \Carbon\Carbon::parse($sparepart->tanggal_masuk)->format('d-m-Y') }}'],
            ['Deskripsi', '{{ $sparepart->deskripsi ?? 'Tidak ada deskripsi.' }}']
        ];

        const csvRows = [];
        data.forEach(row => {
            csvRows.push('"' + row.join('","') + '"'); // Ensure proper CSV formatting
        });

        const csvString = csvRows.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = '{{ $sparepart->nama_sparepart }}_details.csv';
        link.click();
    });

    // Excel Export (using SheetJS library for demonstration)
    document.getElementById('excelBtn').addEventListener('click', function() {
        const wb = XLSX.utils.book_new();
        
        // Create worksheet with headers and data
        const wsData = [
            ["Nama Sparepart", "{{ $sparepart->nama_sparepart }}"],
            ["Jumlah", "{{ number_format($sparepart->jumlah, 0, ',', '.') }}"],
            ["Harga Beli", "Rp. {{ number_format($sparepart->harga_beli, 2, ',', '.') }}"],
            ["Harga Jual", "Rp. {{ number_format($sparepart->harga_jual, 2, ',', '.') }}"],
            ["Keuntungan", "Rp. {{ number_format($sparepart->harga_jual - $sparepart->harga_beli, 0, ',', '.') }}"],
            ["Tanggal Masuk", "{{ \Carbon\Carbon::parse($sparepart->tanggal_masuk)->format('d-m-Y') }}"],
            ["Deskripsi", "{{ $sparepart->deskripsi ?? 'Tidak ada deskripsi.' }}"]
        ];

        const ws = XLSX.utils.aoa_to_sheet(wsData);

        // Add styling to the header row
        ws['!rows'] = [];
        ws['!rows'][0] = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '4F81BD' } } };

        // Append worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Sparepart Detail');

        // Write the Excel file and trigger download
        XLSX.writeFile(wb, '{{ $sparepart->nama_sparepart }}_details.xlsx');
    });

    document.getElementById('printBtn').addEventListener('click', function() {
    const printContent = document.querySelector('.container').innerHTML;
    const newWindow = window.open('', '', 'width=300,height=600');

    newWindow.document.write(`
        <html>
            <head>
                <title>Struk</title>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
                <style>
                    body {
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        line-height: 1.5;
                        margin: 0;
                        padding: 0;
                        color: #333;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        text-align: center;
                    }
                    h1 {
                        font-size: 20px;
                        font-weight: bold;
                        text-align: center;
                        margin: 0;
                        padding: 10px 0;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                    }
                    h5 {
                        font-size: 16px;
                        text-align: center;
                        font-weight: normal;
                        margin: 10px 0;
                    }
                    .container {
                        width: 100%;
                        max-width: 350px;
                        padding: 20px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        border-radius: 10px;
                        background-color: #fff;
                        margin: 0 auto;
                        text-align: left;
                    }
                    .card-header {
                        font-weight: bold;
                        font-size: 14px;
                        border-bottom: 2px solid #007bff;
                        padding: 6px 0;
                        color: #007bff;
                        text-transform: uppercase;
                    }
                    .list-group-item {
                        padding: 8px 0;
                        border-bottom: 1px dashed #ddd;
                        font-size: 12px;
                        display: flex;
                        justify-content: space-between;
                    }
                    .list-group-item strong {
                        font-weight: bold;
                        text-transform: uppercase;
                    }
                    .total {
                        border-top: 2px solid #000;
                        margin-top: 20px;
                        padding-top: 10px;
                        font-size: 14px;
                        font-weight: bold;
                        text-align: center;
                    }
                    .footer {
                        font-size: 10px;
                        text-align: center;
                        margin-top: 15px;
                        color: #888;
                    }
                    @media print {
                        body {
                            padding: 0;
                            margin: 0;
                        }
                        .container {
                            width: 100%;
                            margin: 0;
                            padding: 10px;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1><i class="bi bi-wrench"></i> {{ $sparepart->nama_sparepart }}</h1>
                    <h5><i class="bi bi-info-circle"></i> Detail Sparepart</h5>
                    <div class="card">
                        <div class="card-header">
                            Informasi Laporan
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <span><i class="bi bi-hash"></i> <strong>Jumlah:</strong></span>
                                    <span>{{ number_format($sparepart->jumlah, 0, ',', '.') }}</span>
                                </div>
                                <div class="list-group-item">
                                    <span><i class="bi bi-currency-dollar"></i> <strong>Harga Beli:</strong></span>
                                    <span>Rp. {{ number_format($sparepart->harga_beli, 2, ',', '.') }}</span>
                                </div>
                                <div class="list-group-item">
                                    <span><i class="bi bi-currency-dollar"></i> <strong>Harga Jual:</strong></span>
                                    <span>Rp. {{ number_format($sparepart->harga_jual, 2, ',', '.') }}</span>
                                </div>
                                <div class="list-group-item">
                                    <span><i class="bi bi-calendar-event"></i> <strong>Tanggal Masuk:</strong></span>
                                    <span>{{ \Carbon\Carbon::parse($sparepart->tanggal_masuk)->format('d-m-Y') }}</span>
                                </div>
                                <div class="list-group-item">
                                    <span><i class="bi bi-card-text"></i> <strong>Deskripsi:</strong></span>
                                    <span>{{ $sparepart->deskripsi ?? 'Tidak ada deskripsi.' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="total">
                        <p><strong>Total Keuntungan:</strong> Rp. {{ number_format($sparepart->harga_jual - $sparepart->harga_beli, 0, ',', '.') }}</p>
                    </div>
                    <div class="footer">
                        <p><i class="bi bi-emoji-smile"></i> Terima kasih atas kunjungan Anda!</p>
                    </div>
                </div>
            </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.print();

    
});


</script>
@endsection
