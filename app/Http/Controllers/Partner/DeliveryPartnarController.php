<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliveryPartnarController extends Controller
{
    public function RegistrationForm()
    {
        return view('DeliveryPartner.RegistrationForm');
    }
    public function getPartnerLoginForm()
    {
        return view('DeliveryPartner.LoginForm');
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
        if (Auth::guard('partner')->attempt($credentials)) {
            return redirect()->intended(route('partner.delivery'));
        }
         return redirect()->back()->with('error', 'Invalid mobile number or password.');
    }
    public function getListOfProductForDelivered()
    {
        $partner = Auth::guard('partner')->user();

        // Get all shipped orders
        $rawAssigned = Payment::with(['product', 'user', 'address'])
            ->where('delivery_partner_id', $partner->id)
            ->where('status', 'shipped')
            ->get();

        // Group by orderid
        $assigned = $rawAssigned->groupBy('orderid');

        // Fetch all orders for status tab (unmodified)
        $orders = Payment::with('product')
            ->where('delivery_partner_id', $partner->id)
            ->whereIn('status', ['delivered', 'shipped'])
            ->get();


        return view('DeliveryPartner.index', compact('assigned', 'orders'));
    }

    // public function getListOfProductForDelivered()
    // {
    //    $partner = Auth::guard('partner')->user();

    //     $assigned = Payment::with(['product','user','address'])
    //         ->where('delivery_partner_id', $partner->id)
    //         ->where('status', 'shipped')
    //         ->get();
    //         // dd($assigned->user);

    //     $orders = Payment::with('product')
    //         ->where('delivery_partner_id', $partner->id)
    //         ->get();
    //     return view('DeliveryPartner.index', compact('assigned', 'orders'));
    // }
    public function sendOtp(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'string', 'size:10'],
        ]);

        $partnerId = Auth::guard('partner')->id();

        // Get all payment rows for this order and partner
        $payments = Payment::where('orderid', $request->order_id)
            ->where('delivery_partner_id', $partnerId)
            ->get();

        if ($payments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or not assigned to you.'
            ]);
        }

        // Check if all items are already delivered
        $undeliveredItems = $payments->filter(function ($payment) {
            return $payment->status !== 'delivered';
        });

        if ($undeliveredItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'All items in this order are already delivered.'
            ]);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP and expiry in only undelivered items
        foreach ($undeliveredItems as $payment) {
            $payment->feild2 = $otp; // use actual field name
            $payment->feild3 = now('Asia/Kolkata')->addMinutes(10);
            $payment->save();
        }

        // TODO: Send OTP via SMS/Email

        session([
            'otp_sent' => true,
            'otp_sent_at' => now('Asia/Kolkata')->timestamp, // current UNIX time
            'order_id' => $request->order_id
        ]);



        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.'
            // 'otp' => $otp // only for development/testing
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'order_id' => 'required|max:10',
            'otp' => 'required|digits:6'
        ], [
            'otp.required' => 'Please enter the OTP.',
            'otp.digits' => 'OTP must be 6 digits.'
        ]);


        $orderId = $request->order_id;
        $inputOtp = $request->otp;

        // Get one matching payment record for this order to verify the OTP
        $payment = Payment::where('orderid', $orderId)
            ->where('delivery_partner_id', Auth::guard('partner')->id())
            ->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Order not found or not assigned to you.');
        }

        // Check if OTP matches and is not expired
        if (
            $payment->feild2 === $inputOtp &&
            $payment->feild3 &&
            now('Asia/Kolkata')->lt($payment->feild3)
        ) {
            // Mark all items with same order ID as delivered
            Payment::where('orderid', $orderId)
                ->where('delivery_partner_id', Auth::guard('partner')->id())
                ->update([
                    'status' => 'delivered',
                    'feild4' => now('Asia/Kolkata'), // Optional: track delivery time
                ]);

            // Optionally clear OTP after verification
            // Payment::where('orderid', $orderId)
            //     ->where('delivery_partner_id', Auth::guard('partner')->id())
            //     ->update([
            //         'otp' => null,
            //         'otp_expires_at' => null
            //     ]);
            // After successful OTP verification and marking delivered
             session()->forget(['otp_sent', 'otp_sent_at', 'order_id']);

            return redirect()->back()->with('success', 'OTP verified. All items marked as delivered.');
        }

        return redirect()->back()->with('error', 'Invalid or expired OTP.');
    }
    public function partnerLogout()
    {
        Auth::guard('partner')->logout();

        // Optional: Invalidate session and regenerate CSRF token
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('partner.login')->with('success', 'Logged out successfully.');
    }


    public function showForm($orderid)
    {
        return view('location.share', compact('orderid'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'orderid' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $payment = Payment::where('orderid', $request->orderid)->first();
        if ($payment) {
            $payment->feild5 = $request->lat;
            $payment->feild6 = $request->lng;
            $payment->save();
        }

        return redirect()->back()->with('success', 'Thank you! Your location has been shared.');
    }



}
