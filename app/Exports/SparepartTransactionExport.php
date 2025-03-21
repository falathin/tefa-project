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
        // Sertakan relasi transaction dan sparepart
        $transactions = SparepartTransaction::with(['sparepart', 'transaction'])->get();

        // Grouping berdasarkan minggu transaksi (dari relasi transaction)
        $groupedTransactions = $transactions->groupBy(function ($item) {
            $startOfWeek = Carbon::parse($item->transaction->transaction_date)
                                ->startOfWeek()
                                ->format('d-m-Y');
            $endOfWeek   = Carbon::parse($item->transaction->transaction_date)
                                ->endOfWeek()
                                ->format('d-m-Y');
            return $startOfWeek . ' - ' . $endOfWeek;
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

    /**
     * Fungsi custom untuk memformat angka menjadi format ribuan dengan titik.
     * Contoh: 14000 menjadi "14.000"
     */
    private function formatRibuan($value)
    {
        // Pastikan nilai adalah integer agar tidak menghasilkan desimal
        $value = (int) $value;
        return preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', (string) $value);
    }

    public function collection()
    {
        // Filter data berdasarkan Gate atau jurusan
        $filteredData = $this->data->filter(function ($transaction) {
            if (Gate::allows('isBendahara')) {
                return $transaction;
            } else {
                return $transaction->transaction->jurusan == Auth::user()->jurusan;
            }
        });
    
        // Grouping berdasarkan ID transaksi (jika transaksi yang sama)
        $grouped = $filteredData->groupBy(function ($item) {
            return $item->transaction->id;
        });
    
        // Untuk setiap grup transaksi, gabungkan detail sparepart menjadi satu baris
        return $grouped->map(function ($group) {
            // Ambil data transaksi (semua item dalam grup memiliki data transaksi yang sama)
            $transaction = $group->first()->transaction;
    
            // Agregasi detail sparepart: gabungkan nama, jumlah, harga satuan, dan subtotal.
            // Setiap detail dipisahkan dengan baris kosong
            $sparepartDetails = $group->map(function ($item) {
                /* 
                 * Pembagian harga_jual dengan 1000 untuk menampilkan angka dalam format ribuan.
                 * Contoh: jika harga_jual = 1.000.000 maka unitPrice menjadi 1.000.
                 */
                $unitPrice = $item->sparepart->harga_jual / 1000;
                $subtotal  = $item->quantity * $unitPrice;
                // Format quantity menggunakan fungsi custom
                $formattedQuantity = $this->formatRibuan($item->quantity);
                // Format harga satuan dan subtotal menggunakan fungsi custom
                $formattedUnitPrice = $this->formatRibuan($unitPrice);
                $formattedSubtotal  = $this->formatRibuan($subtotal);
    
                return "Nama: " . ($item->sparepart->nama_sparepart ?? 'Tidak Diketahui') .
                       "\nJumlah: " . $formattedQuantity .
                       "\nHarga Satuan: Rp" . $formattedUnitPrice .
                       "\nSubtotal: Rp" . $formattedSubtotal;
            })->implode("\n\n");            
    
            // Hitung kembalian: uang diterima - (total harga - diskon)
            $change = $transaction->purchase_price - ($transaction->total_price - $transaction->discount);
    
            return [
                'ID'                => $transaction->id,
                'Nama Pelanggan'    => $transaction->name,
                'Tanggal Transaksi' => Carbon::parse($transaction->transaction_date)->format('d-m-Y'),
                'Metode Pembayaran' => $transaction->payment_method,
                'Diskon'            => $this->formatRibuan($transaction->discount),
                'Total Harga'       => 'Rp' . $this->formatRibuan($transaction->total_price),
                'Kembalian'         => 'Rp' . $this->formatRibuan($change),
                'Detail Sparepart'  => $sparepartDetails,
                'Jurusan'           => $transaction->jurusan,
            ];
        })->values();
    }
    
    public function headings(): array
    {
        // Baris 1: Judul laporan
        // Baris 2: Periode laporan (menggunakan $this->week yang sudah berupa "start - end")
        // Baris 3: Header kolom dengan ikon
        return [
            ['Laporan Transaksi Sparepart: ' . $this->week],
            ['Periode: ' . $this->week],
            [
                'ID', 
                '👤 Nama Pelanggan', 
                '📅 Tanggal Transaksi', 
                '💳 Metode Pembayaran', 
                '💸 Diskon', 
                '💰 Total Harga', 
                '🤑 Kembalian', 
                '🛠 Detail Sparepart', 
                '🏫 Jurusan'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge baris 1 dan 2 untuk judul dan periode
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Styling header tabel (baris 3)
        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0073e6']],
        ]);

        // Set alignment untuk seluruh data (mulai baris 3)
        $sheet->getStyle('A3:I' . $sheet->getHighestRow())
              ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A3:I' . $sheet->getHighestRow())
              ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Atur lebar kolom agar tampilan lebih rapi (meniru layout PKB kerja bengkel)
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(16);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(40);
        $sheet->getColumnDimension('I')->setWidth(12);

        // Beri border pada seluruh tabel (mulai dari header kolom)
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A3:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Set wrap text untuk kolom Detail Sparepart (kolom H) agar baris baru tampil rapi
        $sheet->getStyle('H4:H' . $sheet->getHighestRow())
              ->getAlignment()->setWrapText(true);
    }
}