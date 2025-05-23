<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Addre;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function checkInternetConnection()
    {
        try {
            // Try to connect to a reliable external URL (e.g., Google)
            $response = Http::timeout(5)->get('https://www.google.com');

            // Check if the request was successful
            if ($response->successful()) {
                session()->flash('status', 'Internet is connected!');
                session()->flash('status_type', 'success'); // To show green or success message
            } else {
                session()->flash('status', 'Internet is not connected!');
                session()->flash('status_type', 'danger'); // To show red or error message
            }
        } catch (\Exception $e) {
            // If the request fails, there is no internet connection
            session()->flash('status', 'Internet is not connected!');
            session()->flash('status_type', 'danger');
        }

        // Redirect back to the previous page or to a specific view
        return redirect()->back();
    }
    public function carting()
    {
        // Get the authenticated user
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
    public function notification()
    {
        $user = Auth::user();
        $count = 0;
        if(isset($user))
        {
            $notification = Notification::whereNot('status', 1)
                            ->where('user_id', $user->id)
                            ->get();
                            // dd($notification);
            foreach($notification as $status)
            {
                $count += $status->status; 
            }
            if(($count === 0) && ($notification->count() > 0)){
                return response()->json([
                    'status' => 'success',
                    'notification' => $notification->count(),
                ]);
            }
            else{
                return response()->json([
                    'status' => 'success',
                    'notification' => $notification->count(),
                ]);
            }
        }
    }

    public function notificationView()
    {
        $user = Auth::user();
       
        $notifications = Notification::where('user_id', $user->id)
                                     ->with('creator') // Load the creator (admin or manager)
                                     ->orderByDesc('created_at')
                                     ->get();
        
        if(isset($notifications))
        {
            DB::table('notifications')
            ->where('user_id', $user->id) // Specify the condition (e.g., where the notification ID matches)
            ->update([
               'status' => '1', // Update the 'status' column
               'updated_at' => now(), // Update the 'updated_at' timestamp
            ]);
            return view('userNotification',compact('notifications'));
        }
        
        // dd($notifications);
    }
    public function product()
    { 
        $this->carting();
        try{
            $Products = Product::all();

            // Get product IDs in cart for current user
            $cartProductIds = [];
            if (Auth::check()) {
                $cartProductIds = Cart::where('user_id', Auth::id())->pluck('product_id')->toArray();
            }

            return view('home', compact('Products', 'cartProductIds'));
        }
        catch (\Exception $e)
        {
            return view('error')->with('issue', $e);
        }
        
        return view('error');
    }
    public function detail($id){
        dd($id);
        $product = Product::with(['reviews.user'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
                              ->where('id', '!=', $id)
                              ->limit(4)
                              ->get();

        return view('productDetailed', compact('product', 'relatedProducts'));
    }
    
    public function addTocart($id)
    {
        try{
            $user = Auth::user();
            
            $Product = Product::find($id);
            if (!$Product) {
                // Handle case when product is not found
                return redirect()->back()->with('error', 'Product not found.');
            }
            $cart = Cart::where('user_id', $user->id)
                  ->where('product_id', $Product->id)
                  ->first();  // This finds the cart item if it exists for the user and product
           
            
            if($cart)
            {
                
                if($cart->quantity > 9)
                {
                 return redirect()->back()->with('error','Can,t Add More Than 10 Quantity of Any Item.');
                }
                $cart->quantity += 1;
                $cart->save();
                return redirect()->back()->with('insert','Total '. $cart->quantity. ' Quantity Added To '. $Product->name);
                
            }
         else{
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->product_id = $Product->id;
            $cart->save();
            return redirect()->back()->with('insert',' '. $Product->name. ' Added To Your Cart');
        }
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the product to your cart: ' . $e->getMessage());
        }

    }
    public function removeTocart($id)
    {
        $user = Auth::user();
        $Product = Product::find($id);
            if (!$Product) {
                // Handle case when product is not found
                return redirect()->back()->with('error', 'Product not found.');
            }
        $cart = Cart::where('user_id', $user->id)
                  ->where('product_id', $Product->id)
                  ->first();
        if($cart)
        {
            if($cart->quantity < 2)
            {
                return redirect()->back()->with('error','Item quantity can not less than 1.');
            }
                $cart->quantity -= 1;
                $cart->save();
            return redirect()->back()->with('success', 'Item Decreased.');
        }

    }
    public function removeItemTocart($id)
    {
        $user = Auth::user();
        if(session()->has('key'))
        {
            session()->forget('key');
        }
            // Fetch the cart items for the authenticated user
        $cartItems = $user->carts;
        if(isset($cartItems)){
            $cartItems->find($id)->delete();
            session(['key' => $cartItems->count()-1]);
            return redirect()->back()->with('success', 'Item Decreased.');
        }
    }
    public function cartView()
    {
        // dd('uierhf');
        $this->carting();
        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)
                     ->with('product.category') // eager load product details
                     ->get();
                    //  dd($carts[0]->product->category_id);
        $categoryName = [];
        foreach($carts as $cart){
            $categoryName[] = $cart->product->category->name;
        }
        // dd($categoryName);
        return view('userCart',compact('carts', 'categoryName'));
    }
    public function updateAddress()
    {
        $user = Auth::user();
        $address = Addre::where('user_id', $user->id)->first();
        if(isset($address))
        {
            return view('userAddress',compact('address'));
        }
        return view('userAddress');
    }
    public function cartProceed(Request $request)
{
    // Validate incoming request
    $request->validate([
        'address' => 'required|string|max:255',
        'pincode' => 'required|string|max:10',
    ]);

    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Please log in to continue.');
    }

    // Check if the user already has an address
    $address = Addre::firstOrNew(['user_id' => $user->id]);

    $address->address = $request->address;
    $address->pincode = $request->pincode;
    $address->save();

    return redirect()->route('paymentMethod')->with('success', 'Address saved. Proceed to payment.');
}

    public function paymentMethod()
    {
        return view('Order/paymentMethod');
    }
    public function generateUniqueCode($length = 7, $prefix = 'DLS') {
    $randomLength = $length - strlen($prefix);

    do {
        // Generate random alphanumeric part
        $randomPart = Str::upper(Str::random($randomLength));
        $code = $prefix . $randomPart;

        // Check uniqueness in DB (example uses "orders" table and "code" column)
    } while (Payment::where('orderid', $code)->exists());

    return $code;
}
    public function paymentMethodProceed(Request $request){
        $gst = 0.18;
        $plateFromCharge = 0;
        $orderId = $this->generateUniqueCode();
        $indiaTime = Carbon::now('Asia/Kolkata');
        $tomorrow = $indiaTime->copy()->addDay();
        $user = Auth::id();
        $orders = Cart::where('user_id',$user)->get();
        // If cart is empty, redirect back with message
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty. Please add items before placing an order.');
        }
        $address = Addre::where('user_id', $user)->first();
        foreach($orders as $order){
        $product = Product::find($order->product_id);
         if ($product && $address) {
            $confirm = new Payment();
            $confirm->user_id = $user;
            $confirm->product_id = $order->product_id;
            $confirm->qty = $order->quantity;
            $confirm->amount = ($product->price* $order->quantity);
            $confirm->payment_mode = $request->payment_method;
            $confirm->pincode = $address->pincode;
            $confirm->order_date = $indiaTime;
            $confirm->delevery_date = $tomorrow;
            $confirm->orderid = $orderId;
            $confirm->save();
        }
     }
     Cart::where('user_id',$user)->delete();
     $this->carting();
        return redirect()->route('orderNow')->with('success', 'Your Order is Confirmed Order ID:'. $orderId);
    }
    public function orderNow()
    {
        return view('orderSuccess');
    }
}

