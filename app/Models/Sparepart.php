<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sparepart';

    protected $fillable = [
        'nama_sparepart',
        'jumlah',
        'harga_beli',
        'harga_jual',
        'keuntungan',
        'tanggal_masuk',
        'tanggal_keluar',
        'deskripsi',
        'jurusan'
    ];

    public function serviceSpareparts()
    {
        return $this->hasMany(ServiceSparepart::class, 'sparepart_id');
    }

    public function calculateProfit()
    {
        return $this->harga_jual - $this->harga_beli;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_sparepart', 'sparepart_id', 'service_id')
                    ->withPivot('quantity');
    }

    public function getKeuntunganAttribute()
    {
        return $this->harga_jual - $this->harga_beli;
    }

    public function transactions()
    {
        return $this->hasMany(SparepartTransaction::class, 'sparepart_id', 'id_sparepart');
    }
}