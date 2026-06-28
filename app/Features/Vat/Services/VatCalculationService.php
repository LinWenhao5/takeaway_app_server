<?php

namespace App\Features\Vat\Services;

class VatCalculationService
{
    public function calculateSplitVat(array $productsInput, float $subtotal, float $totalDiscount): array
    {
        $productsSnapshot = [];
        $vatSummary = [];
        $totalVatAmount = 0.00;

        // 引入累计池，用来计算最后一个商品的“尾差”
        $remainingDiscount = $totalDiscount;
        $totalItemsCount = count($productsInput);
        $currentIndex = 0;

        foreach ($productsInput as $item) {
            $currentIndex++;
            $product = $item['model'];
            $itemTotal = $product->final_price * $item['quantity'];

            // 1. 计算优惠券分摊（最后一个商品直接拿剩余的所有折扣，杜绝除不尽）
            if ($currentIndex === $totalItemsCount) {
                $allocatedDiscount = $remainingDiscount;
            } else {
                $ratio = $subtotal > 0 ? ($itemTotal / $subtotal) : 0;
                $allocatedDiscount = round($totalDiscount * $ratio, 2);
                $remainingDiscount = round($remainingDiscount - $allocatedDiscount, 2);
            }
            
            // 2. 计算实际支付金额
            $actualPaidItemTotal = max(0.00, round($itemTotal - $allocatedDiscount, 2));

            // 3. 计算增值税
            $vatRate = ($product->vatRate?->rate ?? 0) / 100;
            $vatName = $product->vatRate?->name ?? 'No VAT';
            
            // 此处保持完整精度，先不急着在总税池里累加 round 后的单品税
            $exactVatAmount = $actualPaidItemTotal - ($actualPaidItemTotal / (1 + $vatRate));
            $vatAmount = round($exactVatAmount, 2);

            // 4. 构建单品快照
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
                'vat_amount' => $vatAmount, // 表现层显示 2 位
                'quantity' => $item['quantity'],
                'category_name' => $product->category?->name,
            ];

            // 5. 按税率组装汇总盘
            if (!isset($vatSummary[$vatName])) {
                $vatSummary[$vatName] = [
                    'vat_total' => 0.00,
                    'product_total' => 0.00,
                    'exact_vat_pool' => 0.00, // 临时高精度税池
                ];
            }
            $vatSummary[$vatName]['product_total'] += $actualPaidItemTotal;
            $vatSummary[$vatName]['exact_vat_pool'] += $exactVatAmount; // 累加未裁剪的真实税额
        }

        // 6. 最终根据整个税率组的“实付总额”直接判定最终总税额
        foreach ($vatSummary as $name => $data) {
            // 直接对高精度税池整体进行四舍五入，这才是最合规的总税额
            $finalGroupVat = round($data['exact_vat_pool'], 2);
            
            $vatSummary[$name]['vat_total'] = $finalGroupVat;
            $totalVatAmount += $finalGroupVat;

            // 移除临时高精度字段
            unset($vatSummary[$name]['exact_vat_pool']);
        }

        return [
            'products_snapshot' => $productsSnapshot,
            'vat_snapshot' => $vatSummary,
            'total_vat_amount' => round($totalVatAmount, 2),
        ];
    }
}