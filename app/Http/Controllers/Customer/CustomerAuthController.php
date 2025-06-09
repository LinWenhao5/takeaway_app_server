<?php

namespace App\Http\Controllers\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\CaptchaMail;

class CustomerAuthController extends Controller
{
    /**
     * Register a new customer.
     */
    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8',
            'captcha' => 'required|string',
        ]);

        // Get the cached captcha for the email
        $cachedCaptcha = Cache::get('captcha_' . $request->email);

        //Check if the captcha is valid
        if (!$cachedCaptcha || $cachedCaptcha !== $request->captcha) {
            return response()->json([
                'message' => 'Invalid captcha.',
            ], 400);
        }

        // Create a new customer
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        Cache::forget('captcha_' . $request->email);

        // Return a success response
        return response()->json([
            'message' => 'Customer registered successfully!',
            'customer' => $customer,
        ], 201);
    }

    /**
     * Login a customer.
     */
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find the customer by email
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // Generate a token for the customer
        $token = $customer->createToken('auth_token')->plainTextToken;

        // Return a success response with the token
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
        ], 200);
    }

    public function generateCaptcha(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $captchaCode = rand(100000, 999999);

        Cache::put('captcha_' . $request->email, $captchaCode, now()->addMinutes(5));

        Mail::to($request->email)->queue(new CaptchaMail($captchaCode));

        return response()->json([
            'message' => 'Captcha sent successfully!',
        ], 200);
    }
}
