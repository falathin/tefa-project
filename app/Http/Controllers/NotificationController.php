<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $notifications = Notification::query();
        $jurusanId = Auth::user()->jurusan;

        if ($search) {
            $notifications = $notifications
            ->where('message', 'like', '%' . $search . '%')
            ->where('jurusan', 'like', $jurusanId)
            ;
        }
        
        $jurusanUser = Auth::user()->jurusan;
        
        $notifications = $notifications->with('sparepart')
        ->where('jurusan', 'like', $jurusanId)
        ->where('is_read', false)->latest()->paginate(5);

        $jurusanNotif = 1;
        

        return view('notifications.index', compact('notifications', 'search', 'jurusanNotif'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if (! Gate::allows('isSameJurusan', [$notification]))
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        if ( Gate::allows('isSameJurusan', [$notification]))

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
            'jurusan' => 'required',
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
}