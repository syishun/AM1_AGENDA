<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;

class TestNotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function sendTestNotification()
    {
        $title = 'Test Notification';
        $body = 'This is a test notification from Firebase.';
        $token = 'YOUR_DEVICE_FCM_TOKEN';

        try {
            $response = $this->firebaseService->sendNotification($title, $body, $token);
            return response()->json(['success' => true, 'response' => $response]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
