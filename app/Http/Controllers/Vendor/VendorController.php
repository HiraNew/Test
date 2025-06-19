<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function getVendorLoginForm()
    {
        return view('Vendor.login');
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits_between:10,15',
            'password' => 'required|min:6',
        ]);
        // dd('dfijvg');
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $credentials = $request->only('mobile', 'password');
        if (Auth::guard('vendor')->attempt($credentials)) {
            return redirect()->intended(route('vendor.dashboard'));
        }
         return redirect()->back()->with('error', 'Invalid mobile number or password.');
    }
    public function vendorLogout()
    {
        Auth::guard('vendor')->logout();

        // Optional: Invalidate session and regenerate CSRF token
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('vendor.login')->with('success', 'Logged out successfully.');
    }
    public function vendorDashboard(Request $request)
    {
       $vendor = Auth::guard('vendor')->user();

    // Date range filters from UI
        $from = $request->get('from') ?? Carbon::now()->subDays(30)->toDateString();
        $to = $request->get('to') ?? Carbon::now()->toDateString();
        // dd($to);

        // Base payment query scoped to vendor
        $paymentQuery = Payment::with('product')
            ->where('vendor_id', $vendor->id)
            ->whereBetween('created_at', [$from, $to]);
            // dd($paymentQuery->get());
        
        // Stats
        $productCount = Product::where('vendor_id', $vendor->id)->count();
        $orderCount = $paymentQuery->count();
        // dd($orderCount);
        $pendingOrders = (clone $paymentQuery)->where('status', 'pending')->count();
        $earnings = (clone $paymentQuery)->where('status', 'delivered')->sum('amount');

        // Recent 5 orders
        $recentOrders = (clone $paymentQuery)->latest()->take(5)->get();

        // New orders in last 24h
        $newOrderCount = Payment::where('vendor_id', $vendor->id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        $recentProducts = Product::where('vendor_id', $vendor->id)->latest()->take(5)->get();

        return view('vendor.dashboard', compact(
            'productCount', 'orderCount', 'pendingOrders', 'earnings',
            'recentOrders', 'recentProducts', 'newOrderCount'
        ));
    }
}
