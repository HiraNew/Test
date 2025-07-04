<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Mail\LowStockAlertMail;
use App\Models\Addre;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RecentView;
use App\Models\Review;
use App\Models\ReviewVote;
use App\Models\SearchLog;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Subcategory;
use App\Models\Village;
use Illuminate\Support\Facades\Session;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\LowStockNotification;

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

        $notifications = Notification::with('creator')
                        ->where('user_id', $user->id)
                        ->orderByDesc('created_at')
                        ->get();

        // Mark as read
        Notification::where('user_id', $user->id)
                    ->where('status', '!=', 1)
                    ->update([
                        'status' => 1,
                        'updated_at' => now(),
                    ]);

        return view('userNotification',compact('notifications'));
    }
    public function getNotificationsData()
    {
        $user = Auth::user();

        $notifications = Notification::with(['creator', 'product']) // Include product
                        ->where('user_id', $user->id)
                        ->orderByDesc('created_at')
                        ->get();

        // Mark as read
        Notification::where('user_id', $user->id)
                    ->where('status', '!=', 1)
                    ->update([
                        'status' => 1,
                        'updated_at' => now(),
                    ]);

        // Return JSON response
        return response()->json($notifications);
    }



    public function categoryView($slug)
    {
        // Try to find category by slug
        $categories = Category::where('slug', $slug)->first();
        
        // Try to find subcategory by slug if category not found
        $subcategory = null;
        if (!$categories) {
            $subcategory = Subcategory::where('slug', $slug)->firstOrFail();
            // dd($subcategory);
        }

        // Get products based on category or subcategory
        if ($categories) {
            $products = Product::with(['tags', 'reviews', 'wishlists'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->where('category_id', $categories->id)
                ->where('status', 'active')
                ->paginate(20);
        } else {
            $products = Product::with(['tags', 'reviews', 'wishlists'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->where('subcategory_id', $subcategory->id)
                ->where('status', 'active')
                ->paginate(20);
                // dd($products);
        }

        // Get cart product IDs if logged in
        $cartProductIds = Auth::check()
            ? Cart::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        // Get wishlist product IDs if logged in
        $wishlistProductIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        // Get categories list for navigation or sidebar
        // $categoriesList = Category::select('id', 'name', 'slug', 'icon', 'description')
        //     ->where('status', 0)
        //     ->orderBy('name')
        //     ->get();
        $categoriesList = Category::with('subcategories')
            ->where('status', 0)
            ->orderBy('name')
            ->get();    

        // Pass either category or subcategory to the view
        return view('layouts.category-products', compact(
            'products',
            'categories',
            'subcategory',
            'cartProductIds',
            'wishlistProductIds',
            'categoriesList'
        ));
    }



    public function product(Request $request)
    {
        
        $this->carting(); // Loads or prepares cart session data
        

        try {
            $start = microtime(true);
            $query = $request->input('query');
            // dd($query);

            // Start with base query
            $ProductsQuery = Product::with(['tags', 'reviews', 'wishlists'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->where('status', 'active');
                // dd($ProductsQuery->get());

            if ($query) {
                $ProductsQuery->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('sdescription', 'like', '%' . $query . '%')
                    ->orWhere('color', 'like', '%' . $query . '%')
                    ->orWhere('size', 'like', '%' . $query . '%')
                    ->orWhere('weight', 'like', '%' . $query . '%')
                    ->orWhereHas('tags', function ($tagQuery) use ($query) {
                        $tagQuery->where('name', 'like', '%' . $query . '%');
                    });
                });

                // dd($ProductsQuery->get());
            }

            $Products = $ProductsQuery->paginate(20);
            // dd($Products);

            // If query is present but no results, fallback to random
            if ($query && $Products->isEmpty()) {
                $Products = Product::with(['tags', 'reviews', 'wishlists'])
                    ->inRandomOrder()
                    ->limit(4)
                    ->get();
            }
            $matchedProductIds = $Products instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $Products->pluck('id')->toArray()
            : $Products->pluck('id')->toArray();
            // Log the search if query was used
            if ($query) {
                $duration = round((microtime(true) - $start) * 1000); // in ms

                SearchLog::create([
                    'user_id' => auth()->id(),
                    'query' => $query,
                    'product_ids' => json_encode($matchedProductIds), // Save as JSON
                    'results_count' => $Products->count(),
                    'time_taken' => $duration,
                    'device_info' => $request->ip(),
                    'created_at' => now(),
                ]);
            }

            // Cart product IDs
            $cartProductIds = Auth::check()
                ? Cart::where('user_id', Auth::id())->pluck('product_id')->toArray()
                : [];

            // Wishlist product IDs
            $wishlistProductIds = Auth::check()
                ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
                : [];
            // Recent views
            // if (Auth::check()) {
            //     foreach ($Products as $product) {
            //         RecentView::updateOrCreate(
            //             ['user_id' => Auth::id(), 'product_id' => $product->id],
            //             ['viewed_at' => now('Asia/Kolkata')]
            //         );
            //     }

                $recentViews = Product::whereIn('id', RecentView::where('user_id', Auth::id())
                    ->orderByDesc('viewed_at')
                    ->limit(8)
                    ->pluck('product_id'))
                    ->with(['tags', 'reviews', 'wishlists'])
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->get();
            // } else {
            //     $recentViews = collect(); 
            // }
             // carousle
            $carouselItems = [
                (object)['image' => 'papaya.png', 'caption' => 'Welcome to Slide 1'],
                (object)['image' => 'mango.webp', 'caption' => 'Explore Slide 2'],
                (object)['image' => 'apple.png'], // No caption
            ];
            $categories = Category::with('subcategories')
            ->where('status', 0)
            ->orderBy('name')
            ->get();
            if(isset($query)){
                return view('home', compact('Products', 'cartProductIds', 'wishlistProductIds', 'query', 'recentViews', 'categories'));
            }

            return view('home', compact('Products', 'cartProductIds', 'wishlistProductIds', 'query', 'recentViews', 'carouselItems', 'categories'));

        } catch (\Exception $e) {
            \Log::error('Product Search Error: ' . $e->getMessage(), ['exception' => $e]);
            return view('error')->with('issue', $e->getMessage());
        }
    }





    public function detail($id)
    {
        $this->carting();
        $wishlistProductIds = [];
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                                    ->pluck('product_id')
                                    ->toArray();

        $product = Product::with(['images', 'reviews.user'])->where('status', 'active')->findOrFail($id);
        RecentView::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ],
            [
                'viewed_at' => Carbon::now('Asia/Kolkata') // or just now() if you're using global timezone config
            ]
        );
        // color varient on detail page
        $colorVariants = Product::where('name', $product->name)
        ->where('id', '!=', $product->id)
        ->select('color', DB::raw('MIN(id) as id')) // Just color & ID
        ->groupBy('color')
        ->get()
        ->map(function ($item) {
            return Product::find($item->id); // Get full product
        });



        $userId = auth()->id();

        $inCart = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();
        $averageRating = round($product->reviews->avg('rating'), 1);
        // dd($averageRating);

        $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $id)
        ->with('reviews') // Add this!
        ->limit(4)
        ->get()
        ->map(function ($prod) use ($userId) {
            $prod->isInWishlist = $userId ? $prod->wishlists()->where('user_id', $userId)->exists() : false;
            $prod->averageRating = round($prod->reviews->avg('rating'), 1); // 👈 Add this line
            return $prod;
        });

        $reviews = $product->reviews()
            ->withCount(['likes as likes_count', 'dislikes as dislikes_count'])
            ->with(['userVote' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }, 'user'])
            ->get();

        // Recently viewed logic
        $recentlyViewed = session()->get('recently_viewed', []);
        $recentlyViewed = array_diff($recentlyViewed, [$id]);
        array_unshift($recentlyViewed, $id);
        $recentlyViewed = array_slice($recentlyViewed, 0, 6);
        session()->put('recently_viewed', $recentlyViewed);

        $recentlyViewedProducts = Product::whereIn('id', $recentlyViewed)
        ->where('id', '!=', $id)
        ->with('reviews') // Add this!
        ->get()
        ->sortBy(function ($p) use ($recentlyViewed) {
            return array_search($p->id, $recentlyViewed);
        })
        ->map(function ($prod) use ($userId) {
            $prod->isInWishlist = $userId ? $prod->wishlists()->where('user_id', $userId)->exists() : false;
            $prod->averageRating = round($prod->reviews->avg('rating'), 1); // 👈 Add this line
            return $prod;
        });

        return view('productDetailed', compact(
            'product',
            'relatedProducts',
            'averageRating',
            'reviews',
            'inCart',
            'recentlyViewedProducts',
            'wishlistProductIds',
            'colorVariants'
        ));
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
    
// Item added To cart or quantity increased
    public function addTocart($id) 
    {
        try{
            $this->carting();
            $user = Auth::user();
            
            $Product = Product::find($id);
            // dd($Product->quantity);
            if (!$Product) {
                // Handle case when product is not found
                return redirect()->back()->with('error', 'Product not found.');
            }
            $cart = Cart::where('user_id', $user->id)
                  ->where('product_id', $Product->id)
                  ->first();  // This finds the cart item if it exists for the user and product
           
            
            if($cart)
            {
                if ($cart->quantity >= $Product->quantity) {
                    return redirect()->back()->with('error', 'Not enough stock available. Only ' . $Product->quantity . ' in stock.');
                }

                if($cart->quantity > 9)
                {
                 return redirect()->back()->with('error','Can,t Add More Than 10 Quantity of Any Item.');
                }
                $cart->quantity += 1;
                $cart->save();
                return redirect()->back()->with('insert','Total '. $cart->quantity. ' Quantity Added To '. $Product->name);
                
            }
             // If this is a new item in cart
        if ($Product->quantity < 1) {
            return redirect()->back()->with('error', 'This product is currently out of stock.');
        }
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->product_id = $Product->id;
            $cart->save();
            return redirect()->back()->with('insert',' '. $Product->name. ' Added To Your Cart');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the product to your cart: ' . $e->getMessage());
        }

    }
    // item quantity decrease
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
            return redirect()->back()->with('success', 'Item quantity Decreased.');
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
            return redirect()->back()->with('error', 'Item Removed.');
        }
    }
    public function cartView()
    {
        $this->carting();
        $user = Auth::user();

        $carts = Cart::where('user_id', $user->id)
                    ->with('product.category.charges')
                    ->get();

        $cartDetails = [];
        $totalProductAmount = 0;
        $totalExtraCharges = 0;
        $hasStockIssue = false;
        $stockMessages = [];

        foreach ($carts as $cart) {
            $product = $cart->product;
            $category = $product->category;
            $vendorId = $product->vendor_id;

            // Get only charges for this vendor
            $charges = $category->charges()
                ->where('is_active', true)
                ->where(function ($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                    // OR use vendor_id = null for admin-defined charges if needed
                    // ->orWhereNull('vendor_id');
                })->get();

            // Check stock availability
            $isStockExceeded = false;
            if ($cart->quantity > $product->quantity) {
                $isStockExceeded = true;
                $hasStockIssue = true;

                // Adjust to max available stock
                $cart->quantity = $product->quantity;
                $cart->save();

                $stockMessages[] = "Quantity for '{$product->name}' adjusted to available stock ({$product->quantity}).";
            }

            $productAmount = $product->price * $cart->quantity;
            $extraCharge = 0;
            $appliedCharges = [];

            // Extra charges
            if (!$user->is_charge_exempt) {
                foreach ($charges as $charge) {
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

            $cartDetails[] = [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'image' => $product->image,
                'qty' => $cart->quantity,
                'available_stock' => $product->quantity,
                'base_price' => $product->price,
                'extra_charges' => $appliedCharges,
                'total_with_charges' => $productAmount + $extraCharge,
                'stock_exceeded' => $isStockExceeded,
            ];

            $totalProductAmount += $productAmount;
            $totalExtraCharges += $extraCharge;
        }

        $cartSummary = [
            'total_product_amount' => $totalProductAmount,
            'total_extra_charges' => $user->is_charge_exempt ? 0 : $totalExtraCharges,
            'grand_total' => $user->is_charge_exempt ? $totalProductAmount : $totalProductAmount + $totalExtraCharges,
            'user_exempt' => $user->is_charge_exempt,
            'has_stock_issue' => $hasStockIssue,
        ];

        return view('userCart', compact('carts', 'cartDetails', 'cartSummary'))
            ->with('stockMessages', $stockMessages);
    }


    public function updateAddress()
    {
        $user = Auth::user(); // Full user object instead of just ID

        $orders = Cart::where('user_id', $user->id)->get();
        // 1. Quantity Check: ensure no item exceeds product stock
        foreach ($orders as $order) {
            $product = Product::find($order->product_id);
            if (!$product) {
                return back()->with('error', 'One of the products in your cart is no longer available.');
            }

            if ($order->quantity > $product->quantity) {
                return back()->with('error', "Sorry, only {$product->quantity} units of {$product->name} are available in stock.");
            }
        }
        $address = Addre::where('user_id', $user->id)->first();
        // dd($address);
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $villages = Village::all();
        // if(isset($address))
        // {
        //     return view('userAddress',compact('address'));
        // }
        return view('userAddress',compact('address','countries','states','cities','villages'));
    }
    public function cartProceed(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'village' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'mobile_number' => 'required|digits:10',
            'alt_mobile_number' => 'nullable|digits:10',
            'landmark' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        session(['temp_address' => $validated,
        'temp_address_time' => now()
        ]);
       

        // Handle dropdown or manual country
        // $countryId = $request->country !== 'manual' ? $request->country : Country::firstOrCreate(
        //     ['name' => $request->country_manual]
        // )->id;

        // $stateId = $request->state !== 'manual' ? $request->state : State::firstOrCreate(
        //     ['name' => $request->state_manual]
        // )->id;

        // $cityId = $request->city !== 'manual' ? $request->city : City::firstOrCreate(
        //     ['name' => $request->city_manual]
        // )->id;

        // $villageId = $request->village !== 'manual' ? $request->village : Village::firstOrCreate(
        //     ['name' => $request->village_manual]
        // )->id;

        // Save or update user's address
        // $address = new Addre();
        
        // $address->address = $request->address;
        // $address->pincode = $request->pincode;
        // $address->postal_code = $request->postal_code;
        // $address->mobile_number = $request->mobile_number;
        // $address->alt_mobile_number = $request->alt_mobile_number;
        // $address->landmark = $request->landmark;
        // $address->country_id = $countryId;
        // $address->state_id = $stateId;
        // $address->city_id = $cityId;
        // $address->village_id = $villageId;
        // $address->user_id = $user->id;

        // $address->save();

        return redirect()->route('paymentMethod')->with('success', 'Address saved. Proceed to payment.');
    }

    public function paymentMethod()
    {
        return view('Order/paymentMethod');
    }
    public function generateUniqueCode($length = 10, $prefix = 'DLS') {
        $randomLength = $length - strlen($prefix);

        do {
            // Generate random alphanumeric part
            $randomPart = Str::upper(Str::random($randomLength));
            $code = $prefix . $randomPart;

            // Check uniqueness in DB (example uses "orders" table and "code" column)
        } while (Payment::where('orderid', $code)->exists());

        return $code;
    }
    public function paymentMethodProceed(Request $request)
    {
        $user = Auth::user();
        $orders = Cart::where('user_id', $user->id)->get();
        // $address = Addre::where('user_id', $user->id)->first();

        if ($orders->isEmpty()) {
            return redirect()->route('cartView')->with('error', 'Your cart or address is missing.');
        }

        // $orderId = $this->generateUniqueCode();
        $indiaTime = now('Asia/Kolkata');
        $tomorrow = $indiaTime->copy()->addDay();

        // Stock check first
        foreach ($orders as $order) {
            $product = Product::find($order->product_id);
            if (!$product) {
                return back()->with('error', 'One of the products in your cart is no longer available.');
            }
            if ($order->quantity > $product->quantity) {
                return back()->with('error', "Sorry, only {$product->quantity} units of {$product->name} are available in stock.");
            }
        }

        // ✅ Transaction Start
        DB::beginTransaction();

        try {


            $addressData = Session::get('temp_address');
            $createdAt = session('temp_address_time');

            if (!$addressData || !$createdAt || now()->diffInMinutes($createdAt) > 1) {
                Session::forget('temp_address');
                Session::forget('temp_address_time');
                return redirect()->route('updateAddress')->with('error', 'Session expired. Please re-enter your address.');
            }

            foreach ($orders as $order) {
                $product = Product::find($order->product_id);

                if ($product) {
                    $category = $product->category;
                        $vendorId = $product->vendor_id;

                        $charges = $category->charges()
                            ->where('is_active', true)
                            ->where('vendor_id', $vendorId)
                            ->get()
                            ->keyBy('charge_type');


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
                    // $addressData = Session::get('temp_address');

                    // Save order/payment
                    $confirm = new Payment();
                    $confirm->user_id = $user->id;
                    $confirm->product_id = $product->id;
                    $confirm->vendor_id = $product->vendor_id; // ✅ May be null
                    $confirm->qty = $order->quantity;
                    $confirm->amount = $totalAmount;
                    $confirm->payment_mode = $request->payment_method;
                    $confirm->order_date = $indiaTime;
                    $confirm->delevery_date = $tomorrow;
                    $confirm->orderid = $this->generateUniqueCode();
                    $confirm->save();

                    // $createdAt = session('temp_address_time');

                    // if ($createdAt && now()->diffInMinutes($createdAt) > 1) {
                    //     Session::forget('temp_address');
                    //     Session::forget('temp_address_time');
                    //     return redirect()->route('updateAddress')->with('error', 'Session expired. Please re-enter your address.');
                    // }


                    // for address 
                    Addre::create([
                            'user_id' => $user->id,
                            'address' => $addressData['address'],
                            'pincode' => $addressData['pincode'],
                            'postal_code' => $addressData['postal_code'],
                            'mobile_number' => $addressData['mobile_number'],
                            'alt_mobile_number' =>$addressData['alt_mobile_number'],
                            'landmark' => $addressData['landmark'],
                            'country_id' => $addressData['country'],
                            'state_id' => $addressData['state'],
                            'city_id' => $addressData['city'],
                            'village_id' => $addressData['village'],
                            'payment_id' => $confirm->id,
                        ]);

                    // ✅ Reduce stock
                    $product->decrement('quantity', $order->quantity);
                    // Send admin email if quantity is low
                    // if ($product->quantity <= 5) {
                    //     $admin = new Admin();
                    //     $admin->email = 'lalh38023@gmail.com';
                    //     Mail::to($adminEmail)->send(new LowStockAlertMail($product));
                    //     $admin->notify(new LowStockNotification($product));
                    // }
                }
                // Notify user for there order.
                // $user->notify(new OrderPlacedNotification($confirm)); 
                // Mail::to($user->email)->send(new OrderConfirmationMail($user, $orderId));
            }

            // Clear cart after successful payment
            Cart::where('user_id', $user->id)->delete();
            $this->carting();
            Session::forget('temp_address');
            Session::forget('temp_address_time');
            DB::commit();           
            

            return redirect()->route('orderNow')->with('success', 'Your Order is Confirmed');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Order failed. Please try again. ' . $e->getMessage());
        }
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

