<div class="container-fluid vh-100 overflow-hidden bg-body text-body shadow-sm">
    <div class="row h-100 g-0">
        
        <div class="col-md-8 h-100 d-flex flex-column bg-body border-end border-subtle">
            <div class="p-2 border-bottom border-subtle d-flex justify-content-between align-items-center">
                <a href="/" class="btn btn-sm btn-icon btn-outline-secondary rounded-circle shadow-sm" title="@lang('common.exit')">
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

        <div class="col-md-4 h-100 d-flex flex-column bg-body-tertiary border-start border-subtle">
            @include('pos::_cart')
        </div>

    </div>
</div>

<style>
    [data-bs-theme="dark"] .btn-outline-dark:hover .text-muted,
    .btn-outline-secondary:hover .text-muted {
        color: var(--bs-white) !important;
    }
    
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; white-space: normal; }
    .fw-black { font-weight: 900 !important; }
</style>