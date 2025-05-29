<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($products as $product) {
            $randomUsers = $users->random(min(5, $users->count()));

            foreach ($randomUsers as $user) {
                ProductReview::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => rand(1, 5),
                    'review' => 'This is a sample review for product ' . $product->name,
                ]);
            }
        }
    }
}
