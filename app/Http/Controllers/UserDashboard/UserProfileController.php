<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Addre;
use App\Models\City;
use App\Models\Country;
use App\Models\DeliveryLocation;
use App\Models\Payment;
use App\Models\Product;
use App\Models\RecentView;
use App\Models\State;
use App\Models\Village;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function userAccount()
    {
        // Assuming you have user authentication
        $user = Auth::user();
        $nowKolkata = Carbon::now('Asia/Kolkata');
        $nowKolkata->format('d M Y, h:i A');

        // Sample data — replace these with real queries if needed
        $membership = [
            'level' => 'Plus Silver',
            'valid_till' => 'August 26, 2025',
            'points' => 64
        ];
        $ProductsQuery = Product::with(['tags', 'reviews', 'wishlists'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews');
        $Products = $ProductsQuery->paginate(20);
        if ($Products->isEmpty()) {
                $Products = Product::with(['tags', 'reviews', 'wishlists'])
                    ->inRandomOrder()
                    ->limit(4)
                    ->get();
        }
        $wishlistProductIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];
        if (Auth::check()) {
                // foreach ($Products as $product) {
                //     RecentView::updateOrCreate(
                //         ['user_id' => Auth::id(), 'product_id' => $product->id],
                //         ['viewed_at' =>  $nowKolkata->format('d M Y, h:i A')]
                //     );
                // }

                $recentViews = Product::whereIn('id', RecentView::where('user_id', Auth::id())
                    ->orderByDesc('viewed_at')
                    ->limit(8)
                    ->pluck('product_id'))
                    ->with(['tags', 'reviews', 'wishlists'])
                    ->withAvg('reviews', 'rating')
                    ->withCount('wishlists', 'reviews') // Add wishlists count if needed
                    ->get();
            } else {
                $recentViews = collect();
            }

        return view('UserProfile.account', compact('user', 'membership','recentViews','wishlistProductIds'));
    }
        // List all payments/orders for logged-in user
    public function index()
    {
        // Eager load 'product' relation for all payments of the logged-in user
        $payments = Payment::with('product')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    // dd($payments)
        $totalOrders = Payment::where('user_id', Auth::id())->count();
        $deliveredOrders = Payment::where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->count();
        $cancelledOrders= Payment::where('user_id', Auth::id())
            ->where('status', 'cancelled')
            ->count();

        return view('Order.index', compact('payments','totalOrders','deliveredOrders','cancelledOrders'));
    }

    // Show single payment/order detail with recent payments
    public function show($paymentId)
    {
        $payment = Payment::with('product')
            ->where('id', $paymentId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $recentPayments = Payment::with('product')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $payment->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

            // dd($recentPayments);

       $userId = Auth::id();

        $address = Addre::with(['country', 'state', 'city', 'village'])
            ->where('payment_id', $paymentId)
            ->first();

        // Fallback to user_id if no address found for payment_id
        if (!$address) {
            $address = Addre::with(['country', 'state', 'city', 'village'])
                ->where('user_id', $userId)
                ->first();
        }

        if (!$address) {
            return back()->with('error', 'No address found for this payment or user.');
        }
        $productReturnDays = $payment->product->extra1 ?? 0;
        $deliveryDate = $payment->delivery_date ? \Carbon\Carbon::parse($payment->delivery_date) : null;
        $eligibleDate = $deliveryDate ? $deliveryDate->copy()->addDays($productReturnDays) : null;
        $isReturnEligible = $eligibleDate && now()->lte($eligibleDate) && is_null($payment->return_period);

        // 👇 ADD THIS LINE
        $countries = Country::pluck('name', 'id');

        return view('Order.show', compact(
            'payment',
            'recentPayments',
            'address',
            'isReturnEligible',
            'eligibleDate',
            'countries' // 👈 Include this
        ));
    }

    public function update(Request $request, $paymentId)
    {
        // dd($request->landmark);
        // 'address' => 'required|string|max:255',
        //     'pincode' => 'required|string|max:10',
        //     'country' => 'nullable|string',
        //     'state' => 'nullable|string',
        //     'city' => 'nullable|string',
        //     'village' => 'nullable|string',
        //     'postal_code' => 'nullable|string|max:20',
        //     'mobile_number' => 'required|digits:10',
        //     'alt_mobile_number' => 'nullable|digits:10',
        //     'landmark' => 'nullable|string|max:255',
        
        $request->validate([
            'address_line' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|integer',
            'country' => 'required|string|max:100',
            'mobile_number' => 'string|max:15',
            'alt_mobile_number' => 'nullable|string|max:15',
            'village' => 'required|string|max:100',
            'landmark' => 'nullable|string|max:255',
        ]);

        $payment = Payment::with('address')->findOrFail($paymentId);

        $address = $payment->address;
        $id = Auth::id();
        $address = Addre::firstOrNew(['user_id' => $id]);
        $address->user_id = $id;
        $address->payment_id = $paymentId;
        $address->address = $request->address_line;
        $address->city_id = $request->city;          // Use city_id, not city
        $address->state_id = $request->state;        // Use state_id
        $address->country_id = $request->country;    // Use country_id
        $address->postal_code = $request->postal_code;
        $address->mobile_number = $request->mobile_number;
        $address->alt_mobile_number = $request->alt_mobile_number;
        $address->landmark = $request->landmark;
        $address->save();



        return back()->with('success', 'Address updated successfully.');
    }

    public function cancel(Request $request, $paymentId)
    {
        $request->validate([
            'cancel_reason' => 'required|string',
            'other_reason_text' => 'nullable|string|max:255',
        ]);

        $payment = Payment::where('id', $paymentId)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        $reason = $request->cancel_reason === 'Other reasons' ? $request->other_reason_text : $request->cancel_reason;

        $payment->update([
            'status' => 'cancelled',
            'is_canceled' => $reason,
        ]);

        return back()->with('success', 'Order cancelled successfully.');
    }

    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->pluck('name', 'id');
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->pluck('name', 'id');
        return response()->json($cities);
    }
    // In your controller

    public function getVillages($cityId)
    {
        $villages = Village::where('city_id', $cityId)->pluck('name', 'id');
        return response()->json($villages);
    }




}
