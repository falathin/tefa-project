<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Show the list of notifications
    public function index(Request $request)
    {
        $search = $request->input('search');
        $notifications = Notification::query();

        // Filter notifications by search term
        if ($search) {
            $notifications = $notifications->where('message', 'like', '%' . $search . '%');
        }

        // Paginate the notifications and load related sparepart if available
        $notifications = $notifications->with('sparepart')->where('is_read', false)->latest()->paginate(5);

        return view('notifications.index', compact('notifications', 'search'));
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return redirect()->back();
    }

    // Redirect to the sparepart.edit page when the user wants to edit a spare part from the notification
    public function edit($id)
    {
        // Retrieve the notification (optional, if you need the notification data)
        $notification = Notification::findOrFail($id);
        
        // Retrieve the related sparepart (assuming a notification is linked to a spare part)
        $sparepart = $notification->sparepart; // This assumes the notification has a relation to sparepart.

        // If there is no spare part linked to the notification, handle that case
        if (!$sparepart) {
            return redirect()->route('notifications.index')->with('error', 'Sparepart not found.');
        }

        // Redirect to the sparepart.edit page
        return redirect()->route('sparepart.edit', $sparepart->id_sparepart);
    }

    // Update spare part
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

    // app/Http/Controllers/NotificationController.php
    public function unreadCount()
    {
        $unreadCount = App\Models\Notification::where('is_read', false)->count();
        return response()->json(['count' => $unreadCount]);
    }

}