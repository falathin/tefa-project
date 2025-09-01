<?php

namespace App\Exports;

use App\Models\Sparepart;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SparepartsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    protected $from;
    protected $to;
    protected $jurusan;

    /**
     * @param string|null $from  format YYYY-MM-DD or null
     * @param string|null $to
     * @param string|null $jurusan
     */
    public function __construct(?string $from = null, ?string $to = null, ?string $jurusan = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->jurusan = $jurusan;
    }

    /**
     * Build the query (FromQuery)
     */
    public function query()
    {
        return Sparepart::query()
            ->when($this->jurusan, function ($q) {
                $q->where('jurusan', $this->jurusan);
            })
            ->when($this->from, function ($q) {
                $q->whereDate('tanggal_masuk', '>=', $this->from);
            })
            ->when($this->to, function ($q) {
                $q->whereDate('tanggal_masuk', '<=', $this->to);
            })
            ->orderBy('tanggal_masuk', 'asc');
    }

    /**
     * Map model to row
     */
    public function map($sparepart): array
    {
        // pastikan format tanggal: Y-m-d atau kosong
        $tanggalMasuk = $sparepart->tanggal_masuk ? date('Y-m-d', strtotime($sparepart->tanggal_masuk)) : '';
        $tanggalKeluar = $sparepart->tanggal_keluar ? date('Y-m-d', strtotime($sparepart->tanggal_keluar)) : '';

        return [
            $sparepart->id_sparepart,
            $sparepart->nama_sparepart,
            $sparepart->spek,
            $sparepart->jumlah,
            $sparepart->harga_beli,
            $sparepart->harga_jual,
            // gunakan accessor jika ada, atau hitung manual
            $sparepart->keuntungan ?? ($sparepart->harga_jual - $sparepart->harga_beli),
            $tanggalMasuk,
            $tanggalKeluar,
            $sparepart->deskripsi,
            $sparepart->jurusan,
        ];
    }

    /**
     * Headings for excel
     */
    public function headings(): array
    {
        return [
            'ID Sparepart',
            'Nama Sparepart',
            'Spek',
            'Jumlah',
            'Harga Beli',
            'Harga Jual',
            'Keuntungan',
            'Tanggal Masuk',
            'Tanggal Keluar',
            'Deskripsi',
            'Jurusan',
        ];
    }

    /**
     * Column formatting (D = jumlah, E/F/G currency)
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER, // jumlah (integer)
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // harga beli
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // harga jual
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // keuntungan
        ];
    }

    /**
     * Register events to style the sheet (AfterSheet)
     * NOTE: This must be an instance method (not static).
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // header range (A1:K1)
                $headerRange = 'A1:K1';
                // determine highest row
                $highestRow = $sheet->getHighestRow(); // numeric
                $dataRange = 'A1:K' . $highestRow;

                // Header style: dark green background, white bold text
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2E7D32'], // material green 700
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Freeze header row
                $sheet->freezePane('A2');

                // Auto filter
                $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

                // Borders for all data
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'DDDDDD'],
                        ],
                    ],
                ]);

                // Alternate row coloring (start from row 2)
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        // even row -> light gray fill
                        $sheet->getStyle("A{$row}:K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F5F5F5');
                    } else {
                        // odd row -> white (ensure no leftover)
                        $sheet->getStyle("A{$row}:K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFFFFF');
                    }
                }

                // Alignment: center numeric columns D (Jumlah) and currency columns E,F,G
                $sheet->getStyle("D2:D{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E2:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Center ID column
                $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Dates (H/I) center
                $sheet->getStyle("H2:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Wrap text for Deskripsi (column J)
                $sheet->getStyle("J2:J{$highestRow}")->getAlignment()->setWrapText(true);

                // Make header row height a bit taller
                $sheet->getRowDimension(1)->setRowHeight(26);

                // Optional: set minimum column widths for nicer look (auto-size already enabled, but set a baseline)
                $sheet->getColumnDimension('B')->setWidth(30); // Nama Sparepart
                $sheet->getColumnDimension('C')->setWidth(25); // Spek
                $sheet->getColumnDimension('J')->setWidth(40); // Deskripsi
            },
        ];
    }
}
