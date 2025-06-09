<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'query',
        'results_count',
        'time_taken',
        'product_ids', // 👈 add this
        'device_info',
        'created_at',
    ];

}
