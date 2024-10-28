<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        // Find the notification by ID and mark it as read
        $notification = DatabaseNotification::find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'Notification marked as read']);
        }

        return response()->json(['status' => 'Notification not found'], 404);
    }
}
