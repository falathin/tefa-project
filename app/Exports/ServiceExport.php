<?php
namespace App\Exports;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ServiceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $startDate, $endDate, $category;
    private $totalIncome = 0;

    public function __construct($startDate, $endDate, $category)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->category  = $category;
    }

    public function collection()
    {
        $services = Service::with('serviceChecklists', 'vehicle.customer')
                           ->whereBetween('created_at', [$this->startDate, $this->endDate])
                           ->where('jurusan', $this->category)
                           ->get();
        $this->totalIncome = $services->sum('total_cost');
        return $services;
    }

    public function headings(): array
    {
        return [
            ["🛠 Laporan Servis PKB Bengkel"],
            ["Periode: " . $this->startDate . " s/d " . $this->endDate],
            [
                'No', 'ID Service', 'Nama Pelanggan', 'Plat Nomor', 'Keluhan',
                'Kilometer', 'Biaya Servis', 'Total Biaya', 'Pembayaran', 'Kembalian',
                'Tipe Servis', 'Status', 'Catatan', 'Teknisi', 'Jurusan',
                'Diskon', 'Metode Pembayaran', 'Tanggal Servis', 'Checklist Servis'
            ]
        ];
    }

    public function map($service): array
    {
        static $index = 0;
        $index++;

        $diskon     = $service->diskon ?? 0;
        $totalBiaya = $service->total_cost - $diskon;

        $checklist = optional($service->serviceChecklists)->map(function ($task) {
            return ($task->is_completed ? '✔' : '✘') . ' ' . $task->task;
        })->implode("\n");

        $licensePlate = optional($service->vehicle)->license_plate ?? '-';
        if ($service->jurusan == 'TKRO') {
            $licensePlate = '🚗 ' . $licensePlate;
        } elseif ($service->jurusan == 'TSM') {
            $licensePlate = '🏍 ' . $licensePlate;
        }

        return [
            $index, 
            $service->id,
            optional($service->vehicle->customer)->name ?? '-',
            $licensePlate,
            $service->complaint ?? '-',
            $service->current_mileage ?? 0,
            number_format($service->service_fee, 2),
            number_format($totalBiaya, 2),
            number_format($service->payment_received, 2),
            number_format($service->change, 2),
            $this->translateServiceType($service->service_type),
            boolval($service->status) ? 'Selesai' : 'Belum Selesai',
            $service->additional_notes ?? '-',
            $service->technician_name ?? '-',
            $service->jurusan ?? '-',
            $diskon > 0 ? number_format($diskon, 2) : '',
            ucfirst($service->payment_method),
            $service->service_date,
            $checklist,
        ];
    }
        
    private function translateServiceType($type)
    {
        $translations = [
            'light'  => 'Ringan',
            'medium' => 'Sedang',
            'heavy'  => 'Berat',
        ];
        if (Auth::check()) {
            if (Auth::user()->jurusan == 'TKRO') {
                $translations = [
                    'light'  => '10.000 KM (Ringan)',
                    'medium' => '30.000 KM (Sedang)',
                    'heavy'  => '50.000 KM (Berat)',
                ];
            } elseif (Auth::user()->jurusan == 'TSM') {
                $translations = [
                    'light'  => 'Service Kecil',
                    'medium' => 'Service Sedang',
                    'heavy'  => 'Service Besar',
                ];
            }
        }
        return $translations[$type] ?? ucfirst($type);
    }    

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 16, 'color' => ['argb' => '000000']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '000000']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            3 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => '0070C0']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'A3:S' . $lastRow => [
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet         = $event->sheet;
                $highestRow    = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->mergeCells('A1:S1');
                $sheet->mergeCells('A2:S2');

                if ($highestRow == 3) {
                    $sheet->mergeCells('A4:S4');
                    $sheet->setCellValue('A4', 'Tidak ada data');
                    $sheet->getStyle('A4')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => 'solid',
                            'startColor' => ['argb' => 'FFFF00'],
                        ],
                    ]);
                    $highestRow = 4;
                }

                $totalRow = $highestRow + 2;
                $sheet->setCellValue('F' . $totalRow, 'Total Penghasilan:');
                $sheet->setCellValue('G' . $totalRow, number_format($this->totalIncome, 2));
                $sheet->getStyle('F' . $totalRow)->getFont()->setBold(true);

                for ($row = 4; $row <= $highestRow; $row++) {
                    $statusCell  = 'L' . $row;
                    $paymentCell = 'I' . $row;
                    $statusValue  = $sheet->getCell($statusCell)->getValue();
                    $paymentValue = $sheet->getCell($paymentCell)->getValue();
                    if ($statusValue === 'Selesai') {
                        $sheet->getStyle($statusCell)->applyFromArray([
                            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '00FF00']]
                        ]);
                    } elseif ($statusValue === 'Belum Selesai') {
                        $sheet->getStyle($statusCell)->applyFromArray([
                            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF0000']]
                        ]);
                    }
                    if ($paymentValue > 0) {
                        $sheet->getStyle($paymentCell)->applyFromArray([
                            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '00FF00']]
                        ]);
                    } else {
                        $sheet->getStyle($paymentCell)->applyFromArray([
                            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF0000']]
                        ]);
                    }
                }

                $sheet->getStyle("S4:S{$highestRow}")->getAlignment()->setWrapText(true);

                foreach ($sheet->getColumnIterator() as $column) {
                    $col = $column->getColumnIndex();
                    $maxLength = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                        $cell = $sheet->getCell($col . $row->getRowIndex());
                        $cellValue = $cell->getCalculatedValue();
                        if ($cellValue !== null) {
                            $length = strlen($cellValue);
                            if ($length > $maxLength) {
                                $maxLength = $length;
                            }
                        }
                    }
                    $width = $maxLength < 10 ? 10 : $maxLength;
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}