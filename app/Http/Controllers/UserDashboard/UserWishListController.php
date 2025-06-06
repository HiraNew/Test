<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function bulkDelete(Request $request)
    {
        $productIds = $request->input('product_ids', []);
        $user = auth()->user();

        if (!is_array($productIds) || empty($productIds)) {
            return response()->json(['message' => 'No items selected'], 400);
        }

        $user->wishlists()->whereIn('product_id', $productIds)->delete();

        return response()->json(['message' => 'Selected items removed from wishlist']);
    }
    public function cartBulkDelete(Request $request)
    {
        $cartIds = $request->input('cart_ids');

        if (!$cartIds || !is_array($cartIds)) {
            return response()->json(['message' => 'No items selected.'], 400);
        }

        $userId = auth()->id();

        DB::table('carts')
            ->where('user_id', $userId)
            ->whereIn('id', $cartIds)
            ->delete();

        return response()->json(['message' => 'Selected items deleted from cart.']);
    }


}
