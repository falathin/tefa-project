<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraans';
    protected $primaryKey = 'no_polisi';
    public $incrementing = false; // Indicating that primary key is not an auto-incremented integer
    protected $keyType = 'string';

    protected $fillable = [
        'no_polisi',
        'jenis_kendaraan',
        'warna',
        'tahun_produksi',
        'kode_mesin',
        'id_pelanggan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }    

    public function servis()
    {
        return $this->hasMany(Servis::class, 'nomor_polisi', 'no_polisi');
    }
}