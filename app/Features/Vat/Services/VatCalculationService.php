<?php

namespace App\Features\Vat\Services;

class VatCalculationService
{
    public function calculateSplitVat(array $productsInput, float $subtotal, float $totalDiscount): array
    {
        $productsSnapshot = [];
        $vatSummary = [];
        $totalVatAmount = 0.00;

        foreach ($productsInput as $item) {
            $product = $item['model'];
            $itemTotal = $product->final_price * $item['quantity'];

            // 1. Calculate proportional coupon discount for this item
            $ratio = $subtotal > 0 ? ($itemTotal / $subtotal) : 0;
            $allocatedDiscount = round($totalDiscount * $ratio, 2);
            
            // 2. Calculate actual paid total after coupon deduction
            $actualPaidItemTotal = max(0.00, $itemTotal - $allocatedDiscount);

            // 3. Reverse-calculate VAT amount from the final paid gross price
            $vatRate = ($product->vatRate?->rate ?? 0) / 100; // e.g., 0.21 or 0.09
            $vatName = $product->vatRate?->name ?? 'No VAT';
            
            $vatAmount = round($actualPaidItemTotal - ($actualPaidItemTotal / (1 + $vatRate)), 2);
            $totalVatAmount += $vatAmount;

            // 4. Build single item snapshot
            $productsSnapshot[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'final_price' => $product->final_price,
                'is_discounted' => $product->is_discounted,
                'vat_rate' => $product->vatRate?->rate,
                'allocated_coupon_discount' => $allocatedDiscount,
                'actual_paid_total' => $actualPaidItemTotal,
                'vat_amount' => $vatAmount,
                'quantity' => $item['quantity'],
                'category_name' => $product->category?->name,
            ];

            if (!isset($vatSummary[$vatName])) {
                $vatSummary[$vatName] = [
                    'vat_total' => 0.00,
                    'product_total' => 0.00,
                ];
            }
            $vatSummary[$vatName]['vat_total'] += $vatAmount;
            $vatSummary[$vatName]['product_total'] += $actualPaidItemTotal;
        }

        // Round totals to ensure mathematical consistency
        foreach ($vatSummary as $name => $data) {
            $vatSummary[$name]['vat_total'] = round($data['vat_total'], 2);
            $vatSummary[$name]['product_total'] = round($data['product_total'], 2);
        }

        return [
            'products_snapshot' => $productsSnapshot,
            'vat_snapshot' => $vatSummary,
            'total_vat_amount' => round($totalVatAmount, 2),
        ];
    }
}