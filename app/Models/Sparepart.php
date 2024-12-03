<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sparepart'; // Custom primary key

    protected $fillable = [
        'nama_sparepart',
        'jumlah',
        'harga_beli',
        'harga_jual',
        'keuntungan',
        'tanggal_masuk',
        'tanggal_keluar',
        'deskripsi',
    ];

    public function serviceSpareparts()
    {
        return $this->hasMany(ServiceSparepart::class, 'sparepart_id');
    }

    public function calculateProfit()
    {
        return $this->harga_jual - $this->harga_beli;
    }
}