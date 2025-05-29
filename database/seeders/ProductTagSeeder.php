<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $tags = Tag::all();

        foreach ($products as $product) {
            $randomTags = $tags->random(rand(1, 3));

            foreach ($randomTags as $tag) {
                Tag::create([
                    'product_id' => $product->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
    }
}
