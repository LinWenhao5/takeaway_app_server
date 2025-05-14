@extends('layouts.app')

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
                <td><img src="{{ $product->image_url }}" alt="Image" width="50"></td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form 
                    action="{{ route('admin.products.destroy', $product) }}" 
                    method="POST" 
                    style="display:inline;"
                    class="delete-form"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@vite('resources/js/delete-product.js')
@endsection