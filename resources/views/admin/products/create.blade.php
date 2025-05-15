@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Product</h1>
    <form action="{{ route('web.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter product name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter product description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" placeholder="Enter product price" required>
        </div>
        <div class="mb-3">
            <x-media-selector :media="$media" label="Select Media" name="media" />
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
