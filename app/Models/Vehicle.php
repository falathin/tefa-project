<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'type',
        'color',
        'production_year',
        'engine_code',
        'customer_id',
        'image',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Services
    public function services()
    {
        return $this->hasMany(Service::class, 'vehicle_id');
    }
}