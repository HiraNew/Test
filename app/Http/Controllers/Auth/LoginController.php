<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Create a simple number input form
    }

    public function login(Request $request)
    {
        $request->validate([
            'number' => 'required|exists:users,number',
        ]);

        $user = User::where('number', $request->number)->first();

        if (!$user) {
            return back()->withErrors(['number' => 'Mobile number not found.']);
        }
        if ($user->status != 1) {
            $user->status = 1;
            $user->save();
        }
        Auth::guard('web')->login($user);

        $request->session()->regenerate();

        return redirect()->intended('/'); // or whatever your route is
    }

    public function logout(Request $request)
    {
        // Get the current user before logging out
        $user = Auth::guard('web')->user();

        if ($user) {
            $user->status = 0;
            $user->save();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    //     $this->middleware('auth')->only('logout');
    // }
}
