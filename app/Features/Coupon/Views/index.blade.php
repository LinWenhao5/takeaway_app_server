@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">@lang('coupon.coupon_management')</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-body">@lang('coupon.coupon_management')</h1>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary shadow-sm">@lang('coupon.add_new')</a>
    </div>

    <div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
        @php
            $totalAssigned = $coupons->sum('received_quantity');
            $totalCap = $coupons->sum('total_quantity');
            $claimPercent = $totalCap > 0 ? round(($totalAssigned / $totalCap) * 100) : 0;

            $fixedCount = $coupons->where('type', 'fixed')->count();
            $totalTypes = $coupons->count();
            $fixedRate = $totalTypes > 0 ? ($fixedCount / $totalTypes) * 100 : 0;
        @endphp

        <div class="col">
            <x-coupon::coupon-stat-progress
                :title="__('coupon.stat_claim_rate')" 
                :value="$totalAssigned" 
                :unit="__('coupon.stat_unit_issued')" 
                :badge="$claimPercent . '%'"
                :percent="$claimPercent"
                barColor="bg-primary"
            />
        </div>

        <div class="col">
            <x-coupon::coupon-stat-list 
                :title="__('coupon.stat_active_coupons')" 
                :value="$coupons->where('is_active', true)->count()"
                colorClass="text-success"
            >
                <div class="d-flex flex-wrap gap-1">
                    @foreach($coupons->take(12) as $c)
                        <span class="d-inline-block rounded-circle {{ $c->is_active ? 'bg-success' : 'bg-danger' }}" style="width: 8px; height: 8px;" title="{{ $c->name }}"></span>
                    @endforeach
                </div>
            </x-coupon::coupon-stat-list>
        </div>

        <div class="col">
            <x-coupon::coupon-stat-progress 
                :title="__('coupon.stat_type_structure')" 
                :value="$fixedCount" 
                :unit="'/ ' . $totalTypes" 
                :percent="$fixedRate"
                barColor="bg-info"
            />
        </div>

        <div class="col">
            <x-coupon::coupon-stat-list 
                :title="__('coupon.stat_relative_coupons')" 
                :value="$coupons->whereNotNull('valid_days')->count()"
            >
                <small class="text-muted d-block">
                    {{ __('coupon.stat_unlimited_duration') }}: {{ $coupons->whereNull('valid_days')->whereNull('use_end_at')->count() }}
                </small>
            </x-coupon::coupon-stat-list>
        </div>
    </div>

    <x-table class="mt-4 border-secondary-subtle">
        <x-slot:head>
            <tr class="table-light dark:table-dark text-body">
                <th>@lang('coupon.name')</th>
                <th>@lang('coupon.code')</th>
                <th>@lang('coupon.type_value')</th>
                <th>@lang('coupon.min_order_amount')</th>
                <th>@lang('coupon.usage_status')</th>
                <th>@lang('coupon.validity_fact')</th>
                <th>@lang('coupon.status')</th>
                <th>@lang('coupon.actions')</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @foreach ($coupons as $coupon)
            <tr class="align-middle text-body">
                <td><div class="fw-bold text-body">{{ $coupon->name }}</div></td>
                <td><code class="text-secondary bg-body-tertiary px-2 py-1 rounded" style="font-size: 0.9em;">{{ $coupon->code }}</code></td>
                <td>
                    @if($coupon->type === 'fixed')
                        <span class="text-info fw-semibold">€{{ number_format($coupon->value, 2) }}</span>
                    @else
                        <span class="text-warning fw-semibold">{{ $coupon->value * 100 }}% OFF</span>
                    @endif
                </td>
                <td>
                    @if($coupon->min_order_amount > 0)
                        <span>€{{ number_format($coupon->min_order_amount, 2) }}</span>
                    @else
                        <span class="text-muted">@lang('coupon.no_threshold')</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-body fw-semibold">{{ $coupon->received_quantity }}</small>
                        <div class="progress flex-grow-1 bg-secondary-subtle" style="height: 4px; max-width: 60px;">
                            @if($coupon->total_quantity)
                                <div class="progress-bar bg-primary" style="width: {{ min(($coupon->received_quantity / $coupon->total_quantity) * 100, 100) }}%"></div>
                            @else
                                <div class="progress-bar bg-secondary" style="width: 100%"></div>
                            @endif
                        </div>
                        <small class="text-muted">/ {{ $coupon->total_quantity ?? '∞' }}</small>
                    </div>
                </td>
                <td>
                    @if($coupon->valid_days)
                        <span class="badge bg-info-subtle text-info">@lang('coupon.valid_days_badge', ['days' => $coupon->valid_days])</span>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $coupon->use_start_at?->format('Y-m-d') }} @lang('coupon.to') {{ $coupon->use_end_at?->format('Y-m-d') }}</div>
                    @elseif($coupon->use_end_at)
                        <span class="badge bg-secondary-subtle text-body">@lang('coupon.fixed_period_badge')</span>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $coupon->use_start_at?->format('Y-m-d') }} @lang('coupon.to') {{ $coupon->use_end_at?->format('Y-m-d') }}</div>
                    @else
                        <span class="badge bg-light text-muted border border-secondary-subtle">@lang('coupon.unlimited_badge')</span>
                    @endif
                </td>
                <td>
                    @if($coupon->is_active)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">@lang('coupon.status_active')</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">@lang('coupon.status_inactive')</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-primary btn-sm">@lang('coupon.edit')</a>
                        <x-delete-confirm
                            :action="route('admin.coupons.destroy', $coupon)"
                            :title="__('coupon.delete_title')"
                            :text="__('coupon.delete_text', ['name' => $coupon->name])"
                            :confirm-button-text="__('coupon.confirm_delete')"
                            :success-message="__('coupon.success_delete')"
                            :error-message="__('coupon.error_delete')"
                            button-class="btn btn-outline-danger btn-sm"
                        >
                            <button type="button" class="btn btn-outline-danger btn-sm">@lang('coupon.delete')</button>
                        </x-delete-confirm>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-slot:body>
        <x-slot:pagination>
            {{ $coupons->links() }}
        </x-slot:pagination>
    </x-table>
</div>
@endsection