@php
    $customerName = $order->customer->name 
                    ?? ($order->address_snapshot['name'] ?? null) 
                    ?? ($order->customer_snapshot['name'] ?? '-');
@endphp

<div class="mb-2">
    <strong>@lang('orders.customer'):</strong> {{ $customerName }}
    
    @if($order->order_type !== \App\Features\Order\Enums\OrderType::PICKUP)
        <span class="ms-2 text-muted">
            <i class="bi bi-telephone me-1"></i>
            {{ $order->address_snapshot['phone'] ?? '-' }}
        </span>
    @endif
</div>