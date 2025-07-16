@extends('layouts.app')

@section('title', __('orders.order_management'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">@lang('orders.orders')</a></li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container">
    <form method="GET" class="mb-4 d-flex gap-4 align-items-center" id="filterForm">
        <div class="form-check form-switch">
            <input type="hidden" name="hide_unpaid" value="0">
            <input class="form-check-input" type="checkbox" name="hide_unpaid" id="hide_unpaid"
                   value="1" {{ $hideUnpaid ? 'checked' : '' }} onchange="this.form.submit()">
            <label class="form-check-label" for="hide_unpaid">
                @lang('orders.hide_unpaid_orders')
            </label>
        </div>
    </form>

    <h2 class="mb-4 d-flex align-items-center">
        @lang('orders.todays_orders')
        <a href="{{ route('admin.orders.history') }}" class="btn btn-primary btn-sm ms-3">
            <i class="bi bi-clock-history me-1"></i>
            @lang('orders.view_order_history')
        </a>
    </h2>

    <div class="d-flex flex-column flex-md-row gap-4">
        @foreach($statuses as $status)
            <div class="flex-grow-1 d-flex flex-column w-100" style="max-width:400px; min-width:0; height:80vh;">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-{{ $statusMeta[$status]['color'] }} me-2" style="font-size:1rem;">
                        <i class="bi {{ $statusMeta[$status]['icon'] }} me-1"></i>
                        {{ $statusMeta[$status]['label'] }}
                    </span>
                    <span class="text-muted small">
                        ({{ $todayOrders->where('status', $status)->count() }} @lang('orders.order_count'))
                    </span>
                </div>
                <div class="d-flex flex-column gap-3 pb-2 flex-grow-1" style="min-height:0; overflow-y:auto;">
                    @forelse($todayOrders->where('status', $status) as $order)
                        <div>
                            @include('order::partials.order_card', [
                                'order' => $order,
                                'showProducts' => true,
                                'allStatuses' => $statuses
                            ])
                        </div>
                    @empty
                        <div class="text-muted align-self-center">@lang('orders.no_orders')</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
.order-row {
    padding-bottom: 8px;
}
</style>
@endpush