<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servis';

    protected $primaryKey = 'id_servis';

    protected $fillable = [
        'nomor_polisi',
        'keluhan',
        'kilometer_saat_ini',
        'harga_jasa',
        'total_biaya',
        'uang_masuk',
        'kembalian',
        'jenis_servis',
        'tanggal_servis',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'nomor_polisi', 'no_polisi');
    }
    
    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'servis_sparepart', 'servis_id', 'sparepart_id');
    }    
    
    public function servisSparepart()
    {
        return $this->hasMany(ServisSparepart::class);
    }
}