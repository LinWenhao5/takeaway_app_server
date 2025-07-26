@if($order->order_type !== \App\Features\Order\Enums\OrderType::PICKUP && $order->address_snapshot)
    <div class="mb-2">
        <strong>@lang('orders.address'):</strong>
        {{ $order->address_snapshot['street'] ?? '-' }}
        {{ $order->address_snapshot['house_number'] ?? '' }},
        {{ $order->address_snapshot['postcode'] ?? '' }}
    </div>
@endif