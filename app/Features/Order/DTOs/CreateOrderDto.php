<?php

namespace App\Features\Order\DTOs;

use App\Features\Order\Enums\OrderType;

class CreateOrderDto
{
    public function __construct(
        public readonly int $customerId,
        public readonly ?int $addressId,
        public readonly OrderType $orderType,
        public readonly string $reserveTime,
        public readonly ?string $note = null,
    ) {
    }
}