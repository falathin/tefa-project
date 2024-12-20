<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyPassword extends Model
{
    use HasFactory;
    protected $fillable = [
        'emergency_password',
    ];
}
