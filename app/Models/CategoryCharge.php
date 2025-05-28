<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCharge extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'charge_type', 'amount', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
