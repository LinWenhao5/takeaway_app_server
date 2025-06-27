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

            $customerId = $this->getAuthenticatedCustomer()->id;

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
     *             @OA\Property(property="cart", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=101, description="Product ID"),
     *                 @OA\Property(property="name", type="string", example="Delicious Sushi", description="Product name"),
     *                 @OA\Property(property="description", type="string", example="Fresh and tasty sushi.", description="Product description"),
     *                 @OA\Property(property="price", type="string", example="12.50", description="Product price"),
     *                 @OA\Property(property="image", type="string", example="https://example.com/images/sushi.jpg", description="Product image URL"),
     *                 @OA\Property(property="quantity", type="string", example="3", description="Quantity of the product"),
     *                 @OA\Property(property="subtotal", type="string", example="37.50", description="Subtotal for the product")
     *             )),
     *             @OA\Property(property="total_price", type="string", example="150.00", description="Total price of all products in the cart"),
     *             @OA\Property(property="total_quantity", type="string", example="12", description="Total quantity of all products in the cart")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve cart."),
     *             @OA\Property(property="error", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */
    public function getCart()
    {
        try {
            $customerId = $this->getAuthenticatedCustomer()->id;

            $cartData = $this->cartService->getCartDetails($customerId);

            return response()->json([
                'message' => 'Cart retrieved successfully.',
                'cart' => $cartData['cart'],
                'total_price' => $cartData['total_price'],
                'total_quantity' => $cartData['total_quantity'],
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

            $customerId = $this->getAuthenticatedCustomer()->id;

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

    /**
     * @OA\Delete(
     *    path="/api/cart/remove-quantity",
     *    summary="Remove a specific quantity of a product from the cart",
     *    tags={"Cart"},
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *      required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID of the product to remove"),
     *             @OA\Property(property="quantity", type="integer", example=2, description="Quantity to remove")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product quantity removed from cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product quantity removed from cart successfully."),
     *             @OA\Property(property="customerId", type="integer", example=123),
     *             @OA\Property(property="productId", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to remove product quantity from cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to remove product quantity from cart."),
     *             @OA\Property(property="error", type="string", example="Some error message")
     *         )
     *     )
     * )
     */
    public function removeQuantityFromCart(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
            ]);

            $customerId = $this->getAuthenticatedCustomer()->id;

            $this->cartService->removeQuantityFromCart(
                $customerId,
                $validated['product_id'],
                $validated['quantity']
            );

            return response()->json([
                'message' => 'Product quantity removed from cart successfully.',
                'customerId' => $customerId,
                'productId' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to remove product quantity from cart.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}