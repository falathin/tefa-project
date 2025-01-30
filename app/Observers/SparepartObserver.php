<?php

namespace App\Observers;

use App\Models\Sparepart;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SparepartObserver
{
    public function updating(Sparepart $sparepart)
    {
        // && Gate::allows('isSameJurusan', [$sparepart]) jika dibutuhkan
        if ($sparepart->isDirty('jumlah') && $sparepart->jumlah <= 2) {
            Notification::create([
                'title' => 'Stok Sparepart Menipis',
                'message' => "Stok untuk {$sparepart->nama_sparepart} tersisa {$sparepart->jumlah}. Segera lakukan pengadaan!",
                'jurusan' => Auth::user()->jurusan
            ]);
        }
    }
}