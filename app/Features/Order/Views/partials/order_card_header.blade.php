@php
    $badgeStyles = config('order_status.status_badge_styles');
    $type = $order->order_type->value;
    $typeBadge = config('order_status.type_badge_styles')[$type] ?? ['bg' => 'secondary', 'text' => 'white'];
    $bgClass = 'bg-' . ($badgeStyles[$status]['bg'] ?? 'secondary');
    $textClass = 'text-' . ($badgeStyles[$status]['text'] ?? 'white');
@endphp

{{-- 使用 py-1 进一步压缩高度，gap-1 极度压缩间距 --}}
<div class="card-header d-flex justify-content-between align-items-center py-1 px-2 bg-transparent">
    
    {{-- 左侧合并：类型与序号连接 --}}
    <div class="d-flex align-items-center">
        <span class="badge bg-{{ $typeBadge['bg'] }} text-{{ $typeBadge['text'] }} rounded-start rounded-0 px-2" style="font-size: 0.75rem;">
            @lang('orders.' . strtolower($type))
        </span>
        <span class="fw-bold bg-light px-2 border border-start-0 rounded-end" style="font-size: 0.75rem;">
            #{{ $order->daily_sequence ?? '—' }}
        </span>
    </div>

    {{-- 右侧合并：状态与时间紧贴 --}}
    <div class="d-flex align-items-center gap-1">
        
        <span class="text-muted" style="font-size: 0.75rem;">
            {{ \Carbon\Carbon::parse($order->reserve_time)->format('H:i') }}
        </span>

        {{-- 下拉菜单维持原有位置 --}}
        <div class="ms-1">
            @include('order::partials.order_card_dropdown', ['order' => $order, 'allStatuses' => $allStatuses ?? []])
        </div>
    </div>
</div>