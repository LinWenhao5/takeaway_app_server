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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Product Management</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

    <x-table class="mt-4">
        <x-slot:head>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }}</td>
                <td>
                    @foreach ($product->media->take(2) as $media)
                    <img src="{{ $media->path }}" alt="Image" width="50" style="margin-right: 4px;">
                    @endforeach
                    @if ($product->media->count() > 2)
                    <span style="font-size: 20px; vertical-align: middle;">...</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                        <x-delete-confirm
                            :action="route('admin.products.destroy', $product)"
                            title="Delete Product?"
                            text="Are you sure you want to delete the product '{{ $product->name }}'?"
                            confirm-button-text="Yes, delete it!"
                            success-message="Product deleted successfully!"
                            error-message="Failed to delete the product."
                            button-class="btn btn-outline-danger btn-sm"
                        >
                            <button type="button" class="btn btn-outline-danger btn-sm">Delete</button>
                        </x-delete-confirm>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-slot:body>
        <x-slot:pagination>
            {{ $products->links() }}
        </x-slot:pagination>
    </x-table>
</div>
@endsection