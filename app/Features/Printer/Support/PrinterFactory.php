<?php

namespace App\Features\Printer\Support;

use App\Features\Printer\Services\BaseReceiptImageGenerator;
use App\Features\Printer\Services\ReceiptImageGenerator;
use App\Features\Printer\Services\KitchenImageGenerator;

class PrinterFactory
{
    protected static array $mapping = [
        'receipt' => ReceiptImageGenerator::class,
        'kitchen' => KitchenImageGenerator::class,
    ];

    public static function make(string $type): BaseReceiptImageGenerator
    {
        $className = self::$mapping[$type] ?? ReceiptImageGenerator::class;

        return new $className();
    }
}