<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'number', 'verified', 'status'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function categories()
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function reviewVotes()
    {
        return $this->hasMany(ReviewVote::class);
    }
   public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id')->withTimestamps();
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
    // In User.php
    public function recentViews() {
        return $this->hasMany(RecentView::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }








}
