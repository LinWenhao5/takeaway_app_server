<div class="card border border-light-subtle shadow-sm rounded-3 {{ $class ?? '' }}">
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="table-light border-bottom border-light-subtle text-secondary">
                {{ $head }}
            </thead>
            <tbody class="table-group-divider border-light-subtle text-dark">
                {{ $body }}
            </tbody>
        </table>
    </div>

    @if (isset($pagination) && trim($pagination))
        <div class="card-footer bg-white border-top border-light-subtle py-3 px-4 d-flex justify-content-end">
            {{ $pagination }}
        </div>
    @endif
</div>