@php
    $badgeColors = config('order_status.badge_colors');
    $isDelivery = $order->order_type === \App\Features\Order\Enums\OrderType::DELIVERY;
@endphp
<div class="card-header d-flex justify-content-between align-items-center">
    <span class="d-inline-flex align-items-center">
        <span class="badge me-2 {{ $isDelivery ? 'bg-primary' : 'bg-warning text-dark' }}">
            @lang('orders.' . strtolower($order->order_type->value))
        </span>
        <span>
            @lang('orders.order') #{{ $order->id }}
        </span>
    </span>
    <span class="badge bg-{{ $badgeColors[$order->status->value] ?? 'secondary' }} ms-2">
        @lang('orders.' . $order->status->value)
    </span>
    <span class="text-muted ms-3" style="font-size:0.95em;">
        <i class="bi bi-clock me-1"></i>
        {{ $order->created_at->format('H:i') }}
    </span>
    @include('order::partials.order_card_dropdown', ['order' => $order, 'allStatuses' => $allStatuses ?? []])
</div>