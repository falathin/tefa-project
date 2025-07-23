<?php

namespace App\Exports;

use App\Models\Sparepart;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class SparepartExport implements FromCollection, WithHeadings
{
    protected $fromDate;
    protected $toDate;
    protected $jurusan;

    /**
     * Constructor untuk inisialisasi filter ekspor.
     *
     * @param string $from     Tanggal awal dalam format Y-m-d
     * @param string $to       Tanggal akhir dalam format Y-m-d
     * @param string $jurusan  Jurusan pengguna
     */
    public function __construct(string $from, string $to, string $jurusan)
    {
        $this->fromDate = Carbon::parse($from)->startOfDay();
        $this->toDate   = Carbon::parse($to)->endOfDay();
        $this->jurusan  = $jurusan;
    }

    /**
     * Ambil data sparepart sesuai filter tanggal dan jurusan.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Sparepart::whereBetween('created_at', [$this->fromDate, $this->toDate]);

        if ($this->jurusan !== 'General') {
            $query->where('jurusan', $this->jurusan);
        }

        return $query->get([
            'id_sparepart',
            'nama_sparepart',
            'spek',
            'jumlah',
            'harga_jual',
            'jurusan',
            'created_at'
        ]);
    }

    /**
     * Judul kolom untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Sparepart',
            'Spek',
            'Stok',
            'Harga Satuan',
            'Jurusan',
            'Tanggal Masuk'
        ];
    }
}
