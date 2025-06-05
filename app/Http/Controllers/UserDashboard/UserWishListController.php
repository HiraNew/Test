<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWishListController extends Controller
{
        public function index()
        {
            $wishlists = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

            $cartProductIds = Auth::check()
                ? Cart::where('user_id', Auth::id())->pluck('product_id')->map(fn($id) => (int) $id)->toArray()
                : [];


            // Wishlist product IDs
            // $wishlistProductIds = Auth::check()
            //     ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            //     : [];

            return view('Wishlist.userWishList', compact('wishlists', 'cartProductIds'));
        }
}
