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

    @php
        $currentDate = \Carbon\Carbon::parse(request('date', now()->toDateString()));
        $prevDate = $currentDate->copy()->subDay()->toDateString();
        $nextDate = $currentDate->copy()->addDay()->toDateString();
    @endphp

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-center align-items-center flex-wrap gap-2">
            <a href="{{ route('admin.orders.index', array_merge(request()->except('date'), ['date' => $prevDate])) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-bold fs-5 px-2" id="currentDateText">
                {{ $currentDate->translatedFormat('F d Y') }}
            </span>
            <a href="{{ route('admin.orders.index', array_merge(request()->except('date'), ['date' => $nextDate])) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-chevron-right"></i>
            </a>
            {{-- 日历选择按钮 --}}
            <form method="GET" class="d-inline-block" id="calendarForm">
                @foreach(request()->except('date') as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="date" name="date" class="form-control d-inline-block" style="width:160px"
                       value="{{ $currentDate->toDateString() }}"
                       onchange="document.getElementById('calendarForm').submit();">
            </form>
            {{-- 返回今天按钮 --}}
            @if($currentDate->toDateString() !== now()->toDateString())
                <a href="{{ route('admin.orders.index', array_merge(request()->except('date'), ['date' => now()->toDateString()])) }}"
                   class="btn btn-outline-primary">
                    <i class="bi bi-calendar-day"></i> @lang('orders.today')
                </a>
            @endif
        </div>
    </div>

    <div class="row mb-2">
        <div class="col d-flex justify-content-start">
            <a href="{{ route('admin.orders.history') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-clock-history me-1"></i>
                @lang('orders.view_order_history')
            </a>
        </div>
        <div class="col d-flex justify-content-end">
            <button id="audio-btn" class="btn btn-warning" style="display:none;" onclick="unlockAudioAndHideTip()">
                🔊 @lang('orders.enable_new_order_audio')
            </button>
            <button id="ws-status"
                type="button"
                class="btn border d-flex align-items-center gap-2 px-3 py-2 ms-2"
                style="font-size:1rem; pointer-events: none;">
                <span id="ws-status-dot" class="rounded-circle bg-secondary" style="width:10px;height:10px;display:inline-block;"></span>
                <span id="ws-status-text" class="small text-secondary"> @lang('orders.ws_disconnected')</span>
            </button>
        </div>
    </div>

    <form method="GET" id="statusFilterForm" class="mb-4">
        <input type="hidden" name="statuses_submitted" value="1">
        <input type="hidden" name="date" value="{{ $currentDate->toDateString() }}">
        <div class="btn-group flex-wrap" role="group" aria-label="Status filter">
            @foreach($statuses as $status)
                @php
                    $statusValue = is_array($status) ? ($status['value'] ?? '') : (is_object($status) ? $status->value : $status);
                @endphp
                <input type="checkbox"
                       class="btn-check"
                       name="statuses[]"
                       id="status-{{ $statusValue }}"
                       value="{{ $statusValue }}"
                       autocomplete="off"
                       {{ in_array($statusValue, $selectedStatuses ?? []) ? 'checked' : '' }}
                       onchange="document.getElementById('statusFilterForm').submit();">
                <label class="btn btn-outline-{{ $statusMeta[$statusValue]['color'] }} d-flex align-items-center mb-2 me-2 {{ in_array($statusValue, $selectedStatuses ?? []) ? 'active fw-bold shadow-sm border-2' : '' }}"
                       for="status-{{ $statusValue }}"
                       style="min-width: 140px;">
                    <span class="rounded-circle d-inline-block me-2"
                          style="width:10px;height:10px;background-color: var(--bs-{{ $statusMeta[$statusValue]['color'] }});"></span>
                    <i class="bi {{ $statusMeta[$statusValue]['icon'] }} me-1"></i>
                    <span>{{ $statusMeta[$statusValue]['label'] }}</span>
                    <span class="small ms-2">({{ $allTodayOrders->where('status', $statusValue)->count() }})</span>
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