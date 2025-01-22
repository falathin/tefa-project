<?php
namespace App\Notifications;

use App\Models\Sparepart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SparepartLowStockNotification extends Notification
{
    use Queueable;

    protected $sparepart;

    public function __construct(Sparepart $sparepart)
    {
        $this->sparepart = $sparepart;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        
        return [
            'title' => 'Stok Sparepart Menipis !!!',
            'jurusan' => $this->sparepart->jurusan,
            'message' => "Stok untuk {$this->sparepart->nama_sparepart} tersisa {$this->sparepart->jumlah}. Segera lakukan pengadaan!",
            'sparepart_id' => $this->sparepart->id_sparepart,
        ];
    }
}