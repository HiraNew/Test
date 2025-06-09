<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function userAccount()
    {
        // Assuming you have user authentication
        $user = Auth::user();

        // Sample data — replace these with real queries if needed
        $membership = [
            'level' => 'Plus Silver',
            'valid_till' => 'August 26, 2025',
            'points' => 64
        ];

        return view('UserProfile.account', compact('user', 'membership'));
    }
}
