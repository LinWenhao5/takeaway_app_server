@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.product-categories.index') }}">Product Catgories</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h2>Edit Category</h2>
    <form action="{{ route('admin.product-categories.update', $category->id) }}" method="POST" class="mt-4 w-100">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name', $category->name) }}"
                required
            >
        </div>
        <div class="mb-3">
            <x-media-selector :media="$media" :selected="$category->media_id" label="Select Media" name="media_id" />
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection