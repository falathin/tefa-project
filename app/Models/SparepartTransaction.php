<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id',
        'quantity',
        'purchase_price',
        'total_price',
        'transaction_date',
        'transaction_type',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'id_sparepart');
    }

    public function spareparts()
    {
        return $this->hasMany(Sparepart::class, 'id_sparepart', 'sparepart_id');
    }

}