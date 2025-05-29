<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\RecentView;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecentViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            $randomProducts = $products->random(min(5, $products->count()));

            foreach ($randomProducts as $product) {
                RecentView::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $product->id],
                    ['viewed_at' => now()->subDays(rand(0, 30))]
                );
            }
        }
    }
}
