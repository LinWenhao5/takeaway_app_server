<div class="bg-light border-end overflow-auto h-100" style="width: 25%; min-width: 120px;">
    <div class="list-group list-group-flush p-2 gap-1">
        @foreach($categories as $category)
            <button 
                type="button"
                wire:click="selectCategory({{ $category->id }})"
                class="list-group-item list-group-item-action border-0 rounded-3 py-3 fw-bold text-start {{ $selectedCategoryId == $category->id ? 'active bg-dark text-white' : '' }}"
            >
                {{ $category->name }}
            </button>
        @endforeach
        <button 
            type="button"
            wire:click="selectCategory('uncategorized')"
            class="list-group-item list-group-item-action border-0 rounded-3 py-3 fw-bold text-start {{ $selectedCategoryId === 'uncategorized' ? 'active bg-dark text-white' : 'text-danger' }}"
        >
            @lang('pos.uncategorized')
        </button>
    </div>
</div>