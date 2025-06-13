<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addre extends Model
{
    use HasFactory;
    protected $table = 'addres'; // Confirmed table name
      protected $fillable = [
        'user_id',
        'address',
        'pincode',
        'status',
        'city_id',
        'state_id',
        'country_id',
        'village_id',
        'mobile_number',
        'alt_mobile_number',
        'postal_code',
        'payment_id',
        'landmark', // Include if column exists
    ];
     // Country relationship
     // Country relationship
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

  public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }



}
