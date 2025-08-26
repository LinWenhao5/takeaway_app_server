@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">@lang('products.product_management')</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('products.add_new')</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h1 class="mb-4">@lang('products.add_new')</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">@lang('products.name')</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="@lang('products.enter_name')" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">@lang('products.description')</label>
            <input id="description" type="hidden" name="description" value="{{ old('description') }}">
            <trix-editor input="description"></trix-editor>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">@lang('products.price')</label>
            <input type="number" name="price" id="price" class="form-control" placeholder="@lang('products.enter_price')" required step="0.01">
        </div>
        <div class="mb-3">
            <x-media-selector :media="$media" :label="__('products.select_media')" name="media" />
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ms-2">@lang('products.back')</a>
        <button type="submit" class="btn btn-primary">@lang('products.save')</button>
    </form>
</div>
@endsection
