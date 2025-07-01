<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AdminNotification;

class AdminNotifyController extends Controller
{
    public function sendToAll(Request $req) {
        User::all()->each->notify(new AdminNotification($req->message));
        return back();
    }

    public function sendToUser(Request $req, User $user) {
        // $user->notify(new AdminNotification($req->message));
        $user->notify(new AdminNotification('Hello, this is a real-time notification!'));
        return back();
    }
}
