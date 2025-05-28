@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.product-categories.index') }}">Product Catgories</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h2>Category Management</h2>
    <form action="{{ route('admin.product-categories.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="name" class="form-control" placeholder="New Category Name" required>
            <button class="btn btn-success" type="submit">Add Category</button>
        </div>
    </form>

    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            @if($category->media)
                                <img src="{{ asset('storage/' . $category->media->path) }}"
                                     alt="{{ $category->media->name }}"
                                     class="rounded me-2"
                                     style="height:32px;width:32px;object-fit:cover;">
                            @endif
                            {{ $category->name }}
                        </span>
                        <span>
                            <span class="badge bg-secondary">{{ $category->products->count() }} Products</span>
                            <div class="dropdown d-inline ms-2">
                                <button class="btn btn-sm btn-outline-secondary border-0" type="button" id="dropdownMenuButton{{ $category->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $category->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.product-categories.edit', $category) }}">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <x-delete-confirm
                                            :action="route('admin.product-categories.destroy', $category)"
                                            title="Delete Category"
                                            text="Are you sure you want to delete the category '{{ $category->name }}'?"
                                            confirm-button-text="Yes, delete it!"
                                            success-message="Category deleted successfully!"
                                            error-message="Failed to delete the category."
                                        >
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="bi bi-trash me-1"></i> Delete
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
                                <li>{{ $product->name }}</li>
                            @endforeach
                        </ul>
                        <form action="{{ route('admin.product-categories.assignProduct', $category) }}" method="POST" class="mt-2">
                            @csrf
                            <div class="input-group">
                                <select name="product_id" class="form-select" required>
                                    <option value="">Assign product...</option>
                                    @foreach($products as $product)
                                        @if($product->product_category_id != $category->id)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" type="submit">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection