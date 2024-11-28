<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'vehicle_type',
        'color',
        'production_year',
        'engine_code',
        'customer_id',
        'image',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'vehicle_id');
    }
}