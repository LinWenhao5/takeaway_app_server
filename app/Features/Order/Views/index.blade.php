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
    <audio id="order-audio" src="/sounds/new-order.wav" preload="auto"></audio>
    <audio id="confirm-audio" src="/sounds/confirm.wav" preload="auto"></audio>

    <div class="d-flex justify-content-end mb-2">
        <button id="ws-status"
            type="button"
            class="btn border d-flex align-items-center gap-2 px-3 py-2"
            style="font-size:1rem; pointer-events: none;">
            <span id="ws-status-dot" class="rounded-circle bg-secondary" style="width:10px;height:10px;display:inline-block;"></span>
            <span id="ws-status-text" class="small text-secondary"> @lang('orders.ws_disconnected')</span>
        </button>
    </div>

    <h2 class="mb-4 d-flex align-items-center">
        @lang('orders.todays_orders')
        <a href="{{ route('admin.orders.history') }}" class="btn btn-primary btn-sm ms-3">
            <i class="bi bi-clock-history me-1"></i>
            @lang('orders.view_order_history')
        </a>
        <button id="audio-btn" class="btn btn-warning ms-3" style="display:none;" onclick="unlockAudioAndHideTip()">
            🔊 @lang('orders.enable_new_order_audio')
        </button>
    </h2>

    <form method="GET" id="statusFilterForm" class="mb-4">
        <input type="hidden" name="statuses_submitted" value="1">
        <div class="btn-group flex-wrap" role="group" aria-label="Status filter">
            @foreach($statuses as $status)
                <input type="checkbox"
                       class="btn-check"
                       name="statuses[]"
                       id="status-{{ $status }}"
                       value="{{ $status }}"
                       autocomplete="off"
                       {{ in_array($status, $selectedStatuses ?? []) ? 'checked' : '' }}
                       onchange="document.getElementById('statusFilterForm').submit();">
                <label class="btn btn-outline-{{ $statusMeta[$status]['color'] }} d-flex align-items-center mb-2 me-2 {{ in_array($status, $selectedStatuses ?? []) ? 'active fw-bold shadow-sm border-2' : '' }}"
                       for="status-{{ $status }}"
                       style="min-width: 140px;">
                    <span class="rounded-circle d-inline-block me-2"
                          style="width:10px;height:10px;background-color: var(--bs-{{ $statusMeta[$status]['color'] }});"></span>
                    <i class="bi {{ $statusMeta[$status]['icon'] }} me-1"></i>
                    <span>{{ $statusMeta[$status]['label'] }}</span>
                    <span class="small ms-2">({{ $allTodayOrders->where('status', $status)->count() }})</span>
                </label>
            @endforeach
        </div>
    </form>

    <div id="order-list">
        @php
            $colClass = 'col-12';
        @endphp
        <div class="row g-4" style="min-height:80vh;">
            @foreach($statuses as $status)
                @if(!empty($selectedStatuses) && in_array($status, $selectedStatuses))
                    <div class="{{ $colClass }}">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-{{ $statusMeta[$status]['color'] }} me-2" style="font-size:1rem;">
                                <i class="bi {{ $statusMeta[$status]['icon'] }} me-1"></i>
                                {{ $statusMeta[$status]['label'] }}
                            </span>
                            <span class="small">
                                ({{ $allTodayOrders->where('status', $status)->count() }} @lang('orders.order_count'))
                            </span>
                        </div>
                        <div class="bg-body-tertiary p-3 rounded">
                            <div class="row g-3" style="max-height: 80vh; overflow-y: auto;">
                                @forelse($allTodayOrders->where('status', $status) as $order)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card mb-0 h-100">
                                            @include('order::partials.order_card', [
                                                'order' => $order,
                                                'showProducts' => true,
                                                'allStatuses' => $statuses
                                            ])
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted align-self-center">@lang('orders.no_orders')</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('order::partials.order_notify')
@endpush