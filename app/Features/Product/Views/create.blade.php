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
            <textarea id="description" name="description" class="form-control" placeholder="@lang('products.enter_description')">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">@lang('products.price')</label>
            <input type="number" name="price" id="price" class="form-control" placeholder="@lang('products.enter_price')" required step="0.01">
        </div>
        <div class="mb-3">
            <label for="discount_price" class="form-label">@lang('products.discount_price')</label>
            <input type="number" name="discount_price" id="discount_price" class="form-control" placeholder="@lang('products.enter_discount_price')" step="0.01">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_out_of_stock" id="is_out_of_stock" class="form-check-input" value="1">
            <label class="form-check-label" for="is_out_of_stock">@lang('products.mark_as_out_of_stock')</label>
        </div>
        
        <div class="mb-3">
            <a href="javascript:history.back()" class="btn btn-secondary me-2">@lang('products.back')</a>
            <button type="submit" class="btn btn-primary">@lang('products.save')</button>
        </div>

        <div class="mb-3">
            <x-media-selector :media="$media" :label="__('products.select_media')" name="media" />
        </div>
    </form>
</div>
@endsection
