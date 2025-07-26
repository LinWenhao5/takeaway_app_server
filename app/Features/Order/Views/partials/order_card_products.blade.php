@if(isset($showProducts) && $showProducts)
    <button class="btn btn-link btn-sm px-0" type="button"
        data-bs-toggle="collapse"
        data-bs-target="#collapseDishes{{ $order->id }}"
        aria-expanded="false"
        aria-controls="collapseDishes{{ $order->id }}">
        <i class="bi bi-list-ul me-1"></i> @lang('orders.view_dishes')
    </button>
    <div class="collapse mt-2" id="collapseDishes{{ $order->id }}">
        <strong>@lang('orders.dishes'):</strong>
        <x-table class="table-sm table-bordered mb-0">
            <x-slot:head>
                <tr>
                    <th>@lang('orders.dish')</th>
                    <th>@lang('orders.unit_price')</th>
                    <th>@lang('orders.quantity')</th>
                    <th>@lang('orders.subtotal')</th>
                </tr>
            </x-slot:head>
            <x-slot:body>
                @php $subtotalSum = 0; @endphp
                @foreach($order->products as $product)
                    @php
                        $subtotal = $product->pivot->price * $product->pivot->quantity;
                        $subtotalSum += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>€{{ number_format($product->pivot->price, 2) }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>€{{ number_format($subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-table>
    </div>
@endif