<div class="card shadow-sm h-100">
    @include('order::partials.order_card_header', ['order' => $order, 'allStatuses' => $allStatuses ?? null])
    <div class="card-body">
        @include('order::partials.order_card_customer', ['order' => $order])
        @include('order::partials.order_card_address', ['order' => $order])
        @include('order::partials.order_card_total', ['order' => $order])
        @include('order::partials.order_card_products', ['order' => $order, 'showProducts' => $showProducts ?? false])
    </div>
</div>