<?php

namespace App\Features\Printer\Services;

use Carbon\Carbon;
use Intervention\Image\Image;

class ReceiptImageGenerator extends BaseReceiptImageGenerator
{
    protected function render(Image $img, array $orderData, int &$y): void
    {
        // 1. 标题、大单号与点单类型 (Logo 与文字并排居中)
        $logoPath = public_path('assets/logo.png');
        $titleText = 'ZEN SUSHI';
        $titleFontSize = 46;

        if (file_exists($logoPath)) {
            list($logoWidth, $logoHeight) = getimagesize($logoPath);
            $targetHeight = 55;
            
            if ($logoHeight > $targetHeight) {
                $targetWidth = (int)(($logoWidth / $logoHeight) * $targetHeight);
                $resizedLogo = imagecreatetruecolor($targetWidth, $targetHeight);
                
                imagealphablending($resizedLogo, false);
                imagesavealpha($resizedLogo, true);
                
                $sourceLogo = imagecreatefrompng($logoPath);
                imagecopyresampled($resizedLogo, $sourceLogo, 0, 0, 0, 0, $targetWidth, $targetHeight, $logoWidth, $logoHeight);

                $logoHandle = $resizedLogo;
                $currentLogoWidth = $targetWidth;
                $currentLogoHeight = $targetHeight;
            } else {
                $logoHandle = imagecreatefrompng($logoPath);
                $currentLogoWidth = $logoWidth;
                $currentLogoHeight = $logoHeight;
            }

            $gap = 15;                       
            $textEstimatedWidth = 210;       
            $totalHeaderWidth = $currentLogoWidth + $gap + $textEstimatedWidth;
            $startX = (self::WIDTH - $totalHeaderWidth) / 2;

            $img->insert($logoHandle, (int)$startX, (int)$y);

            $textX = $startX + $currentLogoWidth + $gap;
            $textCorrectedY = $y + (($currentLogoHeight - $titleFontSize) / 2) + ($titleFontSize * 0.8);

            $img->text($titleText, (int)$textX, (int)$textCorrectedY, function($font) use ($titleFontSize) {
                $font->file(public_path(self::FONT_PATH));
                $font->size($titleFontSize);
                $font->color('#000000');
                $font->align('left');
            });

            $y += $currentLogoHeight + 50;
        } else {
            $this->addText($img, $titleText, $titleFontSize, 'center', $y);
            $y += 75;
        }

        $orderId   = $orderData['daily_sequence'] ?? 'UNKNOWN';
        $orderType = strtoupper($orderData['order_type'] ?? 'PICKUP'); 
        $typeLabelDutch = ($orderType === 'DELIVERY') ? 'Bezorg' : 'Afhaal';

        $this->addText($img, "#{$orderId} - {$typeLabelDutch}", 42, 'center', $y);
        $y += 70;
        $this->drawSeparator($img, $y);
        $y += 40;

        // 2. 订单基础信息 (下单与期望时间)
        $createdAt = Carbon::parse($orderData['created_at'])->setTimezone('Europe/Amsterdam')->format('d-m-Y H:i');
        $this->addText($img, "Besteldatum: {$createdAt}", 24, 'left', $y);
        $y += 45;

        $customerName = $orderData['customer_snapshot']['name'] ?? ($orderData['address_snapshot']['name'] ?? 'Klant');
        $this->addText($img, "Klant Naam: {$customerName}", 24, 'left', $y);
        $y += 45;

        if (!empty($orderData['reserve_time'])) {
            $reserveTime = Carbon::parse($orderData['reserve_time'])->setTimezone('Europe/Amsterdam')->format('d-m-Y H:i');
            $timeLabel   = ($orderType === 'DELIVERY') ? "Bezorg Tijd" : "Afhaal Tijd";
            $this->addText($img, "{$timeLabel}: {$reserveTime}", 26, 'left', $y);
            $y += 55;
        } else {
            $y += 10;
        }
        $this->drawSeparator($img, $y);
        $y += 40;

        // 3. 商品列表
        if (isset($orderData['products_snapshot']) && is_array($orderData['products_snapshot'])) {
            foreach ($orderData['products_snapshot'] as $product) {
                $qty = (int)$product['quantity'];
                $finalPrice = (float)$product['final_price'];
                $rowTotal = $finalPrice * $qty; 

                $this->addText($img, "{$qty}x {$product['name']}", 32, 'left', $y);
                $this->addText($img, "EUR " . number_format($rowTotal, 2, ',', '.'), 24, 'right', $y + 6);
                $y += 42;
                
                $hasDetail = false;
                if (isset($product['is_discounted']) && $product['is_discounted']) {
                    $originalTotal = (float)$product['price'] * $qty;
                    $discountSaved = $originalTotal - $rowTotal;
                    if ($discountSaved > 0) {
                        $this->addText($img, "  Origineel:", 20, 'left', $y);
                        $this->addText($img, "EUR " . number_format($originalTotal, 2, ',', '.'), 20, 'right', $y);
                        $y += 28;

                        $this->addText($img, "  - Product Korting:", 20, 'left', $y);
                        $this->addText($img, "-EUR " . number_format($discountSaved, 2, ',', '.'), 20, 'right', $y);
                        $y += 28;
                        $hasDetail = true;
                    }
                }
                
                if (!empty($product['allocated_coupon_discount']) && (float)$product['allocated_coupon_discount'] > 0) {
                    $couponShare = (float)$product['allocated_coupon_discount'];
                    $this->addText($img, "  - Coupon Verdeling:", 20, 'left', $y);
                    $this->addText($img, "-EUR " . number_format($couponShare, 2, ',', '.'), 20, 'right', $y);
                    $y += 28;
                    $hasDetail = true;
                }

                if ($hasDetail && isset($product['actual_paid_total'])) {
                    $this->addText($img, "  Netto Resultaat:", 20, 'left', $y);
                    $this->addText($img, "EUR " . number_format((float)$product['actual_paid_total'], 2, ',', '.'), 20, 'right', $y);
                    $y += 32;
                }

                if (!$hasDetail) {
                    $y += 15;
                }
            }
        }
        $this->drawSeparator($img, $y);
        $y += 40;

        // 4. 客户备注
        $this->addText($img, "Opmerking:", 24, 'left', $y);
        $y += 35;
        $this->addText($img, !empty($orderData['note']) ? $orderData['note'] : "Geen", 24, 'left', $y);
        $y += 50;
        $this->drawSeparator($img, $y);
        $y += 40;

        // 5. 配送费
        $deliveryFee = isset($orderData['delivery_fee']) ? (float)$orderData['delivery_fee'] : 0.00;
        if ($orderType === 'DELIVERY' && $deliveryFee > 0) {
            $this->addText($img, "Bezorgkosten:", 24, 'left', $y);
            $this->addText($img, "EUR " . number_format($deliveryFee, 2, ',', '.'), 24, 'right', $y);
            $y += 45;
            $this->drawSeparator($img, $y);
            $y += 40;
        }

        // 5.5 优惠券全局折扣显示
        if (!empty($orderData['coupon_discount_amount']) && (float)$orderData['coupon_discount_amount'] > 0) {
            $couponAmount = (float)$orderData['coupon_discount_amount'];
            $couponName = $orderData['coupon_snapshot']['name'] ?? 'Kortingsbon';
            $couponCode = $orderData['coupon_snapshot']['code'] ?? '';
            
            $couponTitle = "Coupon: {$couponName}" . ($couponCode ? " ({$couponCode})" : "");
            $this->addText($img, $couponTitle, 24, 'left', $y);
            $y += 35;
            
            $this->addText($img, "  - Korting:", 24, 'left', $y);
            $this->addText($img, "-EUR " . number_format($couponAmount, 2, ',', '.'), 24, 'right', $y);
            $y += 45;
            $this->drawSeparator($img, $y);
            $y += 40;
        }

        // 6. 总计
        $totalPrice = number_format((float)$orderData['total_price'], 2, ',', '.');
        $this->addText($img, "TOTAAL:", 38, 'left', $y);
        $this->addText($img, "EUR {$totalPrice}", 38, 'right', $y);
        $y += 70;
        $this->drawSeparator($img, $y);
        $y += 40;

        // 7. 荷兰 BTW 税率细目分解
        if (isset($orderData['vat_snapshot']) && is_array($orderData['vat_snapshot'])) {
            $vatRates = array_filter($orderData['vat_snapshot'], function($key) {
                return $key !== 'total_vat_amount';
            }, ARRAY_FILTER_USE_KEY);

            if (count($vatRates) > 0) {
                $this->addText($img, "BTW Overzicht:", 22, 'left', $y);
                $y += 40;
                foreach ($vatRates as $vatName => $vatInfo) {
                    if (!isset($vatInfo['product_total']) || !isset($vatInfo['vat_total'])) continue;
                    $this->addText($img, "{$vatName} (Op EUR " . number_format((float)$vatInfo['product_total'], 2, ',', '.') . "):", 22, 'left', $y);
                    $this->addText($img, "EUR " . number_format((float)$vatInfo['vat_total'], 2, ',', '.'), 22, 'right', $y);
                    $y += 35;
                }
                if (isset($orderData['total_vat_amount'])) {
                    $this->addText($img, "Totale BTW:", 22, 'left', $y);
                    $this->addText($img, "EUR " . number_format((float)$orderData['total_vat_amount'], 2, ',', '.'), 22, 'right', $y);
                    $y += 45;
                }
                $this->drawSeparator($img, $y);
                $y += 40;
            }
        }

        // 8. 送货地址
        if ($orderType === 'DELIVERY' && !empty($orderData['address_snapshot'])) {
            $address = $orderData['address_snapshot'];
            $this->addText($img, "BEZORGADRES:", 26, 'left', $y);
            $y += 40;
            $this->addText($img, "Klant: " . ($address['name'] ?? ''), 24, 'left', $y);
            $y += 35;
            $this->addText($img, "Telefoon: " . ($address['phone'] ?? ''), 24, 'left', $y);
            $y += 35;
            $this->addText($img, "Adres: " . ($address['street'] ?? '') . " " . ($address['house_number'] ?? ''), 24, 'left', $y);
            $y += 35;
            $this->addText($img, "Postcode: " . ($address['postcode'] ?? '') . " " . ($address['city'] ?? ''), 24, 'left', $y);
            $y += 50;
            $this->drawSeparator($img, $y);
            $y += 40;
        }

        // 9. 底部致谢
        $this->addText($img, 'Bedankt voor uw bestelling!', 24, 'center', $y);
        $y += 45;
        $this->addText($img, 'Zen Sushi Heemstede', 20, 'center', $y);
        $y += 35;
        $this->addText($img, 'Binnenweg 31, 2101JB Heemstede', 20, 'center', $y);
        $y += 50;
        $this->addText($img, 'Eet smakelijk!', 24, 'center', $y);
        $y += 100; 
    }
}