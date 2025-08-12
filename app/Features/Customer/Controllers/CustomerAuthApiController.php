<?php
namespace App\Features\Customer\Controllers;

use App\Features\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\CaptchaMail;
use Exception;

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
     *             @OA\Property(property="message", type="string", example="Customer registered successfully!"),
     *             @OA\Property(property="customer", type="object", description="Customer details")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid captcha",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid captcha.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to register customer",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to register customer."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'password' => 'required|string|min:8',
                'captcha' => 'required|string',
            ]);

            // Get the cached captcha for the email
            $cachedCaptcha = Cache::get('captcha_' . $request->email);

            // Check if the captcha is valid
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
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to register customer.',
                'error' => $e->getMessage(),
            ], 500);
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
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to login."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/customer/reset-password",
     *     summary="Reset password with captcha",
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
     *         response=400,
     *         description="Invalid captcha",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid captcha.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to reset password",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to reset password."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:customers,email',
                'captcha' => 'required|string',
                'password' => 'required|string|min:8',
            ]);

            $cachedCaptcha = Cache::get('captcha_' . $request->email);

            if (!$cachedCaptcha || $cachedCaptcha !== $request->captcha) {
                return response()->json([
                    'message' => 'Invalid captcha.',
                ], 400);
            }

            $customer = Customer::where('email', $request->email)->first();
            $customer->password = $request->password;
            $customer->save();

            Cache::forget('captcha_' . $request->email);

            return response()->json([
                'message' => 'Password reset successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to reset password.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
     *         description="Captcha sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Captcha sent successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to generate captcha",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to generate captcha."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function generateCaptcha(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $captchaCode = rand(100000, 999999);

            Cache::put('captcha_' . $request->email, $captchaCode, now()->addMinutes(5));

            Mail::to($request->email)->queue(new CaptchaMail($captchaCode));

            return response()->json([
                'message' => 'Captcha sent successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to generate captcha.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
