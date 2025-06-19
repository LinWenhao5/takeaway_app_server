<?php

namespace App\Http\Controllers\Cart;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CartApiController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @OA\Post(
     *     path="/api/cart/add",
     *     summary="Add a product to the cart",
     *     tags={"Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID of the product to add"),
     *             @OA\Property(property="quantity", type="integer", example=2, description="Quantity of the product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product added to cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product added to cart successfully."),
     *             @OA\Property(property="customerId", type="integer", example=123),
     *             @OA\Property(property="productId", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to add product to cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to add product to cart."),
     *             @OA\Property(property="error", type="string", example="Some error message")
     *         )
     *     )
     * )
     */
    public function addToCart(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
            ]);

            $customerId = $this->getAuthenticatedCustomerId();

            $this->cartService->addToCart(
                $customerId,
                $validated['product_id'],
                $validated['quantity']
            );

            return response()->json([
                'message' => 'Product added to cart successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to add product to cart.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cart",
     *     summary="Retrieve the cart content",
     *     tags={"Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cart retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cart retrieved successfully."),
     *             @OA\Property(property="customerId", type="integer", example=123),
     *             @OA\Property(property="cart", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve cart."),
     *             @OA\Property(property="error", type="string", example="Some error message")
     *         )
     *     )
     * )
     */
    public function getCart()
    {
        try {
            $customerId = $this->getAuthenticatedCustomerId();

            $cartData = $this->cartService->getCartDetails($customerId);

            return response()->json([
                'message' => 'Cart retrieved successfully.',
                'cart' => $cartData['cart'],
                'total_price' => $cartData['total_price'],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve cart.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *    path="/api/cart/remove",
     *    summary="Remove a product from the cart",
     *    tags={"Cart"},
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *      required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID of the product to remove")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product removed from cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product removed from cart successfully."),
     *             @OA\Property(property="customerId", type="integer", example=123),
     *             @OA\Property(property="productId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to remove product from cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to remove product from cart."),
     *             @OA\Property(property="error", type="string", example="Some error message")
     *         )
     *     )
     * )
     */
    public function removeFromCart(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|integer',
            ]);

            $customerId = $this->getAuthenticatedCustomerId();

            $this->cartService->removeFromCart(
                $customerId,
                $validated['product_id']
            );

            return response()->json([
                'message' => 'Product removed from cart successfully.',
                'customerId' => $customerId,
                'productId' => $validated['product_id'],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to remove product from cart.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}