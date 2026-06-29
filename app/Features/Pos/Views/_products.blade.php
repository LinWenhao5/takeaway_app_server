
<div class="flex-grow-1 overflow-auto p-3 h-100 bg-body" style="width: 75%;">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-1">
        @forelse($products as $product)
            <div class="col d-flex">
                <button 
                    wire:click="addToCart({{ $product->id }})"
                    class="btn w-100 h-100 p-3 text-start d-flex flex-column justify-content-between 
                        border border-subtle rounded-3 position-relative shadow-none 
                        {{ $product->is_out_of_stock ? 'bg-body-secondary text-muted opacity-50 border-0' : 'btn-outline-secondary' }}"
                    style="min-height: 105px; transition: all 0.25s ease;"
                    @if($product->is_out_of_stock) disabled @endif
                >
                    <span class="fw-bold small text-truncate-2 w-100 text-body">
                        {{ $product->name }}
                    </span>
                    
                    <div class="d-flex justify-content-between align-items-center w-100 mt-2">
                        <span class="font-monospace fw-semibold text-muted small">€{{ $product->final_price ?? $product->price }}</span>
                        
                        @if($product->is_out_of_stock)
                            <span class="badge bg-secondary text-uppercase position-absolute top-0 end-0 m-1" style="font-size: 8px;">
                                {{ __('pos.out_of_stock') }}
                            </span>
                        @elseif($product->is_discounted)
                            <span class="badge bg-danger text-uppercase position-absolute top-0 end-0 m-1" style="font-size: 8px;">PROMO</span>
                        @endif
                    </div>
                </button>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <span class="d-block mb-2">🍱</span>
                <span class="small">@lang('pos.no_category_products')</span>
            </div>
        @endforelse
    </div>
</div>