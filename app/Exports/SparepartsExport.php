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

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
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
     *
     * Important: return native types (numbers for numeric columns)
     * and Excel date serial numbers for date columns using ExcelDate::stringToExcel.
     */
    public function map($sparepart): array
    {
        $tanggalMasuk = null;
        $tanggalKeluar = null;

        if ($sparepart->tanggal_masuk) {
            $d = date('Y-m-d', strtotime($sparepart->tanggal_masuk));
            $tanggalMasuk = ExcelDate::stringToExcel($d);
        }

        if ($sparepart->tanggal_keluar) {
            $d2 = date('Y-m-d', strtotime($sparepart->tanggal_keluar));
            $tanggalKeluar = ExcelDate::stringToExcel($d2);
        }

        $keuntungan = $sparepart->keuntungan ?? ($sparepart->harga_jual - $sparepart->harga_beli);

        return [
            (int) $sparepart->id_sparepart,                                    // A
            $sparepart->nama_sparepart,                                        // B
            $sparepart->spek,                                                  // C
            (int) $sparepart->jumlah,                                          // D
            is_numeric($sparepart->harga_beli) ? (float) $sparepart->harga_beli : 0, // E
            is_numeric($sparepart->harga_jual) ? (float) $sparepart->harga_jual : 0, // F
            is_numeric($keuntungan) ? (float) $keuntungan : 0,                 // G
            $tanggalMasuk,                                                     // H (Excel date serial)
            $tanggalKeluar,                                                    // I (Excel date serial)
            $sparepart->deskripsi,                                             // J
            $sparepart->jurusan,                                               // K
        ];
    }

    /**
     * Headings for excel — returned uppercase for emphasis.
     */
    public function headings(): array
    {
        $heads = [
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

        // return uppercase headings to mimic previous intent without using non-existent font method
        return array_map('strtoupper', $heads);
    }

    /**
     * Column formatting:
     * - D = jumlah integer
     * - E/F/G = currency / number with thousand separator
     * - H/I = date format (Excel date)
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER, // jumlah
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // harga beli
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // harga jual
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // keuntungan
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD2, // tanggal masuk
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD2, // tanggal keluar
        ];
    }

    /**
     * Register events to style the sheet (AfterSheet)
     * This is an instance method (required).
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insert a title row above the headings for a nicer look
                $sheet->insertNewRowBefore(1, 1);

                // Determine last column and last row
                $lastColumn = 'K'; // we know we have 11 columns A..K
                $highestRow = $sheet->getHighestRow(); // includes the inserted row

                // Title (row 1) — merged across A:K
                $sheet->setCellValue('A1', 'LAPORAN SPAREPART');
                $sheet->mergeCells("A1:{$lastColumn}1");

                // Style title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0D6EFD'], // bootstrap primary blue
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(36);

                // Headings are now at row 2 (after insertion)
                $headerRange = "A2:{$lastColumn}2";

                // Header style: dark navy with white text, slightly bigger
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1E3A8A'], // deep indigo
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(26);

                // Freeze panes (freeze title+header rows so data scrolls under)
                $sheet->freezePane('A3');

                // Auto filter on header row
                $sheet->setAutoFilter("A2:{$lastColumn}{$highestRow}");

                // Full-data border
                $dataRange = "A2:{$lastColumn}{$highestRow}";
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'], // light gray border
                        ],
                    ],
                ]);

                // Alternating row colors starting from row 3 (data)
                for ($row = 3; $row <= $highestRow; $row++) {
                    if ($row % 2 === 0) {
                        // even -> very light blue tint
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F8FAFF');
                    } else {
                        // odd -> white
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFFFFF');
                    }
                }

                // Alignment for specific columns
                $sheet->getStyle("A3:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // ID
                $sheet->getStyle("D3:D{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jumlah
                $sheet->getStyle("E3:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Currency
                $sheet->getStyle("H3:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Dates
                $sheet->getStyle("J3:J{$highestRow}")->getAlignment()->setWrapText(true); // Deskripsi wrap

                // Column widths (baseline; ShouldAutoSize also applied)
                $sheet->getColumnDimension('A')->setWidth(10);  // ID
                $sheet->getColumnDimension('B')->setWidth(32);  // Nama Sparepart
                $sheet->getColumnDimension('C')->setWidth(28);  // Spek
                $sheet->getColumnDimension('D')->setWidth(10);  // Jumlah
                $sheet->getColumnDimension('E')->setWidth(16);  // Harga Beli
                $sheet->getColumnDimension('F')->setWidth(16);  // Harga Jual
                $sheet->getColumnDimension('G')->setWidth(16);  // Keuntungan
                $sheet->getColumnDimension('H')->setWidth(14);  // Tgl Masuk
                $sheet->getColumnDimension('I')->setWidth(14);  // Tgl Keluar
                $sheet->getColumnDimension('J')->setWidth(50);  // Deskripsi
                $sheet->getColumnDimension('K')->setWidth(14);  // Jurusan

                // Emphasize header top border thicker
                $sheet->getStyle($headerRange)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '0B5ED7'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
