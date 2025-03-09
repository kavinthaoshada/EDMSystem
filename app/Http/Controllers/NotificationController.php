<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index', [
            'notifications' => auth()->user()->notifications()->paginate(10)
        ]);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    }
}
