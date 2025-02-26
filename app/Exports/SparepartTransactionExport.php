<?php

namespace App\Exports;

use App\Models\SparepartTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SparepartTransactionExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $transactions = SparepartTransaction::with('sparepart')->get();

        // Group berdasarkan minggu transaksi
        $groupedTransactions = $transactions->groupBy(function ($item) {
            return Carbon::parse($item->transaction_date)->startOfWeek()->format('d-m-Y') . ' - ' . Carbon::parse($item->transaction_date)->endOfWeek()->format('d-m-Y');
        });

        foreach ($groupedTransactions as $week => $data) {
            $sheets[] = new SparepartTransactionWeeklySheet($week, $data);
        }

        return $sheets;
    }
}

class SparepartTransactionWeeklySheet implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $week;
    protected $data;

    public function __construct($week, $data)
    {
        $this->week = $week;
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($transaction) {
            return [
                'ID' => $transaction->id,
                'Nama Sparepart' => $transaction->sparepart->nama_sparepart ?? 'Tidak Diketahui',
                'Jumlah' => $transaction->quantity,
                'Harga Satuan' => number_format($transaction->purchase_price, 0, ',', '.'),
                'Total Harga' => number_format($transaction->total_price, 0, ',', '.'),
                'Tanggal Transaksi' => Carbon::parse($transaction->transaction_date)->format('d-m-Y'),
                'Jenis Transaksi' => $transaction->transaction_type == 'sale' ? 'Penjualan' : 'Pembelian',
                'Jurusan' => $transaction->jurusan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Transaksi Sparepart: ' . $this->week], // Judul tabel per minggu
            ['ID', 'Nama Sparepart', 'Jumlah', 'Harga Satuan', 'Total Harga', 'Tanggal Transaksi', 'Jenis Transaksi', 'Jurusan']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:H1'); // Gabungkan header laporan
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Atur style header utama

        // Atur style header tabel
        $sheet->getStyle('A2:H2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0073e6']],
        ]);

        // Tambahkan border pada seluruh tabel
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A2:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
    }
}