@props([
    'title',
    'value',
    'colorClass' => 'text-body'
])

<div class="card shadow-sm border-0 bg-body-tertiary h-100">
    <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
            <small class="text-muted d-block text-uppercase fw-semibold mb-1">{{ $title }}</small>
            <h3 class="mb-0 fw-bold {{ $colorClass }}">{{ $value }}</h3>
        </div>
        <div class="mt-2">
            {{ $slot }}
        </div>
    </div>
</div>