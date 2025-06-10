<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
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
        // List all payments/orders for logged-in user
    public function index()
    {
        // Eager load 'product' relation for all payments of the logged-in user
        $payments = Payment::with('product')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    // dd($payments);

        return view('Order.index', compact('payments'));
    }

    // Show single payment/order detail with recent payments
    public function show($paymentId)
    {
        // Eager load 'product' relation for the single payment
        $payment = Payment::with('product')
                    ->where('id', $paymentId)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        // Also eager load product relation for recent payments
        $recentPayments = Payment::with('product')
                        ->where('user_id', Auth::id())
                        ->where('id', '!=', $payment->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

        return view('Order.show', compact('payment', 'recentPayments'));
    }


}
