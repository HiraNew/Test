<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first(); // or any user id who is creator

        // Create Category
        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => Str::slug('Fashion'),
            'icon' => 'images/fashion.png',
            'description' => 'All fashion related products',
            'created_by' => $admin->id,
            'status' => 0,
        ]);

        // Create Subcategories for Fashion
        Subcategory::create([
            'category_id' => $fashion->id,
            'name' => 'Men Clothing',
            'slug' => Str::slug('Men Clothing'),
            'icon' => 'images/men-clothing.png',
            'created_by' => $admin->id,
            'status' => 0,
        ]);

        Subcategory::create([
            'category_id' => $fashion->id,
            'name' => 'Women Clothing',
            'slug' => Str::slug('Women Clothing'),
            'icon' => 'images/women-clothing.png',
            'created_by' => $admin->id,
            'status' => 0,
        ]);

        // Add more categories and subcategories similarly...
    }
}
