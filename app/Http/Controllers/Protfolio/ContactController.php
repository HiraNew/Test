<?php

namespace App\Http\Controllers\Protfolio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:portfolios,email',
            'mobile' => 'required|digits:10|unique:portfolios,mobile',
            'message' => 'required|string|max:1000',
        ]);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }

        // Save contact data or send email (you can enhance this)
        // Example:
        // Contact::create($validated->validated());
        Portfolio::create($request->all());

        return back()->with('success', 'Message sent!');
    }
    
}
