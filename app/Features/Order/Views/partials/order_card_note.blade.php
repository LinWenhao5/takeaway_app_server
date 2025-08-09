@if(!empty($order->note))
    <div class="mb-2">
        <strong>@lang('orders.note'):</strong>
        {{ $order->note }}
    </div>
@endif