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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SparepartTransactionExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        // Ambil seluruh transaksi dengan relasi sparepart dan transaction
        $transactions = SparepartTransaction::with(['sparepart', 'transaction'])->get();

        // Hitung jumlah transaksi untuk jurusan TSM dan TKRO
        $tsmCount = $transactions->filter(function ($item) {
            return $item->transaction->jurusan == 'TSM';
        })->count();
        $tkroCount = $transactions->filter(function ($item) {
            return $item->transaction->jurusan == 'TKRO';
        })->count();

        // Pilih jurusan yang lebih banyak, jika sama pilih TSM
        $filterJurusan = $tsmCount >= $tkroCount ? 'TSM' : 'TKRO';

        // Filter transaksi berdasarkan jurusan yang dipilih
        $transactions = $transactions->filter(function ($item) use ($filterJurusan) {
            return $item->transaction->jurusan == $filterJurusan;
        });

        // Grouping berdasarkan minggu transaksi (menggunakan data dari relasi transaction)
        $groupedTransactions = $transactions->groupBy(function ($item) {
            $startOfWeek = Carbon::parse($item->transaction->transaction_date)->startOfWeek()->format('d-m-Y');
            $endOfWeek   = Carbon::parse($item->transaction->transaction_date)->endOfWeek()->format('d-m-Y');
            return $startOfWeek . ' - ' . $endOfWeek;
        });

        foreach ($groupedTransactions as $week => $data) {
            $sheets[] = new SparepartTransactionWeeklySheet($week, $data, $filterJurusan);
        }

        return $sheets;
    }
}

class SparepartTransactionWeeklySheet implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $week;
    protected $data;
    protected $jurusan;

    public function __construct($week, $data, $jurusan)
    {
        $this->week = $week;
        $this->data = $data;
        $this->jurusan = $jurusan;
    }

    public function collection()
    {
        // Filter data lagi (misalnya jika diperlukan tambahan filter)
        $filteredData = $this->data->filter(function ($transaction) {
            if (Gate::allows('isBendahara')) {
                return $transaction;
            } else {
                return $transaction->transaction->jurusan == Auth::user()->jurusan;
            }
        });

        // Map data dengan mengakses properti dari relasi transaction
        return $filteredData->map(function ($transaction) {
            return [
                'ID'                 => $transaction->id,
                'Nama Sparepart'     => $transaction->sparepart->nama_sparepart ?? 'Tidak Diketahui',
                'Jumlah'             => $transaction->quantity,
                // Menggunakan purchase_price dan total_price dari transaksi, 
                // sesuaikan jika diperlukan. Contoh berikut:
                'Harga Satuan'       => number_format($transaction->transaction->purchase_price, 0, ',', '.'),
                'Total Harga'        => number_format($transaction->transaction->total_price, 0, ',', '.'),
                'Tanggal Transaksi'  => Carbon::parse($transaction->transaction->transaction_date)->format('d-m-Y'),
                'Jenis Transaksi'    => $transaction->transaction->transaction_type == 'sale' ? 'Penjualan' : 'Pembelian',
                'Jurusan'            => $transaction->transaction->jurusan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Transaksi Sparepart (' . $this->jurusan . ') - ' . $this->week],
            ['ID', 'Nama Sparepart', 'Jumlah', 'Harga Satuan', 'Total Harga', 'Tanggal Transaksi', 'Jenis Transaksi', 'Jurusan']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge header judul
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Style header tabel
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