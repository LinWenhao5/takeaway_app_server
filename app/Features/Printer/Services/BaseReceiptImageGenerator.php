<?php

namespace App\Features\Printer\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;

abstract class BaseReceiptImageGenerator
{
    protected const WIDTH = 576;
    protected const FONT_PATH = 'fonts/arial.ttf'; 

    /**
     * 定义子类特定的渲染业务逻辑
     */
    abstract protected function render(Image $img, array $orderData, int &$y): void;

    /**
     * 统一的生成入口（主流水线）
     */
    public function generate(array $orderData): string
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->createImage(self::WIDTH, 4000);
        $img->fill('#ffffff'); 
        $y = 40;

        // 执行子类的具体画板排版
        $this->render($img, $orderData, $y);

        // -----------------------------------------------------------------
        // 统一的高清智能裁剪与单色 8-bit Colormap 转码流逻辑
        // -----------------------------------------------------------------
        $img->trim(); 

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

    /**
     * 提取出的公共方法：安全渲染文本并计算偏移
     */
    protected function addText(Image $img, string $text, int $size, string $align, int $y): void
    {
        $x = ($align === 'left') ? 20 : (($align === 'center') ? self::WIDTH / 2 : self::WIDTH - 20);
        $correctedY = $y + ($size * 0.8);

        $img->text($text, (int)$x, (int)$correctedY, function($font) use ($size, $align) {
            $font->file(public_path(self::FONT_PATH));
            $font->size($size);
            $font->color('#000000');
            $font->align($align); 
        });
    }

    /**
     * 提取出的公共方法：绘制水平分割线
     */
    protected function drawSeparator(Image $img, int $y): void
    {
        $img->drawLine(function($line) use ($y) {
            $line->from(20, $y);
            $line->to(self::WIDTH - 20, $y);
            $line->color('#000000');
            $line->width(2);
        });
    }
}