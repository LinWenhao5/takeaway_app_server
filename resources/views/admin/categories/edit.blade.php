@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 420px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Edit Category</h3>
            <form action="{{ route('admin.product-categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        required
                    >
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success px-4">Save</button>
                    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection