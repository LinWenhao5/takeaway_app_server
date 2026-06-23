<?php

namespace App\Features\Printer\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class ReceiptImageGenerator
{
    private const WIDTH = 576;
    private const FONT_PATH = 'fonts/arial.ttf'; 

    public function generate(array $orderData): string
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->createImage(self::WIDTH, 3000);
        $img->fill('#ffffff'); 
        $y = 40;

        // -----------------------------------------------------------------
        // 1. 标题、大单号与点单类型 (Logo 与文字并排居中)
        // -----------------------------------------------------------------
        $logoPath = public_path('assets/logo.png');
        $titleText = 'Zen Sushi';
        $titleFontSize = 46;

        if (file_exists($logoPath)) {
            // A. 获取 Logo 的原始物理尺寸
            list($logoWidth, $logoHeight) = getimagesize($logoPath);
            
            // B. 设定目标高度（强制锁死在 55px 高度，防止巨轮大图撑爆排版）
            $targetHeight = 55;
            if ($logoHeight > $targetHeight) {
                // 等比计算缩放后的宽度
                $targetWidth = (int)(($logoWidth / $logoHeight) * $targetHeight);
                
                // 用 GD 原生创建一个缩放后的临时干净画布
                $resizedLogo = imagecreatetruecolor($targetWidth, $targetHeight);
                
                // 保持 PNG 的透明通道（如果有的话）
                imagealphablending($resizedLogo, false);
                imagesavealpha($resizedLogo, true);
                
                // 读入原始 Logo 
                $sourceLogo = imagecreatefrompng($logoPath);
                
                // 核心缩放拷贝
                imagecopyresampled(
                    $resizedLogo, $sourceLogo, 
                    0, 0, 0, 0, 
                    $targetWidth, $targetHeight, $logoWidth, $logoHeight
                );

                
                // 将缩放后的 GD 句柄直接赋值给后续渲染变量
                $logoHandle = $resizedLogo;
                $currentLogoWidth = $targetWidth;
                $currentLogoHeight = $targetHeight;
            } else {
                // 如果图片本来就很小，直接读入句柄即可
                $logoHandle = imagecreatefrompng($logoPath);
                $currentLogoWidth = $logoWidth;
                $currentLogoHeight = $logoHeight;
            }

            // C. 开始计算并排居中的绝对 X 坐标
            $gap = 15;                       // Logo 与文字的左右间距
            $textEstimatedWidth = 210;       // "Zen Sushi" 46号字近似像素宽
            
            $totalHeaderWidth = $currentLogoWidth + $gap + $textEstimatedWidth;
            $startX = (self::WIDTH - $totalHeaderWidth) / 2;

            // D. 核心渲染：将缩放后的 GD 句柄完美塞入画布
            $img->insert($logoHandle, (int)$startX, (int)$y);

            $textX = $startX + $currentLogoWidth + $gap;
            $textCorrectedY = $y + (($currentLogoHeight - $titleFontSize) / 2) + ($titleFontSize * 0.8);

            $img->text($titleText, (int)$textX, (int)$textCorrectedY, function($font) use ($titleFontSize) {
                $font->file(public_path(self::FONT_PATH));
                $font->size($titleFontSize);
                $font->color('#000000');
                $font->align('left');
            });


            // 动态向下推进行高
            $y += $currentLogoHeight + 50;
        } else {
            // 如果 Logo 不存在，自动退回到原本的文字纯居中排列，系统不崩溃
            $this->addText($img, $titleText, $titleFontSize, 'center', $y);
            $y += 75;
        }

        // 大单号与点单类型紧随其后
        $orderId   = $orderData['id'];
        $orderType = strtoupper($orderData['order_type'] ?? 'PICKUP'); 
        $typeLabelDutch = ($orderType === 'DELIVERY') ? 'BEZORG' : 'AFHAAL';

        $this->addText($img, "#{$orderId} - {$typeLabelDutch}", 42, 'center', $y);
        $y += 70;

        $this->drawSeparator($img, $y);
        $y += 40;

        // -----------------------------------------------------------------
        // 2. 订单基础信息 (下单与期望时间)
        // -----------------------------------------------------------------
        $createdAt = Carbon::parse($orderData['created_at'])->setTimezone('Europe/Amsterdam')->format('d-m-Y H:i');

        $this->addText($img, "Besteldatum: {$createdAt}", 24, 'left', $y);
        $y += 45;

        if (!empty($orderData['reserve_time'])) {
            $reserveTime = Carbon::parse($orderData['reserve_time'])->format('d-m-Y H:i');
            $timeLabel   = ($orderType === 'DELIVERY') ? "BEZORG TIJD" : "AFHAAL TIJD";
            
            $this->addText($img, "{$timeLabel}: {$reserveTime}", 26, 'left', $y);
            $y += 55;
        } else {
            $y += 10;
        }

        $this->drawSeparator($img, $y);
        $y += 40;

        // -----------------------------------------------------------------
        // 3. 商品列表（商品名加大至 32 号字 + 动态折扣细节明细）
        // -----------------------------------------------------------------
        if (isset($orderData['products_snapshot']) && is_array($orderData['products_snapshot'])) {
            foreach ($orderData['products_snapshot'] as $product) {
                $leftText = "{$product['quantity']}x {$product['name']}";
                $priceStr = "EUR " . number_format((float)$product['final_price'], 2, ',', '.');
                
                $this->addText($img, $leftText, 32, 'left', $y);
                
                $fontOffset = 6;
                $this->addText($img, $priceStr, 24, 'right', $y + $fontOffset);
                
                if (isset($product['is_discounted']) && $product['is_discounted'] && !empty($product['discount_price'])) {
                    $y += 42;
                    
                    $originalPrice = (float)$product['price'];
                    $discountPrice = (float)$product['discount_price'];
                    $savedAmount   = $originalPrice - $discountPrice;
                    
                    $orgPriceFormatted = number_format($originalPrice, 2, ',', '.');
                    $savedFormatted    = number_format($savedAmount, 2, ',', '.');
                    
                    // 拼接完美的荷兰语折扣提示
                    $discountNotice = "  [Orig: EUR {$orgPriceFormatted} | Korting: -EUR {$savedFormatted}]";
                    
                    // 用精致的 20 号小字印在正下方
                    $this->addText($img, $discountNotice, 20, 'left', $y);
                    
                    $y += 28; // 为折扣行留出高度间距
                } else {
                    $y += 55; // 无折扣商品，保持舒适的正常行高
                }
            }
        }

        $this->drawSeparator($img, $y);
        $y += 40;

        // -----------------------------------------------------------------
        // 4. 客户备注 (Opmerking)
        // -----------------------------------------------------------------
        $this->addText($img, "Opmerking:", 24, 'left', $y);
        $y += 35;

        if (!empty($orderData['note'])) {
            $this->addText($img, $orderData['note'], 24, 'left', $y);
        } else {
            $this->addText($img, "Geen", 24, 'left', $y);
        }
        
        $y += 50;
        $this->drawSeparator($img, $y);
        $y += 40;

        // -----------------------------------------------------------------
        // 5. 配送费 (Bezorgkosten)
        // -----------------------------------------------------------------
        $deliveryFee = isset($orderData['delivery_fee']) ? (float)$orderData['delivery_fee'] : 0.00;
        if ($orderType === 'DELIVERY' && $deliveryFee > 0) {
            $feeStr = "EUR " . number_format($deliveryFee, 2, ',', '.');
            $this->addText($img, "Bezorgkosten:", 24, 'left', $y);
            $this->addText($img, $feeStr, 24, 'right', $y);
            $y += 45;
            $this->drawSeparator($img, $y);
            $y += 40;
        }

        // -----------------------------------------------------------------
        // 6. 总计 (TOTAAL)
        // -----------------------------------------------------------------
        $totalPrice = number_format((float)$orderData['total_price'], 2, ',', '.');
        $this->addText($img, "TOTAAL:", 38, 'left', $y);
        $this->addText($img, "EUR {$totalPrice}", 38, 'right', $y);
        $y += 70;
        $this->drawSeparator($img, $y);
        $y += 40;

        // -----------------------------------------------------------------
        // 7. 荷兰 BTW 税率细目分解
        // -----------------------------------------------------------------
        if (isset($orderData['vat_snapshot']) && is_array($orderData['vat_snapshot'])) {
            $vatRates = array_filter($orderData['vat_snapshot'], function($key) {
                return $key !== 'total_vat_amount';
            }, ARRAY_FILTER_USE_KEY);

            if (count($vatRates) > 0) {
                $this->addText($img, "BTW Overzicht:", 22, 'left', $y);
                $y += 40;

                foreach ($vatRates as $vatName => $vatInfo) {
                    if (!isset($vatInfo['product_total']) || !isset($vatInfo['vat_total'])) {
                        continue;
                    }
                    
                    $vatLabel = "{$vatName} (Op EUR " . number_format((float)$vatInfo['product_total'], 2, ',', '.') . "):";
                    $vatValue = "EUR " . number_format((float)$vatInfo['vat_total'], 2, ',', '.');
                    
                    $this->addText($img, $vatLabel, 22, 'left', $y);
                    $this->addText($img, $vatValue, 22, 'right', $y);
                    $y += 35;
                }
                
                if (isset($orderData['total_vat_amount'])) {
                    $totalVatStr = "EUR " . number_format((float)$orderData['total_vat_amount'], 2, ',', '.');
                    $this->addText($img, "Totale BTW:", 22, 'left', $y);
                    $this->addText($img, $totalVatStr, 22, 'right', $y);
                    $y += 45;
                }
                $this->drawSeparator($img, $y);
                $y += 40;
            }
        }

        // -----------------------------------------------------------------
        // 8. 送货地址 (BEZORGADRES)
        // -----------------------------------------------------------------
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

        // -----------------------------------------------------------------
        // 9. 底部致谢、店铺地址与留白机制
        // -----------------------------------------------------------------
        $this->addText($img, 'Bedankt voor uw bestelling!', 24, 'center', $y);
        $y += 45;

        $this->addText($img, 'Zen Sushi Heemstede', 20, 'center', $y);
        $y += 35;
        $this->addText($img, 'Binnenweg 31, 2101JB Heemstede', 20, 'center', $y);
        $y += 50;

        $this->addText($img, 'Eet smakelijk!', 24, 'center', $y);
        $y += 100; 

        // -----------------------------------------------------------------
        // 10. 极致裁剪与单色 8-bit Colormap 转码流
        // -----------------------------------------------------------------
        $img->modify(new \Intervention\Image\Modifiers\TrimModifier(0));

        $gdImage = $img->core()->native();
        $width = imagesx($gdImage);
        $height = imagesy($gdImage);

        $monoCanvas = imagecreate($width, $height);
        
        imagecolorallocate($monoCanvas, 255, 255, 255);
        imagecolorallocate($monoCanvas, 0, 0, 0);

        imagecopyresampled(
            $monoCanvas, $gdImage, 
            0, 0, 0, 0, 
            $width, $height, $width, $height
        );
        
        ob_start();
        imagepng($monoCanvas, null, 9, PNG_NO_FILTER); 
        $binary = ob_get_clean();

        return $binary;
    }

    private function addText($img, $text, $size, $align, $y)
    {
        $x = ($align === 'left') ? 20 : (($align === 'center') ? self::WIDTH / 2 : self::WIDTH - 20);
        
        $correctedY = $y + ($size * 0.8);

        $img->text($text, $x, $correctedY, function($font) use ($size, $align) {
            $font->file(public_path(self::FONT_PATH));
            $font->size($size);
            $font->color('#000000');
            $font->align($align); 
        });
    }

    private function drawSeparator($img, $y)
    {
        $img->drawLine(function($line) use ($y) {
            $line->from(20, $y);
            $line->to(self::WIDTH - 20, $y);
            $line->color('#000000');
            $line->width(2);
        });
    }
}