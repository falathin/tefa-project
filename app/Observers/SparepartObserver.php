<?php

namespace App\Observers;

use App\Models\Sparepart;
use App\Models\Notification;

class SparepartObserver
{
    public function updating(Sparepart $sparepart)
    {
        if ($sparepart->isDirty('jumlah') && $sparepart->jumlah <= 2) {
            Notification::create([
                'title' => 'Stok Sparepart Menipis',
                'message' => "Stok untuk {$sparepart->nama_sparepart} tersisa {$sparepart->jumlah}. Segera lakukan pengadaan!",
            ]);
        }
    }
}