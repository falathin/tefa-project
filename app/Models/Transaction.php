<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'purchase_price',
        'total_price',
        'discount',
        'payment_method',
        'transaction_date',
        'transaction_type',
        'jurusan'
    ];

    public function transactionSpareparts()
    {
        return $this->hasMany(SparepartTransaction::class);
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'sparepart_transactions', 'transaction_id', 'sparepart_id')->withPivot('quantity');
    }
}
