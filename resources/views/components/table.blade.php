<div class="card border border-light-subtle shadow-sm rounded-3 {{ $class ?? '' }}">
    
    @if (isset($filters) && trim($filters))
        <div class="card-header border-bottom border-light-subtle py-3 px-3 px-md-4">
            {{ $filters }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-nowrap text-sm-start">
            
            <thead class="border-bottom border-light-subtle">
                {{ $head }}
            </thead>
            
            <tbody class="table-group-divider border-light-subtle">
                {{ $body }}
            </tbody>
        </table>
    </div>

    @if (isset($pagination) && trim($pagination))
        <div class="card-footer border-top border-light-subtle py-3 px-3 px-md-4 d-flex justify-content-center justify-content-md-end">
            {{ $pagination }}
        </div>
    @endif
</div>