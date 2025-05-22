@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h1>Product Management</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }}</td>
                <td>
                    @foreach ($product->media->take(2) as $media)
                    <img src="{{ asset('storage/' . $media->path) }}" alt="Image" width="50" style="margin-right: 4px;">
                @endforeach
                @if ($product->media->count() > 2)
                    <span style="font-size: 20px; vertical-align: middle;">...</span>
                @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">Edit</a>
                    <delete-confirm
                        action="{{ route('admin.products.destroy', $product) }}"
                        title="Delete Product?"
                        text="Are you sure you want to delete the product '{{ $product->name }}'?"
                        confirm-button-text="Yes, delete it!"
                        success-message="Product deleted successfully!"
                        error-message="Failed to delete the product."
                    >
                    </delete-confirm>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@vite('resources/js/delete-confirm.js')
@endsection