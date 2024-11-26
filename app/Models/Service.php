<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'complaint',
        'current_mileage',
        'service_fee',
        'service_date',
        'total_cost',
        'payment_received',
        'change',
        'service_type',
    ];

    // Relasi ke Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Relasi ke ServiceSparepart
    public function serviceSpareparts()
    {
        return $this->hasMany(ServiceSparepart::class, 'service_id');
    }
}