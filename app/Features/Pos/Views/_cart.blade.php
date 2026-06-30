<div class="h-100 d-flex flex-column">
    @include('pos::_cart_header')
    @include('pos::_cart_list')
    @include('pos::_cart_footer')
    
    @if($showConfirmModal)
        @include('pos::_confirm_modal') 
    @endif
</div>