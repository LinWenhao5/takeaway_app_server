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
    @php
        $badgeStyles = config('order_status.status_badge_styles');
        $status = $order->status->value;
        $bgClass = 'bg-' . ($badgeStyles[$status]['bg'] ?? 'secondary');
        $textClass = 'text-' . ($badgeStyles[$status]['text'] ?? 'white');
        $type = $order->order_type->value;
        $typeBadge = config('order_status.type_badge_styles')[$type] ?? ['bg' => 'secondary', 'text' => 'white'];
    @endphp
    <div class="mb-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h3 class="mb-2">
                        @lang('orders.order') #{{ $order->id }}
                    </h3>
                    <div class="mb-2">
                        <span class="badge bg-{{ $typeBadge['bg'] }} text-{{ $typeBadge['text'] }} me-2">
                            @lang('orders.' . strtolower($type))
                        </span>
                        <span class="badge {{ $bgClass }} {{ $textClass }}">
                            @lang('orders.' . $status)
                        </span>
                        <span class="ms-2 text-muted">
                            <i class="bi bi-clock me-1"></i>
                            @lang('orders.reserve_time'):
                            {{ \Carbon\Carbon::parse($order->reserve_time)->format('Y-m-d H:i') }}
                        </span>
                    </div>
                </div>
                <div class="text-md-end mt-3 mt-md-0">
                    <div>
                        <strong>@lang('orders.total'):</strong>
                        <span class="fs-5 text-success">€{{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div>
                        <strong>@lang('orders.created'):</strong>
                        {{ $order->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('orders.customer_info')</h5>
                    <div>
                        <strong>@lang('orders.customer'):</strong> {{ $order->customer->name ?? '-' }}<br>
                        <strong>@lang('orders.email'):</strong> {{ $order->customer->email ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('orders.address')</h5>
                    <div>
                        <strong>@lang('orders.street'):</strong> {{ $order->address_snapshot['street'] ?? '-' }}<br>
                        <strong>@lang('orders.house_number'):</strong> {{ $order->address_snapshot['house_number'] ?? '-' }}<br>
                        <strong>@lang('orders.postcode'):</strong> {{ $order->address_snapshot['postcode'] ?? '-' }}<br>
                        <strong>@lang('orders.city'):</strong> {{ $order->address_snapshot['city'] ?? '-' }}<br>
                        <strong>@lang('orders.country'):</strong> {{ $order->address_snapshot['country'] ?? '-' }}<br>
                        <strong>@lang('orders.phone'):</strong> {{ $order->address_snapshot['phone'] ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">@lang('orders.dishes')</h5>
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
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
        @lang('orders.back_to_orders')
    </a>
@endsection

@section('right-sidebar')
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h5 class="card-title mb-3">@lang('orders.status')</h5>
            @php
                $type = $order->order_type->value;
                $typeStates = [
                    'pickup' => ['paid', 'completed'],
                    'delivery' => ['paid', 'delivering', 'completed'],
                ];
                $allowedStates = $typeStates[$type] ?? [];
                $stateIcons = [
                    'paid' => 'bi-cash-coin',
                    'delivering' => 'bi-truck',
                    'completed' => 'bi-check-circle',
                ];
            @endphp
            <div class="mb-3">
                <span class="badge {{ $bgClass }} {{ $textClass }}">
                    @lang('orders.' . $status)
                </span>
            </div>
            <div class="mb-2">@lang('orders.change_status'):</div>
            <div class="d-grid gap-2">
                @foreach($allowedStates as $s)
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                        @csrf
                        <input type="hidden" name="status" value="{{ $s }}">
                        <button type="submit"
                                class="btn btn-sm mb-1
                                    {{ $status === $s ? 'btn-primary disabled' : 'btn-outline-primary' }}"
                                @if($status === $s) disabled @endif>
                            <i class="bi {{ $stateIcons[$s] ?? 'bi-arrow-repeat' }} me-1"></i>
                            @lang('orders.' . $s)
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection

