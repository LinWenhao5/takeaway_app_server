@props([
    'title',
    'value',
    'unit' => '',
    'badge' => null,
    'percent' => 0,
    'barColor' => 'bg-primary'
])

<div class="card shadow-sm border-0 bg-body-tertiary h-100">
    <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
            <small class="text-muted d-block text-uppercase fw-semibold mb-1">{{ $title }}</small>
            <div class="d-flex align-items-baseline justify-content-between">
                <h3 class="mb-0 fw-bold text-body">
                    {{ $value }} 
                    @if($unit)<span class="fs-6 text-muted fw-normal">{{ $unit }}</span>@endif
                </h3>
                @if($badge)
                    <span class="badge bg-primary-subtle text-primary fw-bold">{{ $badge }}</span>
                @endif
            </div>
        </div>
        <div class="mt-3">
            <div class="progress bg-secondary-subtle" style="height: 6px;">
                <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $percent }}%"></div>
            </div>
        </div>
    </div>
</div>