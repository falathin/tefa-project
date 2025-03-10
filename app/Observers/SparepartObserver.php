<?php

namespace App\Observers;

use App\Models\Sparepart;
use App\Models\Notification;
use App\Models\SparepartHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SparepartObserver
{
    // untuk update histori sparepart
    public function updated(Sparepart $sparepart)
    {
        // Ambil nilai lama (sebelum diubah) dari kolom 'jumlah'
        $oldValue = $sparepart->getOriginal('jumlah');
        $newValue = $sparepart->jumlah;

        SparepartHistory::create([
            'sparepart_id' => $sparepart->id_sparepart,
            'field_changed' => 'Updated',
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'action' => 'update',
            'user_id' => Auth::user()->id
        ]);
    }

    // Catat history saat Sparepart dibuat
    public function created(Sparepart $sparepart)
    {
        SparepartHistory::create([
            'sparepart_id' => $sparepart->id_sparepart,
            'field_changed' => 'created',
            'old_value' => 0,
            'new_value' => $sparepart->jumlah,
            'action' => 'create',
            'user_id' => Auth::user()->id,
        ]);
    }

    // untuk pengingat notifikasi sparepart menipis
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
