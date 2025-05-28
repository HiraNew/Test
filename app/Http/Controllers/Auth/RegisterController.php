<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'number' => ['required', 'digits:10', Rule::unique('users', 'number')->ignore(Auth::id())],
            'otp' => ['required', 'digits:4', function ($attribute, $value, $fail) use ($data) {
            $storedOtp = Session::get('otp_' . $data['number']);

            if (!$storedOtp || $storedOtp != $value) {
                $fail('Invalid OTP. Please try again.');
            }
         }],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? '',
            'number' => $data['number'],
            'verified' => '1',
        ]);
    }
    public function sendOtp(Request $request)
    {
        $request->validate([
            'number' => 'required|digits:10'
        ]);

        $otp = rand(1000, 9999);
        dump($otp);

        // Store OTP in session (or use Redis/DB)
        Session::put('otp_' . $request->number, $otp);

        // Simulate OTP sending (replace with actual SMS API call)
        logger("OTP for {$request->number} is: {$otp}");

        return response()->json(['success' => true]);
    }
}
