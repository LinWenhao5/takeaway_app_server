{{-- resources/views/components/table-sort-th.blade.php --}}
@props([
    'field',
    'label'
])

@php
    $isCurrentField = request('sort_by') === $field;
    $currentOrder = request('sort_order');
    $nextOrder = ($isCurrentField && $currentOrder === 'asc') ? 'desc' : 'asc';
@endphp

<th>
    <div class="d-flex align-items-center">
        <a href="{{ request()->fullUrlWithQuery(['sort_by' => $field, 'sort_order' => $nextOrder]) }}" 
           class="text-decoration-none text-secondary d-inline-flex align-items-center">
            {{ $label }}
            
            @if($isCurrentField)
                <small class="ms-1 fw-bold">{{ $currentOrder === 'asc' ? '↑' : '↓' }}</small>
            @endif
        </a>

        @if($isCurrentField)
            <a href="{{ request()->fullUrlWithQuery(['sort_by' => null, 'sort_order' => null]) }}" 
               class="text-secondary ms-2 text-decoration-none" 
               style="font-size: 0.85rem; line-height: 1;" 
               title="清除排序">
                &times;
            </a>
        @endif
    </div>
</th>