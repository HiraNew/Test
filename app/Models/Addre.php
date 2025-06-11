<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addre extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'address', 'pincode', 'status',
        'city', 'state', 'country',
        'mobile_number', 'alt_mobile_number',
        'postal_code'
    ];
}
