<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification',
        'created_by',
        'sender_name',
        'product_id',
    ];

    // Define the relationship with the user (who received the notification)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the creator (admin or manager)
    public function creator()
    {
        return $this->belongsTo(Vendor::class, 'created_by');
    }
    
    // In your Notification model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}

