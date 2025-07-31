@php
    $badgeStyles = config('order_status.status_badge_styles');
    $status = $order->status->value;
    $type = $order->order_type->value;
    $typeBadge = config('order_status.type_badge_styles')[$type] ?? ['bg' => 'secondary', 'text' => 'white'];
    $bgClass = 'bg-' . ($badgeStyles[$status]['bg'] ?? 'secondary');
    $textClass = 'text-' . ($badgeStyles[$status]['text'] ?? 'white');
@endphp
<div class="card-header d-flex justify-content-between align-items-center">
    <span class="d-inline-flex align-items-center">
        <span class="badge me-2 bg-{{ $typeBadge['bg'] }} text-{{ $typeBadge['text'] }}">
            @lang('orders.' . strtolower($type))
        </span>
        <span>
            @lang('orders.order') #{{ $order->id }}
        </span>
    </span>
    <span class="badge {{ $bgClass }} {{ $textClass }} ms-2">
        @lang('orders.' . $status)
    </span>
    <span class="text-muted ms-3" style="font-size:0.95em;">
        <i class="bi bi-clock me-1"></i>
        {{ \Carbon\Carbon::parse($order->reserve_time)->format('H:i') }}
    </span>
    @include('order::partials.order_card_dropdown', ['order' => $order, 'allStatuses' => $allStatuses ?? []])
</div>