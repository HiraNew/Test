<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Specify the fillable fields for mass assignment
    protected $fillable = ['name', 'slug', 'description', 'created_by'];

    /**
     * Define the relationship between Category and User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function charges()
    {
        return $this->hasMany(CategoryCharge::class);
    }
    // public function charges()
    // {
    //     return $this->hasMany(Extra::class);
    // }
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }



}

