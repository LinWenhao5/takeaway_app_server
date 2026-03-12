<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use App\Features\Order\Models\Order;

class PickupOrderStrategy extends AbstractOrderCreationStrategy
{
    public function __construct(CartService $cartService)
    {
        parent::__construct($cartService);
    }

    public function validateOrder($totalPrice, $addressId): void
    {
        // No specific validation for pickup orders
    }

    protected function calculateFinalPrice($totalPrice): float
    {
        return $totalPrice;
    }

    protected function buildOrderData($customerId, $addressId, $reserveTime, $note, $finalPrice): array
    {
        return [
            'customer_id' => $customerId,
            'status' => OrderStatus::Unpaid,
            'total_price' => $finalPrice,
            'delivery_fee' => 0,
            'address_id' => null,
            'address_snapshot' => null,
            'order_type' => OrderType::PICKUP,
            'reserve_time' => $reserveTime,
            'note' => $note,
        ];
    }

    protected function calculateVatSummary(Order $order): array
    {
        $vatSummary = [];
        foreach ($order->products as $product) {
            $vatName = $product->pivot->vat_name ?? 'No VAT';
            $vatAmount = $product->pivot->vat_amount * $product->pivot->quantity;
            $productAmount = $product->pivot->price * $product->pivot->quantity;

            if (!isset($vatSummary[$vatName])) {
                $vatSummary[$vatName] = [
                    'vat_total' => 0,
                    'product_total' => 0,
                ];
            }
            $vatSummary[$vatName]['vat_total'] += $vatAmount;
            $vatSummary[$vatName]['product_total'] += $productAmount;
        }
        return $vatSummary;
    }
}