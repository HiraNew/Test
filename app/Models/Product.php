<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // A product can appear in many carts
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    // public function wishlists() {
    //     return $this->hasMany(Wishlist::class);
    // }
    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
    // In Product.php
    public function recentViews() {
        return $this->hasMany(RecentView::class);
    }
    public function wishlists()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }




}
