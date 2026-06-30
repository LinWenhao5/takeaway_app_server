<div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-shrink-0 bg-body">
    <span class="fw-bold small text-body">
        @lang('pos.cart_details') ({{ $totalQuantity }} @lang('pos.items'))
    </span>
    
    <button wire:click="clearCart" 
            class="btn btn-link btn-sm text-danger p-0 text-decoration-none fw-semibold"
            onclick="confirm('@lang('pos.confirm_clear_cart')') || event.stopImmediatePropagation()">
        @lang('pos.clear')
    </button>
</div>