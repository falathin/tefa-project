<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'sparepart_id',
        'quantity',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    // Relasi ke transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi ke Sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }

}