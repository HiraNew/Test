<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestApiController extends Controller
{
    public function indexs()
    {
        $users = User::all();
        return $users;
    }
}
