@extends('layouts.app')

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
                        <span>{{ $category->name }}</span>
                        <span class="badge bg-secondary">{{ $category->products->count() }} Products</span>
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