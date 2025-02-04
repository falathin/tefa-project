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
        'status',
        'additional_notes',
        'technician_name',
        'jurusan'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function serviceSpareparts()
    {
        return $this->hasMany(ServiceSparepart::class);
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'service_spareparts')->withPivot('quantity');
    }

    public function isPaid()
    {
        return $this->payment_received >= $this->total_cost;
    }

    public function checklists()
    {
        return $this->hasMany(ServiceChecklist::class);
    }
}