<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'ldescription', 'sdescription','price', 'quantity', 'image', 'status', 'created_by', 'category_id', 'subcategory_id', // existing fields
        'color', 'size', 'weight',
        'extra1', 'extra2', 'extra3', 'extra4', 'extra5','vendor_id',
    ];


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
    // Accessor for average rating
    public function getAverageRatingAttribute()
    {
        return $this->reviews_avg_rating;
    }

    // Accessor for wishlist count
    public function getWishlistCountAttribute()
    {
        return $this->wishlists_count ?? 0;
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    // Product.php
    public function extraImages()
    {
        return $this->hasMany(ProductImage::class);
    }


    protected static function booted()
    {
        static::deleting(function ($product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        });
    }







}
