<div class="p-3 bg-secondary bg-opacity-10 fw-bold border-bottom d-flex justify-content-between align-items-center">
    <span class="text-secondary small">@lang('pos.cart_details') ({{ $totalQuantity }} @lang('pos.items'))</span>
    <button wire:click="clearCart" class="btn btn-link btn-sm text-danger p-0 text-decoration-none fw-semibold">@lang('pos.clear')</button>
</div>

<div class="flex-grow-1 overflow-auto p-3">
    @forelse($cartItems as $item)
        <div class="card border-0 shadow-sm p-2 rounded-3 mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="w-50">
                    <div class="fw-bold text-dark text-truncate small">{{ $item['name'] }}</div>
                    <div class="text-muted small" style="font-size: 11px;">€{{ $item['final_price'] }}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button wire:click="decreaseQuantity({{ $item['id'] }})" class="btn btn-sm btn-light rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">-</button>
                    <span class="font-monospace fw-bold text-center small" style="width: 20px;">{{ $item['quantity'] }}</span>
                    <button wire:click="addToCart({{ $item['id'] }})" class="btn btn-sm btn-light rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">+</button>
                </div>
                <div class="text-end shadow-none" style="width: 70px;">
                    <span class="font-monospace fw-bold small">€{{ $item['subtotal'] }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted py-5">
            <span class="display-6 mb-2">🛒</span>
            <span class="small">@lang('pos.cart_empty')</span>
        </div>
    @endforelse
</div>

<div class="p-3 bg-white border-top shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted fw-bold">@lang('pos.total_payable'):</span>
        <span class="font-monospace fw-black display-6 text-dark">€{{ $totalPrice }}</span>
    </div>

    <div class="row g-2 mb-3">
        <div class="col"><button type="button" wire:click="$set('paymentMethod', 'CASH')" class="btn w-100 py-2.5 fw-bold {{ $paymentMethod === 'CASH' ? 'btn-dark' : 'btn-outline-secondary' }}">💵 @lang('pos.cash_payment')</button></div>
        <div class="col"><button type="button" wire:click="$set('paymentMethod', 'CARD')" class="btn w-100 py-2.5 fw-bold {{ $paymentMethod === 'CARD' ? 'btn-dark' : 'btn-outline-secondary' }}">💳 @lang('pos.card_payment')</button></div>
    </div>

    @if($paymentMethod === 'CASH')
        <div class="bg-light p-2 rounded-3 border border-light-subtle shadow-inner mb-3">
            <div class="row align-items-center g-2">
                <div class="col-auto"><label class="small fw-bold text-muted ps-1">@lang('pos.cash_received'):</label></div>
                <div class="col"><input type="number" step="0.01" wire:model.live="cashReceived" class="form-control text-end font-monospace fw-bold fs-5" placeholder="0.00"></div>
                <div class="col-auto ps-3 border-start">
                    <div class="text-muted text-uppercase" style="font-size: 9px; font-weight: bold;">@lang('pos.change')</div>
                    <div class="font-monospace fw-bold text-danger fs-5">€{{ $this->change }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-3"><input type="text" wire:model="note" placeholder="{{ __('pos.note_placeholder') }}" class="form-control form-control-sm bg-light"></div>
    <button wire:click="checkout" @if(empty($cartItems)) disabled @endif class="btn btn-success w-100 py-3 fw-bold fs-5 rounded-3 shadow">@lang('pos.checkout_btn')</button>
</div>