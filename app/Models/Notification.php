<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'is_read',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}