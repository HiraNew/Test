<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_canceled',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function address()
    {
        return $this->hasOne(Addre::class, 'payment_id', 'id');
    }
    // In Payment.php
    public function partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'delivery_partner_id');
    }



    


}
