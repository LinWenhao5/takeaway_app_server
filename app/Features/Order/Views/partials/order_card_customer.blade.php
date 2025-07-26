{{-- filepath: app/Features/Order/Views/partials/order_card_customer.blade.php --}}
<div class="mb-2">
    <strong>@lang('orders.customer'):</strong>
    {{ $order->customer->name ?? '-' }}
    @if($order->order_type !== \App\Features\Order\Enums\OrderType::PICKUP)
        <span class="ms-2 text-muted">
            <i class="bi bi-telephone me-1"></i>
            {{ $order->address_snapshot['phone'] ?? '-' }}
        </span>
    @endif
</div>