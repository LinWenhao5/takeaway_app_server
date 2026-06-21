<?php
namespace App\Features\Customer\Controllers;

use App\Features\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\CaptchaMail;
use App\Exceptions\BusinessException;

class CustomerAuthApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/customer/register",
     *     summary="Register a new customer",
     *     tags={"Customer Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe", description="Customer's name"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com", description="Customer's email"),
     *             @OA\Property(property="password", type="string", example="password123", description="Customer's password"),
     *             @OA\Property(property="captcha", type="string", example="123456", description="Captcha code sent to the email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="REGISTRATION_SUCCESS"),
     *             @OA\Property(property="message", type="string", example="Customer registered successfully!"),
     *             @OA\Property(property="customer", type="object", description="Customer details")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request: Email already exists, invalid captcha, or other validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="EMAIL_ALREADY_EXISTS", description="Email already exists, invalid captcha, or validation failed"),
     *             @OA\Property(property="message", type="string", example="The email has already been taken or Invalid captcha.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Registration is disabled",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="REGISTRATION_DISABLED"),
     *             @OA\Property(property="message", type="string", example="Registration is currently disabled.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Invalid captcha",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="INVALID_CAPTCHA"),
     *             @OA\Property(property="message", type="string", example="Invalid captcha.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to register customer",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="REGISTRATION_FAILED"),
     *             @OA\Property(property="message", type="string", example="Failed to register customer."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {

        if (!config('app.registration_enabled')) {
            throw new BusinessException(
                'Registration is currently disabled.',
                'REGISTRATION_DISABLED',
                403
            );
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8',
            'captcha' => 'required|string',
        ]);

        $cachedCaptcha = Cache::get('captcha_' . $request->email);

        if (!$cachedCaptcha || $cachedCaptcha !== $request->captcha) {
            throw new BusinessException(
                'Invalid captcha.',
                'INVALID_CAPTCHA',
                401
            );
        } else {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            Cache::forget('captcha_' . $request->email);
            return response()->json([
                'message' => 'Customer registered successfully!',
                'customer' => $customer,
            ], 201);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/customer/login",
     *     summary="Login a customer",
     *     tags={"Customer Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john.doe@example.com", description="Customer's email"),
     *             @OA\Property(property="password", type="string", example="password123", description="Customer's password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful!"),
     *             @OA\Property(property="code", type="string", example="LOGIN_SUCCESS"),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials."),
     *             @OA\Property(property="code", type="string", example="INVALID_CREDENTIALS")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to login."),
     *             @OA\Property(property="code", type="string", example="LOGIN_FAILED"),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw new BusinessException(
                'Invalid credentials.',
                'INVALID_CREDENTIALS',
                401
            );
        }

        $maxDevices = 3;
        if ($customer->tokens()->count() >= $maxDevices) {
            $customer->tokens()->orderBy('created_at', 'asc')->first()->delete();
        }

        $token = $customer->createToken('email_login')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'code' => 'LOGIN_SUCCESS',
            'token' => $token,
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/customer/forgot-password",
     *     summary="Reset customer password using captcha",
     *     tags={"Customer Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john.doe@example.com", description="Customer's email"),
     *             @OA\Property(property="captcha", type="string", example="123456", description="Captcha code sent to the email"),
     *             @OA\Property(property="password", type="string", example="newpassword123", description="New password (min 8 chars)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid captcha",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid captcha."),
     *             @OA\Property(property="code", type="string", example="INVALID_CAPTCHA")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to reset password",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to reset password."),
     *             @OA\Property(property="code", type="string", example="PASSWORD_RESET_FAILED"),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'captcha' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $cachedCaptcha = Cache::get('captcha_' . $request->email);

        if (!$cachedCaptcha || $cachedCaptcha !== $request->captcha) {
            throw new BusinessException(
                'Invalid captcha.',
                'INVALID_CAPTCHA',
                401
            );
        }

        $customer = Customer::where('email', $request->email)->first();
        $customer->password = $request->password;
        $customer->save();

        Cache::forget('captcha_' . $request->email);

        return response()->json([
            'message' => 'Password reset successfully!',
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/customer/generate-captcha",
     *     summary="Generate a captcha code for email verification",
     *     tags={"Customer Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john.doe@example.com", description="Customer's email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Captcha sent successfully or already sent",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="CAPTCHA_SENT_SUCCESS"),
     *             @OA\Property(property="message", type="string", example="Captcha sent successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to generate captcha",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="CAPTCHA_GENERATE_FAILED"),
     *             @OA\Property(property="message", type="string", example="Failed to generate captcha."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function generateCaptcha(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if (Cache::has('captcha_' . $request->email)) {
            return response()->json([
                'code' => 'CAPTCHA_ALREADY_SENT',
                'message' => 'Captcha has already been sent. Please do not request repeatedly.',
            ], 200);
        }

        $captchaCode = rand(100000, 999999);

        Cache::put('captcha_' . $request->email, $captchaCode, now()->addMinutes(5));

        Mail::to($request->email)->queue(new CaptchaMail($captchaCode));

        return response()->json([
            'code' => 'CAPTCHA_SENT_SUCCESS',
            'message' => 'Captcha sent successfully!',
        ], 200);
    }
}
