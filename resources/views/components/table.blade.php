<div class="table-responsive">
    <table class="table table-hover align-middle {{ $class ?? '' }}">
        <thead class="bg-light text-secondary">
            {{ $head }}
        </thead>
        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>

@if (isset($pagination) && $pagination)
    <div class="mt-3">
        {{ $pagination }}
    </div>
@endif