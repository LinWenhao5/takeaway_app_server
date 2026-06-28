<?php

namespace App\Features\Coupon\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserCouponResource extends JsonResource
{
    public function toArray($request)
    {
        $pivotId = $this->pivot ? $this->pivot->id : null;
        $isUsed = $this->pivot ? (bool)$this->pivot->is_used : false;
        $expiresAtString = $this->pivot ? $this->pivot->expires_at : null;
        $receivedAtString = $this->pivot ? $this->pivot->received_at : null;

        $status = 'available';
        if ($isUsed) {
            $status = 'used';
        } elseif ($expiresAtString && Carbon::parse($expiresAtString)->isPast()) {
            $status = 'expired';
        }

        return [
            'id' => $pivotId,
            'coupon_id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            
            'type' => $this->type,
            'value' => $this->type === 'percent' ? $this->value * 100 : (float)$this->value,
            'min_order_amount' => (float)$this->min_order_amount,
            'is_no_threshold' => $this->min_order_amount <= 0,
            
            'rule_description' => $this->min_order_amount > 0 
                ? ($this->type === 'fixed' ? "Min. spend €" . number_format($this->min_order_amount, 2) : "Orders over €" . number_format($this->min_order_amount, 2))
                : "No minimum spend",

            'is_used' => $isUsed,
            'user_coupon_status' => $status,
            'received_at' => $receivedAtString,
            'expires_at' => $expiresAtString,
        ];
    }
}