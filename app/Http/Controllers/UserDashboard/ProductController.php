<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Addre;
use App\Models\Cart;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
            // return response()->json([
            //     'status' => 'success',
            //     'notification' => $cartItems,
            // ]);
        }
        // dd($cartItems);
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
        // $notifications = Notification::where('user_id', $user->id)->get();
        $notifications = Notification::where('user_id', $user->id)
                                     ->with('creator') // Load the creator (admin or manager)
                                     ->orderByDesc('created_at')
                                     ->get();
        // $noti = User::find(1);
        // dd($notifications);
                            // dd($notifications->created_by);
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
        // dd('home');
        // $this->notification();
        $this->carting();
        // dd(isset($user->name));
        // $this->checkInternetConnection();
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
        
        // dd($Products);
        return view('error');
        // return view('home',compact('Products'));
    }
    public function detail($id){
        // dd($id);
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
            // dd($id);
            $user = Auth::user();
            // $cartItems = $user->carts->count();
            //     // dd($cartItems);
            //     if($cartItems >= 1)
            //     {
            //         return redirect()->back()->with('error','Only 1 Items allowed In Cart.');
            //     }
            // if(!isset($user))
            // {
            //     return redirect()->route('login');
            // }
            $Product = Product::find($id);
            if (!$Product) {
                // Handle case when product is not found
                return redirect()->back()->with('error', 'Product not found.');
            }
            // dd($Product);
            
            // $cart = Cart::where('product_id',$Product->id);
            // Check if the product is already in the cart for the logged-in user
            $cart = Cart::where('user_id', $user->id)
                  ->where('product_id', $Product->id)
                  ->first();  // This finds the cart item if it exists for the user and product
            // dd($cart);
            
            if($cart)
            {
                
                if($cart->quantity > 9)
                {
                 return redirect()->back()->with('error','Can,t Add More Than 10 Quantity of Any Item.');
                }
                $cart->quantity += 1;
                // dd($cart->quantity);
                $cart->save();
                return redirect()->back()->with('insert','Total '. $cart->quantity. ' Quantity Added To '. $Product->name);
                // return response()->json([
                //     'status' => 'success',
                //     'msg' => 'Total '. $cart->quantity. ' Quantity Added To '. $Product->name,
                // ]);
                // dd($cart->quantity);
            }
         else{
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->product_id = $Product->id;
            // $cart->price = $Product->price;
            // $cart->image = $Product->image;
            $cart->save();
            return redirect()->back()->with('insert',' '. $Product->name. ' Added To Your Cart');
            // return response()->json([
            //     'status' => 'success',
            //     'msg' => ' '. $Product->name. ' Added To Your Cart',
            // ]);
        }
        // dd($cart);
        // $cart = new Cart();
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the product to your cart: ' . $e->getMessage());
        }

        // dd($Product);
    }
    public function removeTocart($id)
    {
        // dd('userCart');
        $user = Auth::user();
        $Product = Product::find($id);
            if (!$Product) {
                // Handle case when product is not found
                return redirect()->back()->with('error', 'Product not found.');
            }
        $cart = Cart::where('user_id', $user->id)
                  ->where('product_id', $Product->id)
                  ->first();
        // dd($cart);
        if($cart)
        {
            if($cart->quantity < 2)
            {
                return redirect()->back()->with('error','Item quantity can not less than 1.');
            }
                $cart->quantity -= 1;
                // dd($cart->quantity);
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
            // dd($cartItems->find($id));
        if(isset($cartItems)){
            $cartItems->find($id)->delete();
            session(['key' => $cartItems->count()-1]);
            return redirect()->back()->with('success', 'Item Decreased.');
        }
            // $removeItem->delete();
        // dd('userCart'.$id);
    }
    public function cartView()
    {
        $this->carting();
        // dd('userCart');
        $user = Auth::user();
        // $cart = Cart::where('user_id',$user->id)->get();
        // // $product = Product::
        // dd($cart->product_id);
        $carts = Cart::where('user_id', $user->id)
                     ->with('product') // eager load product details
                     ->get();
        // dd($carts);
        return view('userCart',compact('carts'));
    }
    public function updateAddress()
    {
        // dd('userAddress');
        $user = Auth::user();
        // dd($user->id);
        $address = Addre::where('user_id', $user->id)->first();
        // dd(isset($address));
        if(isset($address))
        {
            // dd($address);
            return view('userAddress',compact('address'));
        }
        return view('userAddress');
    }
    public function cartProceed(Request $request)
    {
        // dd(Auth::id());
        $user = Auth::user();
        // dd($user->id);
        $address = Addre::where('user_id', $user->id)->first();
        // dd($address);
        if(isset($address)){
            $address->update([
                'address' => $request->address,
                'pincode' => $request->pincode,
            ]);
            return view('Order/paymentMethod');
        }
        else{
        // dd($user->id);
        // $Address = Addre::where('user_id', $user->id);
        // dd(isset($Address));
        // if(isset($Address))
        // {
        //     return view('userAddress',compact('Address'));
        // }
        // $request->validate([
        //     'address' => 'required|string|max:255',
        //     'pincode' => 'required|numeric|min:100000|max:999999',
        // ]);
        $newAddress = new Addre();
        $newAddress->user_id = $user->id;
        $newAddress->address = $request->address;
        $newAddress->pincode = $request->pincode;
        $newAddress->save();
        // $this->orderNow();
        return redirect()->route('paymentMethod');
        }
    }
    public function paymentMethod()
    {
        return view('Order/paymentMethod');
        // return redirect()->route('orderNow')->with('success','Cash On Delevery');
    }
    public function orderNow()
    {
        return view('orderSuccess');
    }
}

