<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    public function index(Request $request) {
        $user = $request->user();
        $perPage = $request->get('per_page', 5);

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_cleared', false)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('is_cleared', false)
            ->count();

        return response()->json([
            'notifications' => $notifications->items(),
            'current_page'  => $notifications->currentPage(),
            'last_page'     => $notifications->lastPage(),
            'total'         => $notifications->total(),
            'unread_count'  => $unreadCount
        ]);
    }

    public function latest(Request $request) {
        $user = $request->user();
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_cleared', false)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('is_cleared', false)
            ->count();
        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount
        ]);
    }

    public function markRead(Request $request, $id) {
        Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request) {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function clearRead(Request $request) {
        // Hide read notifications from the list without deleting them (keeps audit history)
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', true)
            ->update(['is_cleared' => true]);
        return response()->json(['success' => true]);
    }

    public function clearOne(Request $request, $id) {
        // Hide a single notification (read or unread) without deleting it
        Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->update(['is_cleared' => true]);
        return response()->json(['success' => true]);
    }
}
