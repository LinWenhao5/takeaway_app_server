{{-- filepath: resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Category</h2>
    <form action="{{ route('admin.product-categories.update', $category->id) }}" method="POST" class="mt-4" style="max-width:400px;">
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
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection