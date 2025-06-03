<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'name', 'slug', 'icon', 'description', 'created_by', 'status'];

    // Relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
