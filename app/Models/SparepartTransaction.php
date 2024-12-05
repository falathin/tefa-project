<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id',
        'jumlah',
        'harga_beli',
        'total_harga',
        'tanggal_transaksi',
        'jenis_transaksi',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

}