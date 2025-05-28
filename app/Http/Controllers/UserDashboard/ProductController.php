<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Addre;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewVote;
use App\Models\SearchLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
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
    public function product(Request $request)
{
    $this->carting();

    try {
        $start = microtime(true);

        $query = $request->input('query');
        $ProductsQuery = Product::query();

        if ($query) {
            $ProductsQuery->where('name', 'like', '%' . $query . '%');
        }

        $Products = $ProductsQuery->get();
       

        if ($query && $Products->isEmpty()) {
            $Products = Product::inRandomOrder()->limit(4)->get();
        }

        $end = microtime(true);
        $duration = round(($end - $start) * 1000); // milliseconds

        if ($query) {
            SearchLog::create([
                'user_id' => Auth::id(),
                'query' => $query,
                'product_ids' => json_encode($Products->pluck('id')->toArray()), // 🛠️ important!
                'results_count' => $Products->count(),
                'time_taken' => $duration,
                'device_info' => $request->ip(),
            ]);
        }

        $cartProductIds = Auth::check()
            ? Cart::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        return view('home', compact('Products', 'cartProductIds', 'query'));

    } catch (\Exception $e) {
        return view('error')->with('issue', $e);
    }
}



    public function detail($id)
    {
        $this->carting();
        
        $product = Product::with(['images', 'reviews.user'])->findOrFail($id);
        // dd($product->images);
        $inCart = false;
        $inCart = Cart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        $averageRating = round($product->reviews->avg('rating'), 1);

        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $id)
                                ->limit(4)
                                ->get();

        $reviews = $product->reviews()
            ->withCount([
                'likes as likes_count',
                'dislikes as dislikes_count'
            ])
            ->with(['userVote' => function ($query) {
                $query->where('user_id', auth()->id());
            }, 'user'])
            ->get();

        return view('productDetailed', compact('product', 'relatedProducts', 'averageRating', 'reviews', 'inCart'));
    }

     
    // Review Controller functions start

    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        // dd($product->reviews);
        $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->comment ?? 'N/A  ',
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    public function like($id)
    {
        $review = Review::findOrFail($id);
        $review->likes = $review->likes + 1;
        $review->save();

        return response()->json(['likes' => $review->likes]);
    }

    public function dislike($id)
    {
        $review = Review::findOrFail($id);
        $review->dislikes = $review->dislikes + 1;
        $review->save();

        return response()->json(['dislikes' => $review->dislikes]);
    }

    public function vote(Request $request)
    {
        $validated = $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'vote' => 'required|in:like,dislike',
        ]);

        $user = auth()->user();
        $existingVote = ReviewVote::where('review_id', $validated['review_id'])
            ->where('user_id', $user->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote === $validated['vote']) {
                // Remove vote
                $existingVote->delete();
            } else {
                // Update vote
                $existingVote->update(['vote' => $validated['vote']]);
            }
        } else {
            ReviewVote::create([
                'user_id' => $user->id,
                'review_id' => $validated['review_id'],
                'vote' => $validated['vote'],
            ]);
        }

        $review = Review::withCount([
            'likes as likes_count',
            'dislikes as dislikes_count'
        ])->findOrFail($validated['review_id']);

        return response()->json([
            'likes' => $review->likes_count,
            'dislikes' => $review->dislikes_count,
            'user_vote' => ReviewVote::where('user_id', $user->id)
                ->where('review_id', $review->id)
                ->value('vote')
        ]);
    }



// Reviews functions end
    
    public function addTocart($id)
    {
        $this->carting();
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
        $this->carting();
        $user = Auth::user();

        $carts = Cart::where('user_id', $user->id)
                    ->with('product.category.charges') // Eager load category and its charges
                    ->get();

        $categoryName = [];
        $idDetail = [];
        $cartDetails = [];

        $totalProductAmount = 0;
        $totalExtraCharges = 0;

        foreach ($carts as $cart) {
            $product = $cart->product;
            $category = $product->category;

            $productAmount = $product->price * $cart->quantity;
            $extraCharge = 0;
            $appliedCharges = [];

            if (!$user->is_charge_exempt && $category && $category->charges) {
                foreach ($category->charges as $charge) {
                    if (!$charge->is_active) continue;

                    switch ($charge->charge_type) {
                        case 'gst':
                            $amount = $productAmount * ($charge->amount / 100);
                            $extraCharge += $amount;
                            $appliedCharges['gst'] = $amount;
                            break;

                        case 'platform_charge':
                            $extraCharge += $charge->amount;
                            $appliedCharges['platform_charge'] = $charge->amount;
                            break;

                        case 'delivery_charge':
                            $extraCharge += $charge->amount;
                            $appliedCharges['delivery_charge'] = $charge->amount;
                            break;

                        case 'cod_charge':
                            $extraCharge += $charge->amount;
                            $appliedCharges['cod_charge'] = $charge->amount;
                            break;

                        default:
                            $extraCharge += $charge->amount;
                            $appliedCharges[$charge->charge_type] = $charge->amount;
                            break;
                    }
                }
            }

            $categoryName[] = $category->name ?? 'N/A';
            $idDetail[] = $product->id;

            $cartDetails[] = [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'image' => $product->image,
                'qty' => $cart->quantity,
                'base_price' => $product->price,
                'extra_charges' => $appliedCharges, // show detailed breakdown
                'total_with_charges' => $productAmount + $extraCharge,
            ];



            $totalProductAmount += $productAmount;
            $totalExtraCharges += $extraCharge;
        }

        $cartSummary = [
            'total_product_amount' => $totalProductAmount,
            'total_extra_charges' => $user->is_charge_exempt ? 0 : $totalExtraCharges,
            'grand_total' => $user->is_charge_exempt ? $totalProductAmount : $totalProductAmount + $totalExtraCharges,
            'user_exempt' => $user->is_charge_exempt,
        ];

        return view('userCart', compact('carts', 'categoryName', 'idDetail', 'cartDetails', 'cartSummary'));
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
        $user = Auth::user(); // Full user object instead of just ID

        $orders = Cart::where('user_id', $user->id)->get();
        $address = Addre::where('user_id', $user->id)->first();
        $orderId = $this->generateUniqueCode();
        $indiaTime = now('Asia/Kolkata');
        $tomorrow = $indiaTime->copy()->addDay();

        foreach ($orders as $order) {
            $product = Product::find($order->product_id);
            
            if ($product && $address) {
                $category = $product->category;
                $charges = $category->charges()->where('is_active', true)->get()->keyBy('charge_type');

                $baseAmount = $product->price * $order->quantity;
                $extraCharges = 0;

                if (!$user->is_charge_exempt) {
                    if ($charges->has('gst')) {
                        $extraCharges += $baseAmount * ($charges['gst']->amount / 100);
                    }
                    if ($charges->has('platform_charge')) {
                        $extraCharges += $charges['platform_charge']->amount;
                    }
                    if ($charges->has('delivery_charge')) {
                        $extraCharges += $charges['delivery_charge']->amount;
                    }
                    if ($request->payment_method == 'cod' && $charges->has('cod_charge')) {
                        $extraCharges += $charges['cod_charge']->amount;
                    }
                }

                $totalAmount = $baseAmount + $extraCharges;

                $confirm = new Payment();
                $confirm->user_id = $user->id;
                $confirm->product_id = $product->id;
                $confirm->qty = $order->quantity;
                $confirm->amount = $totalAmount;
                $confirm->payment_mode = $request->payment_method;
                $confirm->order_date = $indiaTime;
                $confirm->delevery_date = $tomorrow;
                $confirm->orderid = $orderId;
                $confirm->save();
            }
        }

        Cart::where('user_id', $user->id)->delete();
        $this->carting();

        return redirect()->route('orderNow')->with('success', 'Your Order is Confirmed. Order ID: ' . $orderId);

    }
    public function orderNow()
    {
        return view('orderSuccess');
    }
    public function mainance()
    {
        return View('maintainance');
    }
    
}

