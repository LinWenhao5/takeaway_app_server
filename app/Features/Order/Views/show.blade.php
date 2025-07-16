@extends('layouts.app')

@section('title', __('orders.details'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">@lang('orders.orders')</a></li>
            <li class="breadcrumb-item active" aria-current="page">@lang('orders.order') #{{ $order->id }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h2>@lang('orders.order') #{{ $order->id }} @lang('orders.details')</h2>
    <div class="mb-4">
        <strong>@lang('orders.status'):</strong>
        @php
            $badgeColors = config('order_status.badge_colors');
        @endphp
        <span class="badge bg-{{ $badgeColors[$order->status->value] ?? 'secondary' }}">
            @lang('orders.' . $order->status->value)
        </span>
        <br>
        <strong>@lang('orders.total'):</strong> €{{ number_format($order->total_price, 2) }}<br>
        <strong>@lang('orders.created'):</strong> {{ $order->created_at->format('Y-m-d H:i') }}
    </div>

    <div class="mb-4">
        <h5>@lang('orders.customer_info')</h5>
        <div>
            <strong>@lang('orders.customer'):</strong> {{ $order->customer->name ?? '-' }}<br>
            <strong>@lang('orders.email'):</strong> {{ $order->customer->email ?? '-' }}
        </div>
    </div>

    <div class="mb-4">
        <h5>@lang('orders.address')</h5>
        <div>
            <strong>@lang('orders.street'):</strong> {{ $order->address_snapshot['street'] ?? '-' }}<br>
            <strong>@lang('orders.house_number'):</strong> {{ $order->address_snapshot['house_number'] ?? '-' }}<br>
            <strong>@lang('orders.postcode'):</strong> {{ $order->address_snapshot['postcode'] ?? '-' }}<br>
            <strong>@lang('orders.city'):</strong> {{ $order->address_snapshot['city'] ?? '-' }}<br>
            <strong>@lang('orders.country'):</strong> {{ $order->address_snapshot['country'] ?? '-' }}<br>
            <strong>@lang('orders.phone'):</strong> {{ $order->address_snapshot['phone'] ?? '-' }}
        </div>
    </div>

    <div class="mb-4">
        <h5>@lang('orders.dishes')</h5>
        <x-table class="table-bordered align-middle">
            <x-slot:head>
                <tr>
                    <th>@lang('orders.dish')</th>
                    <th>@lang('orders.quantity')</th>
                    <th>@lang('orders.unit_price')</th>
                    <th>@lang('orders.subtotal')</th>
                </tr>
            </x-slot:head>
            <x-slot:body>
                @php $total = 0; @endphp
                @forelse ($order->products as $product)
                    @php
                        $subtotal = ($product->pivot->price ?? 0) * ($product->pivot->quantity ?? 0);
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $product->name ?? '-' }}</td>
                        <td>{{ $product->pivot->quantity ?? '-' }}</td>
                        <td>€{{ number_format($product->pivot->price ?? 0, 2) }}</td>
                        <td>€{{ number_format($subtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">@lang('orders.no_dishes')</td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-table>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">@lang('orders.back_to_orders')</a>
@endsection

