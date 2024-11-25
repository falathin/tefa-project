<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSparepart extends Model
{
    protected $table =  'servis_sparepart';
    protected $fillable = ['sparepart_id','servis_id','jumlah'];
    public $timestamps = false;

    public function sparepart(){
        return $this->belongsTo(Sparepart::class,'sparepart_id');
    }

    public function servis(){
        return $this->belongsTo(Servis::class,'servis_id');
    }
}