<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json(['data' => $user->unreadNotifications()->get()]);
    }

    public function markAsRead(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $user->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->json('OK');
    }
}
