<div class="border-top shadow-lg p-3 bg-body flex-shrink-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold">@lang('pos.total_payable'):</span>
        <span class="font-monospace fw-black fs-3">€{{ $totalPrice }}</span>
    </div>

    <div class="row g-2 mb-3">
        <div class="col"><button type="button" wire:click="$set('paymentMethod', 'CASH')" class="btn w-100 btn-sm {{ $paymentMethod === 'CASH' ? 'btn-primary' : 'btn-outline-secondary' }}">💵 @lang('pos.cash_payment')</button></div>
        <div class="col"><button type="button" wire:click="$set('paymentMethod', 'CARD')" class="btn w-100 btn-sm {{ $paymentMethod === 'CARD' ? 'btn-primary' : 'btn-outline-secondary' }}">💳 @lang('pos.card_payment')</button></div>
    </div>

    @if($paymentMethod === 'CASH')
        <button class="btn btn-sm btn-outline-info w-100 mb-2" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#cashKeyboardCollapse" 
                aria-expanded="false" 
                aria-controls="cashKeyboardCollapse">
            <i class="bi bi-keyboard"></i> @lang('pos.open_collapsed_number_keyboard')
        </button>

        <div wire:ignore.self class="collapse" id="cashKeyboardCollapse">
            @include('pos::_cash_keyboard')
        </div>
    @endif

    <input type="text" wire:model="note" placeholder="{{ __('pos.note_placeholder') }}" class="form-control form-control-sm mb-2">
    
    <button wire:click="initiateCheckout" @if(empty($cartItems)) disabled @endif class="btn btn-success w-100 py-3 fw-bold shadow">
        @lang('pos.checkout_btn')
    </button>
</div>