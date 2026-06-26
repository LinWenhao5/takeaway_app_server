<?php
namespace App\Features\Coupon\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'min_order_amount' => $this->min_order_amount,
            'pickup_start_at' => $this->pickup_start_at,
            'pickup_end_at' => $this->pickup_end_at,
            'valid_days' => $this->valid_days,
            'use_start_at' => $this->use_start_at,
            'use_end_at' => $this->use_end_at,
            'total_quantity' => $this->total_quantity,
            'per_customer_limit' => $this->per_customer_limit,
            'is_active' => $this->is_active,
        ];
    }
}