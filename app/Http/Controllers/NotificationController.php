<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notification()
{
    // Get user notifications
    $notifications = auth()->user()->notifications;

    return view('notifications.notification', compact('notifications'));
}

}
