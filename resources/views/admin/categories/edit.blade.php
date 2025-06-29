@extends('layouts.app')

@section('title', __('product_categories.edit') . ' - ' . __('product_categories.product_categories'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.product-categories.index') }}">@lang('product_categories.product_categories')</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('product_categories.edit')</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h2>@lang('product_categories.edit_category')</h2>
    <form action="{{ route('admin.product-categories.update', $category->id) }}" method="POST" class="mt-4 w-100">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">@lang('product_categories.category_name')</label>
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
            <x-media-selector :media="$media" :selected="$category->media_id" :label="__('product_categories.select_media')" name="media_id" />
        </div>
        <button type="submit" class="btn btn-success">@lang('product_categories.save')</button>
        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary ms-2">@lang('product_categories.cancel')</a>
    </form>
</div>
@endsection