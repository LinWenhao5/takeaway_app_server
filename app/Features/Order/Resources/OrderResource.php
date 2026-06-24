<?php

namespace App\Features\Order\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'public_id' => $this->public_id,
            'daily_sequence' => $this->daily_sequence,

            'products_snapshot' => $this->products_snapshot,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'delivery_fee' => $this->delivery_fee,
            'address_id' => $this->address_id,
            'address_snapshot' => $this->address_snapshot,
            'vat_snapshot' => $this->vat_snapshot,
            'order_type' => $this->order_type,
            'reserve_time' => optional($this->reserve_time)->format('Y-m-d H:i'),
            'total_vat_amount' => $this->total_vat_amount,
            'note' => $this->note,
            'printed' => $this->printed,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}