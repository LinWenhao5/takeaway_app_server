<div class="card shadow-sm h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-receipt-cutoff me-2"></i>
            @lang('orders.order') #{{ $order->id }}
            @php
                $badgeColors = config('order_status.badge_colors');
            @endphp
            <span class="badge bg-{{ $badgeColors[$order->status->value] ?? 'secondary' }} ms-2">
                @lang('orders.' . $order->status->value)
            </span>
            <span class="text-muted ms-3" style="font-size:0.95em;">
                <i class="bi bi-clock me-1"></i>
                {{ $order->created_at->format('H:i') }}
            </span>
        </span>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary border-0" type="button" id="dropdownMenuButton{{ $order->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $order->id }}">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}">
                        <i class="bi bi-eye me-1"></i> @lang('orders.details')
                    </a>
                </li>
                @if($order->status->value !== 'unpaid')
                    @foreach(($allStatuses ?? []) as $s)
                        @if($s !== $order->status->value && $s !== 'unpaid')
                            <li>
                                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $s }}">
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        @lang('orders.mark_as') @lang('orders.' . $s)
                                    </button>
                                </form>
                            </li>
                        @endif
                    @endforeach
                @endif
                @if($order->status->value === 'unpaid')
                    <li>
                        <x-delete-confirm
                            :action="route('admin.orders.destroy', $order)"
                            confirm="@lang('orders.delete_confirm')"
                        >
                            <a class="dropdown-item text-danger" href="#">
                                <i class="bi bi-trash me-1"></i> @lang('orders.delete')
                            </a>
                        </x-delete-confirm>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-2">
            <strong>@lang('orders.customer'):</strong>
            {{ $order->customer->name ?? '-' }}
            <span class="ms-2 text-muted">
                <i class="bi bi-telephone me-1"></i>
                {{ $order->address_snapshot['phone'] ?? '-' }}
            </span>
        </div>
        <div class="mb-2">
            <strong>@lang('orders.address'):</strong>
            {{ $order->address_snapshot['street'] ?? '-' }}
            {{ $order->address_snapshot['house_number'] ?? '' }},
            {{ $order->address_snapshot['postcode'] ?? '' }}
            {{ $order->address_snapshot['city'] ?? '' }},
            {{ $order->address_snapshot['country'] ?? '' }}
        </div>
        <div class="mb-2"><strong>@lang('orders.total'):</strong> €{{ number_format($order->total_price, 2) }}</div>

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
    </div>
</div>