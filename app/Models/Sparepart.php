<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

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

    // Relasi ke ServiceSparepart
    public function serviceSpareparts()
    {
        return $this->hasMany(ServiceSparepart::class, 'sparepart_id');
    }
}
