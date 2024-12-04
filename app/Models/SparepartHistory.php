<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id', 
        'jumlah_changed',
        'action',
    ];

    // Define the relationship with Sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }
}