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
        $this->endDate = $endDate;
        $this->category = $category;
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
            'No', 'ID Service', 'Nama Pelanggan', 'Plat Nomor', 'Keluhan',
            'Kilometer', 'Biaya Servis', 'Total Biaya', 'Pembayaran', 'Kembalian',
            'Tipe Servis', 'Status', 'Catatan', 'Teknisi', 'Jurusan',
            'Diskon', 'Metode Pembayaran', 'Tanggal Servis', 'Checklist Servis'
        ];
    }

    public function map($service): array
    {
        static $index = 0;
        $index++;
    
        $diskon = $service->diskon ?? 0; 
        $totalBiaya = $service->total_cost - $diskon;
    
        $checklist = optional($service->serviceChecklists)->map(function ($task) {
            return ($task->is_completed ? '✔' : '✘') . ' ' . $task->task;
        })->implode(', ') ?? 'Tidak ada checklist';
    
        return [
            $index, 
            $service->id,
            optional($service->vehicle->customer)->name ?? '-',
            optional($service->vehicle)->license_plate ?? '-',
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
            $diskon > 0 ? number_format($diskon, 2) : 'Tanpa Diskon',
            ucfirst($service->payment_method),
            $service->service_date,
            $checklist,
        ];
    }
        
    private function translateServiceType($type)
    {
        $translations = [
            'light' => 'Ringan',
            'medium' => 'Sedang',
            'heavy' => 'Berat',
        ];

        if (Auth::check()) {
            if (Auth::user()->jurusan == 'TKRO') {
                $translations = [
                    'light' => '10.000 KM (Ringan)',
                    'medium' => '30.000 KM (Sedang)',
                    'heavy' => '50.000 KM (Berat)',
                ];
            } elseif (Auth::user()->jurusan == 'TSM') {
                $translations = [
                    'light' => 'Service Kecil',
                    'medium' => 'Service Sedang',
                    'heavy' => 'Service Besar',
                ];
            }
        }
    
        return $translations[$type] ?? ucfirst($type);
    }    

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '0070C0']]],
            'A1:R' . $lastRow => [
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ],
            'S1:S' . $lastRow => [
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]], // Border untuk checklist servis
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
    
                // Menambahkan total penghasilan di bawah tabel
                $totalRow = $highestRow + 2;
                $sheet->setCellValue('F' . $totalRow, 'Total Penghasilan:');
                $sheet->setCellValue('G' . $totalRow, number_format($this->totalIncome, 2));
                $sheet->getStyle('F' . $totalRow)->getFont()->setBold(true);
    
                // Looping melalui setiap baris untuk memberikan style berdasarkan status dan pembayaran
                for ($row = 2; $row <= $highestRow; $row++) {
                    $statusCell = 'L' . $row;
                    $paymentCell = 'I' . $row;
    
                    $statusValue = $sheet->getCell($statusCell)->getValue();
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
    
                // Otomatis menyesuaikan lebar semua kolom berdasarkan kontennya
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
    
}