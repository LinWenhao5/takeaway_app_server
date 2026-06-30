<div class="modal d-block" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">@lang('pos.confirm_order')</h5>
            </div>
            
            <div class="modal-body text-center py-4">
                <div class="text-muted small mb-1">@lang('pos.total_payable')</div>
                <div class="display-5 fw-black text-primary">€{{ $totalPrice }}</div>
            </div>
            
            <div class="modal-footer border-0 p-3 pt-0 gap-2">
                <button wire:click="$set('showConfirmModal', false)" 
                        class="btn btn-light flex-fill rounded-pill">
                    @lang('pos.cancel')
                </button>
                <button wire:click="confirmCheckout" 
                        class="btn btn-success flex-fill rounded-pill px-4">
                    @lang('pos.confirm')
                </button>
            </div>
        </div>
    </div>
</div>