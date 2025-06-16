<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryPartnarController extends Controller
{
    public function RegistrationForm()
    {
        return view('DeliveryPartner.RegistrationForm');
    }
}
