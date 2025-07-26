@extends('layouts.app')

@section('title', __('orders.order_history'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">@lang('orders.orders')</a></li>
            <li class="breadcrumb-item active" aria-current="page">@lang('orders.order_history')</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4">@lang('orders.order_history')</h2>
    @php
        $badgeStyles = config('order_status.badge_styles');
    @endphp
    <x-table>
        <x-slot:head>
            <tr>
                <th>#</th>
                <th>@lang('orders.customer')</th>
                <th>@lang('orders.phone')</th>
                <th>@lang('orders.address')</th>
                <th>@lang('orders.type')</th>
                <th>@lang('orders.status')</th>
                <th>@lang('orders.total')</th>
                <th>@lang('orders.created')</th>
                <th>@lang('orders.action')</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($historyOrders as $order)
                @php
                    $status = $order->status->value;
                    $type = $order->order_type->value ?? '';
                    $statusBadge = config('order_status.status_badge_styles')[$status] ?? ['bg' => 'secondary', 'text' => 'white'];
                    $typeBadge = config('order_status.type_badge_styles')[$type] ?? ['bg' => 'secondary', 'text' => 'white'];
                @endphp
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ $order->address_snapshot['phone'] ?? '-' }}</td>
                    <td>
                        {{ $order->address_snapshot['street'] ?? '-' }}
                        {{ $order->address_snapshot['house_number'] ?? '' }},
                        {{ $order->address_snapshot['postcode'] ?? '' }}
                        {{ $order->address_snapshot['city'] ?? '' }},
                        {{ $order->address_snapshot['country'] ?? '' }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $typeBadge['bg'] }} text-{{ $typeBadge['text'] }}">
                            @lang('orders.' . strtolower($type))
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $statusBadge['bg'] }} text-{{ $statusBadge['text'] }}">
                            @lang('orders.' . $status)
                        </span>
                    </td>
                    <td>€{{ number_format($order->total_price, 2) }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                            @lang('orders.details')
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">@lang('orders.no_order_history')</td>
                </tr>
            @endforelse
        </x-slot:body>
        <x-slot:pagination>
            {{ $historyOrders->links() }}
        </x-slot:pagination>
    </x-table>
</div>
@endsection