<?php

namespace App\Features\Printer\Services;

use Carbon\Carbon;
use Intervention\Image\Image;

class KitchenImageGenerator extends BaseReceiptImageGenerator
{
    protected function render(Image $img, array $orderData, int &$y): void
    {
        // 1. 厨房单页眉标识
        $this->addText($img, '** KEUKEN BON **', 38, 'center', $y);
        $y += 60;

        // 2. 核心大单号与点单类型
        $orderId   = $orderData['daily_sequence'] ?? 'UNKNOWN';
        $orderType = strtoupper($orderData['order_type'] ?? 'PICKUP'); 
        $typeLabelDutch = ($orderType === 'DELIVERY') ? 'BEZORG' : 'AFHAAL';

        $this->addText($img, "#{$orderId} - {$typeLabelDutch}", 48, 'center', $y);
        $y += 75;
        $this->drawSeparator($img, $y);
        $y += 40;

        // 3. 时间判定
        $createdAt = Carbon::parse($orderData['created_at'])->setTimezone('Europe/Amsterdam')->format('d-m-Y H:i');
        $this->addText($img, "Besteld op: {$createdAt}", 24, 'left', $y);
        $y += 45;

        $customerName = $orderData['customer_snapshot']['name'] ?? ($orderData['address_snapshot']['name'] ?? 'Klant');
        $this->addText($img, "Klant Naam: {$customerName}", 24, 'left', $y);
        $y += 45;

        if (!empty($orderData['reserve_time'])) {
            $reserveTime = Carbon::parse($orderData['reserve_time'])->setTimezone('Europe/Amsterdam')->format('d-m-Y H:i');
            $timeLabel   = ($orderType === 'DELIVERY') ? "BEZORGEN OM" : "AFHALEN OM";
            $this->addText($img, "{$timeLabel}: {$reserveTime}", 30, 'left', $y);
            $y += 55;
        } else {
            $y += 10;
        }
        $this->drawSeparator($img, $y);
        $y += 40;

        // 4. 菜品与价格列表
        $this->addText($img, "ITEMS", 24, 'left', $y);
        $y += 40;

        if (isset($orderData['products_snapshot']) && is_array($orderData['products_snapshot'])) {
            foreach ($orderData['products_snapshot'] as $product) {
                $qty = (int)$product['quantity'];
                $finalPrice = (float)$product['final_price'];
                $rowTotal = $finalPrice * $qty; 

                $this->addText($img, "{$qty}x {$product['name']}", 34, 'left', $y);
                $this->addText($img, "EUR " . number_format($rowTotal, 2, ',', '.'), 24, 'right', $y + 8);
                $y += 60; 
            }
        }
        $this->drawSeparator($img, $y);
        $y += 40;

        // 5. 客户备注
        $this->addText($img, "Opmerking / Note:", 26, 'left', $y);
        $y += 40;
        $this->addText($img, !empty($orderData['note']) ? $orderData['note'] : "Geen", 28, 'left', $y);
        
        $y += 80; 
    }
}