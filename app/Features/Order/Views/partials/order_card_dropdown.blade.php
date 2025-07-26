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
        @php
            $type = $order->order_type->value;
            $status = $order->status->value;
            $allStates = $allStatuses ?? [];
            // 定义每种类型允许的状态顺序
            $typeStates = [
                'pickup' => ['paid', 'waiting_pickup', 'completed'],
                'delivery' => ['paid', 'delivering', 'completed'],
            ];
            $allowedStates = $typeStates[$type] ?? [];
            $currentIndex = array_search($status, $allowedStates);
            $nextStatus = $allowedStates[$currentIndex + 1] ?? null;
        @endphp

        {{-- 状态切换（非未支付时） --}}
        @if($status !== 'unpaid' && $nextStatus)
            @php
                $stateIcons = [
                    'paid' => 'bi-cash-coin',
                    'waiting_pickup' => 'bi-clock',
                    'delivering' => 'bi-truck',
                    'completed' => 'bi-check-circle',
                ];
                $icon = $stateIcons[$nextStatus] ?? 'bi-arrow-repeat';
            @endphp
            <li>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                    @csrf
                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                    <button type="submit" class="dropdown-item">
                        <i class="bi {{ $icon }} me-1"></i>
                        @lang('orders.mark_as') @lang('orders.' . $nextStatus)
                    </button>
                </form>
            </li>
        @endif

        {{-- 删除按钮（仅未支付时） --}}
        @if($status === 'unpaid')
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