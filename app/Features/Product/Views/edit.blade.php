@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">@lang('products.product_management')</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('products.edit')</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h1>@lang('products.edit_product')</h1>
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">@lang('products.name')</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">@lang('products.description')</label>
            <input id="description" type="hidden" name="description" value="{{ old('description', $product->description) }}">
            <trix-editor input="description"></trix-editor>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">@lang('products.price')</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}" required step="0.01">
        </div>
        <div class="mb-3">
            <x-media-selector :media="$media" :selected="$selectedMedia" :label="__('products.select_media')" name="media" :multiple="true"/>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ms-2">@lang('products.back')</a>
        <button type="submit" class="btn btn-primary">@lang('products.update')</button>
    </form>
</div>
@endsection

@section('right-sidebar')
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h5 class="card-title mb-3">@lang('products.assign_category')</h5>
            <form action="{{ route('admin.products.assignCategory') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="mb-2">
                    <label for="category_id" class="form-label">@lang('products.category')</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if($product->product_category_id == $category->id) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-primary w-100">@lang('products.assign_category')</button>
            </form>
        </div>
    </div>
@endsection