<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id',
        'field_changed',
        'old_value',
        'new_value',
        'action',
        'user_id'
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }
    
    public function sparepartObserver()
    {
        return $this->belongsTo(Sparepart::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

}