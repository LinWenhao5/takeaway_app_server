<div class="flex-grow-1 overflow-auto bg-body-tertiary p-2">
    @forelse($cartItems as $item)
        <div class="card border-0 shadow-sm p-2 rounded-3 mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="w-50">
                    <div class="fw-bold small text-truncate">{{ $item['name'] }}</div>
                    <div class="text-muted" style="font-size: 11px;">€{{ $item['final_price'] }}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button wire:click="decreaseQuantity({{ $item['id'] }})" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 30px; height: 30px;">-</button>
                    <span class="font-monospace fw-bold">{{ $item['quantity'] }}</span>
                    <button wire:click="addToCart({{ $item['id'] }})" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 30px; height: 30px;">+</button>
                </div>
                <div class="text-end fw-bold" style="width: 60px;">€{{ $item['subtotal'] }}</div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">🛒 @lang('pos.cart_empty')</div>
    @endforelse
</div>