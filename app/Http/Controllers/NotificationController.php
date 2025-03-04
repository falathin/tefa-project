<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $notifications = Notification::query();

        if (Gate::allows('isBendahara')) {
            if ($search) {
                $notifications = $notifications
                    ->where('message', 'like', '%' . $search . '%');
            }
            $notifications = $notifications->with('sparepart')->where('is_read', false)->latest()->paginate(5);
            return view('notifications.index', compact('notifications', 'search'));
        } else {
            if ($search) {
                $notifications = $notifications
                    ->where('jurusan', 'like', Auth::user()->jurusan)
                    ->where('message', 'like', '%' . $search . '%');
            }

            $notifications = $notifications->with('sparepart')
                ->where('jurusan', 'like', Auth::user()->jurusan)
                ->where('is_read', false)->latest()->paginate(5);

            return view('notifications.index', compact('notifications', 'search'));
        }
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        $sparepart = $notification->sparepart;

        if (!$sparepart) {
            return redirect()->route('notifications.index')->with('error', 'Sparepart not found.');
        }

        return redirect()->route('sparepart.edit', $sparepart->id_sparepart);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update($request->all());

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function unreadCount()
    {
        $unreadCount = Notification::where('is_read', false)->count();
        return response()->json(['count' => $unreadCount]);
    }

    public function markAllAsRead()
    {
        if (Gate::allows('isBendahara')) {
            Notification::where('is_read', false)->update(['is_read' => true]);
        } else {
            Notification::where('is_read', false)
                ->where('jurusan', Auth::user()->jurusan)
                ->update(['is_read' => true]);
        }
    
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }    

}
