<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceChecklist extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'task', 'is_completed'];

    protected $dates = ['created_at'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}