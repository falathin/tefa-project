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

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

}