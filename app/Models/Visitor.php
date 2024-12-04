<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    // Asumsi model pengunjung memiliki properti atau field 'visit_date'
    protected $fillable = [
        'name',
        'email',
        'visit_date',
    ];
}