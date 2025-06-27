<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Exception;


/**
 * @OA\Info(
 *     title="Takeaway App API",
 *     version="1.0.0",
 *     description="API documentation for the Takeaway App",
 *     @OA\Contact(
 *         email="linwenhao5@gmail.com"
 *     )
 * )
 * 
 *  * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token:"
 * )
 * 
 */
abstract class Controller
{
    protected function getAuthenticatedCustomer()
    {
        $user = Auth::user();
        if (!$user) {
            abort(response()->json([
                'message' => 'Unauthorized.'
            ], 401));
        }
        return $user;
    }
}
