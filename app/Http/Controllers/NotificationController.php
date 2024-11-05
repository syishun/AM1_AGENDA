<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Mark a notification as read
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Send a notification using Firebase Cloud Messaging
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotification(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'token' => 'required|string',
        ]);

        $title = $request->input('title');
        $body = $request->input('body');
        $token = $request->input('token');

        // Send notification using FirebaseService
        $this->firebaseService->sendNotification($title, $body, $token);

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
