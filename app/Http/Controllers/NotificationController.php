<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $notifications = Notification::query();

        // Filter notifications by search term
        if ($search) {
            $notifications = $notifications->where('message', 'like', '%' . $search . '%');
        }

        // Paginate the notifications
        $notifications = $notifications->with('sparepart')->where('is_read', false)->latest()->paginate(5);

        // Pass the notifications to the view
        return view('notifications.index', compact('notifications', 'search'));
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
        // Correct the line where we find spare part related to notification
        $notification = Notification::findOrFail($id);
        $sparepart = $notification->sparepart;

        // Pass the sparepart to the edit view
        return view('sparepart.edit', compact('sparepart'));
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update([
            'message' => $request->message,
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }
}