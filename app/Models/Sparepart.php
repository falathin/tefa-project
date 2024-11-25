<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'spareparts';
    protected $primaryKey = 'id_sparepart';

    protected $fillable = [
        'nama_sparepart', 'jumlah', 'harga_beli', 'harga_jual', 'keuntungan', 'tanggal_masuk', 'tanggal_keluar', 'deskripsi'
    ];

    protected $guarded = ['id_sparepart'];

    public function servisSparepart()
    {
        return $this->hasMany(ServisSparepart::class, 'sparepart_id','is_sparepart');
    }   
}