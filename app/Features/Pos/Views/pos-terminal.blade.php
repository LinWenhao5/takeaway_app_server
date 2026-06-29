<div class="container-fluid vh-100 overflow-hidden bg-light text-dark shadow-sm">
    <div class="row h-100 g-0">
        
        <div class="col-md-8 h-100 d-flex flex-column bg-white border-end">
            <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
                <a href="/" class="btn btn-sm btn-icon btn-light rounded-circle shadow-sm" title="@lang('common.exit')">
                    <i class="bi bi-x-lg"></i>
                </a>
                <h1 class="h5 mb-0 fw-bold">@lang('pos.terminal_title')</h1>
                <div>
                    @if($errorMessage) <span class="badge bg-danger p-1 small">⚠️ {{ $errorMessage }}</span> @endif
                    @if($successMessage) <span class="badge bg-success p-1 small">✅ {{ $successMessage }}</span> @endif
                </div>
            </div>
            <div class="flex-grow-1 d-flex overflow-hidden">
                @include('pos::_categories')
                @include('pos::_products')
            </div>
        </div>

        <div class="col-md-4 h-100 d-flex flex-column bg-light">
            @include('pos::_cart')
        </div>

    </div>
</div>

<style>
    .btn-outline-dark:hover .text-muted {
        color: #ffffff !important;
    }
    
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; white-space: normal; }
    .fw-black { font-weight: 900 !important; }
</style>