<?php

namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Coupon\Services\CouponService;
use App\Features\Vat\Services\VatCalculationService;
use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\Product\Models\Product;
use App\Features\Order\Models\Order;
use App\Features\Customer\Models\Customer;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;

abstract class AbstractOrderCreationStrategy
{
    public function __construct(
        protected CartService $cartService,
        protected CouponService $couponService,
        protected VatCalculationService $vatCalculationService
    ) {}

    public function createOrder(CreateOrderDto $createOrderDto): Order
    {
        return DB::transaction(function () use ($createOrderDto) {

            [$cart, $products, $subtotal] =
                $this->prepareCartProducts($createOrderDto->customerId);

            $this->validateOrder($createOrderDto, $subtotal);

            [$userCoupon, $couponDiscount] = $this->processCoupon($createOrderDto, $subtotal);

            $baseFinalPrice = $this->calculateFinalPrice($subtotal);
            $finalPriceWithCoupon = max(0.00, $baseFinalPrice - $couponDiscount);

            $sortedProducts = collect($products)->sortBy(function ($item) {
                return $item['model']->category?->sort_order ?? 9999;
            })->all();

            $pricingBreakdown = $this->vatCalculationService->calculateSplitVat($sortedProducts, $subtotal, $couponDiscount);

            $customer = $createOrderDto->customerId 
                ? Customer::find($createOrderDto->customerId) 
                : null;

            $orderData = array_merge(
                $this->buildOrderData($createOrderDto, $finalPriceWithCoupon),
                [
                    'customer_snapshot' => $customer ? [
                        'name' => $customer->name,
                        'email' => $customer->email,
                    ] : [
                        'name'  => 'Walk-in Customer',

                    ],
                    'coupon_id' => $userCoupon ? $userCoupon->coupon_id : null,
                    'coupon_discount_amount' => $couponDiscount,
                    'coupon_snapshot' => $userCoupon ? [
                        'user_coupon_id' => $userCoupon->id,
                        'name' => $userCoupon->name,
                        'code' => $userCoupon->code,
                        'discount_value' => $userCoupon->value
                    ] : null,
                    'products_snapshot' => $pricingBreakdown['products_snapshot'],
                    'vat_snapshot' => $pricingBreakdown['vat_snapshot'],
                    'total_vat_amount' => $pricingBreakdown['total_vat_amount'],
                ]
            );

            $order = Order::create($orderData);

            $this->attachProductsToPivot($order, $products);

            if ($userCoupon) {
                DB::table('coupon_customer')
                    ->where('id', $userCoupon->id)
                    ->update([
                        'is_used' => true,
                        'used_at' => now(),
                        'order_id' => $order->id
                    ]);
            }

            $cartId = $this->getCartId($createOrderDto->customerId);
            $this->cartService->clearCart($cartId);

            return $order;
        });
    }

    protected function processCoupon(CreateOrderDto $dto, float $subtotal): array
    {
        if (!$dto->couponCustomerId) {
            return [null, 0.00];
        }

        return $this->couponService->verifyAndCalculateDiscount(
            $dto->couponCustomerId,
            $dto->customerId,
            $subtotal,
            true
        );
    }

    /**
     * Step 1: Prepare cart + products + subtotal
     */
    protected function prepareCartProducts(?int $customerId): array
    {
        $cartId = $this->getCartId($customerId);
        $cart = $this->cartService->getCart($cartId);

        if (empty($cart)) {
            throw new BusinessException('Cart is empty.', 'CART_EMPTY');
        }

        $subtotal = 0;
        $products = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (!$product) {
                throw new BusinessException("Product not found: {$productId}", 'PRODUCT_NOT_FOUND', 404);
            }

            $products[$productId] = [
                'model' => $product,
                'quantity' => $quantity,
                'unit_price' => $product->final_price,
            ];

            $subtotal += $product->final_price * $quantity;
        }

        return [$cart, $products, $subtotal];
    }

    protected function getCartId(?int $customerId): string
    {
        return (string) $customerId;
    }


    protected function attachProductsToPivot(Order $order, array $products): void
    {
        foreach ($products as $productId => $item) {
            $product = $item['model'];
            $order->products()->attach($productId, [
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'final_price' => $product->final_price,
            ]);
        }
    }

    /**
     * Abstract methods
     */
    abstract public function validateOrder(CreateOrderDto $createOrderDto, float $subtotal): void;

    abstract protected function calculateFinalPrice(float $subtotal): float;

    abstract protected function buildOrderData(CreateOrderDto $createOrderDto, float $finalPrice): array;
}