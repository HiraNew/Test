<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function productApi(Request $request)
    {
        try {
            $products = Product::with(['tags', 'reviews', 'wishlists'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            \Log::error('API Product Fetch Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
