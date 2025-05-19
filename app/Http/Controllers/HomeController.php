<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function checkInternetConnection()
    // {
    //     try {
    //         // Try to connect to a reliable external URL (e.g., Google)
    //         $response = Http::timeout(5)->get('https://www.google.com');

    //         // Check if the request was successful
    //         if ($response->successful()) {
    //             session()->flash('status', 'Internet is connected!');
    //             session()->flash('status_type', 'success'); // To show green or success message
    //         } else {
    //             session()->flash('status', 'Internet is not connected!');
    //             session()->flash('status_type', 'danger'); // To show red or error message
    //         }
    //     } catch (\Exception $e) {
    //         // If the request fails, there is no internet connection
    //         session()->flash('status', 'Internet is not connected!');
    //         session()->flash('status_type', 'danger');
    //     }

    //     // Redirect back to the previous page or to a specific view
    //     return redirect()->back();
    // }
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $this->checkInternetConnection();
        // dd(Auth::user()->name);
        // return redirect()->route('products');
        $user = Auth::user();
        if(isset($user))
        {
            if(session()->has('key'))
            {
                session()->forget('key');
            }
            // Fetch the cart items for the authenticated user
            $cartItems = $user->carts->count();
            session(['key' => $cartItems]);
        }
    }
    
    
}
