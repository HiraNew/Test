<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewVote extends Model
{
    use HasFactory;
    protected $fillable = [
        'review_id',
        'user_id',
        'vote', // Optional if you're mass assigning this too
    ];
}
