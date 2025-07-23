<?php

namespace App\Exports;

use App\Models\Sparepart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SparepartExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;
    protected $jurusan;

    public function __construct($from, $to, $jurusan = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->jurusan = $jurusan;
    }

    public function collection()
    {
        $query = Sparepart::whereBetween('created_at', [$this->from, $this->to]);

        if (!empty($this->jurusan)) {
            $query->where('jurusan', $this->jurusan);
        }

        return $query->get([
            'id_sparepart',
            'nama_sparepart',
            'spek',
            'jumlah',
            'harga_beli',
            'harga_jual',
            'keuntungan',
            'tanggal_masuk',
            'jurusan',
            'created_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Sparepart',
            'Spesifikasi',
            'Jumlah',
            'Harga Beli',
            'Harga Jual',
            'Keuntungan',
            'Tanggal Masuk',
            'Jurusan',
            'Tanggal Dibuat', // created_at
        ];
    }

}