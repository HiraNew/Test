<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductWishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wishlists = $user->wishlists()->with('product')->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Added to wishlist', 'wishlist' => $wishlist]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $wishlist = ProductWishlist::where('user_id', $user->id)->where('product_id', $id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['message' => 'Removed from wishlist']);
        }

        return response()->json(['message' => 'Item not found'], 404);
    }
}
