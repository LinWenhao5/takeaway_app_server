@extends('layouts.app')

@section('title', __('product_categories.category_management'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.product-categories.index') }}">
                @lang('product_categories.product_categories')
            </a>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h2>@lang('product_categories.category_management')</h2>
    <form action="{{ route('admin.product-categories.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="name" class="form-control" placeholder="@lang('product_categories.new_category_placeholder')" required>
            <button class="btn btn-success" type="submit">@lang('product_categories.add_category')</button>
        </div>
    </form>

    <ul id="category-list" class="row list-unstyled">
        @foreach($categories as $category)
            <li class="col-md-6 mb-4" data-id="{{ $category->id }}">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            @if($category->media)
                                <img src="{{ $category->media->path }}"
                                     alt="{{ $category->media->name }}"
                                     class="rounded me-2"
                                     style="height:32px;width:32px;object-fit:cover;">
                            @endif
                            {{ $category->name }}
                        </span>
                        <span>
                            <span class="badge bg-secondary">
                                @lang('product_categories.products_count', ['count' => $category->products->count()])
                            </span>
                            <div class="dropdown d-inline ms-2">
                                <button class="btn btn-sm btn-outline-secondary border-0" type="button" id="dropdownMenuButton{{ $category->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $category->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.product-categories.edit', $category) }}">
                                            <i class="bi bi-pencil me-1"></i> @lang('product_categories.edit')
                                        </a>
                                    </li>
                                    <li>
                                        <x-delete-confirm
                                            :action="route('admin.product-categories.destroy', $category)"
                                            :title="__('product_categories.delete')"
                                            :text="__('product_categories.delete_confirm', ['name' => $category->name])"
                                            :confirm-button-text="__('product_categories.yes_delete')"
                                            :success-message="__('product_categories.delete_success')"
                                            :error-message="__('product_categories.delete_error')"
                                        >
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="bi bi-trash me-1"></i> @lang('product_categories.delete')
                                            </a>
                                        </x-delete-confirm>
                                    </li>
                                </ul>
                            </div>
                        </span>
                    </div>
                    <div class="card-body">
                        <ul>
                             @foreach($category->products as $product)
                                <li class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <strong class="fw-bold">{{ $product->id }}. {{ $product->name }}</strong>
                                        <p class="mb-1 text-muted small">{{ $product->description }}</p>
                                        <span class="fw-bold text-success">€ {{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary ms-2">@lang('product_categories.edit')</a>
                                        <form action="{{ route('admin.product-categories.unassignProduct', [$category, $product]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">@lang('product_categories.unassign')</button>
                                        </form>
                                    </div>
                                </li>
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </ul>
                        <form action="{{ route('admin.product-categories.assignProduct', $category) }}" method="POST" class="mt-2">
                            @csrf
                            <div class="input-group">
                                <select name="product_id" class="form-select" required>
                                    <option value="">@lang('product_categories.assign_product')...</option>
                                    @foreach($products as $product)
                                        @if($product->product_category_id != $category->id)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} 
                                                {{ $product->category ? '[' . $product->category->name . ']' : '(' . __('product_categories.uncategorized') . ')' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" type="submit">@lang('product_categories.assign_product')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('category-list');
    Sortable.create(el, {
        animation: 150,
        onEnd: function () {
            let order = [];
            document.querySelectorAll('#category-list li').forEach(function (li, idx) {
                order.push({id: li.getAttribute('data-id'), sort_order: idx});
            });
            fetch("{{ route('admin.product-categories.sort') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({order: order})
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    console.log('Categories sorted successfully');
                } else {
                    console.error('Failed to sort categories:', data.message);
                }
            }).catch(error => {
                console.error('Error sorting categories:', error);
            });
        }
    });
});
</script>
@endsection