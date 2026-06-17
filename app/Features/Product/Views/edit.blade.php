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
            <textarea id="description" name="description" class="form-control" placeholder="@lang('products.enter_description')">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">@lang('products.price')</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}" required step="0.01">
        </div>
        <div class="mb-3">
            <label for="discount_price" class="form-label">@lang('products.discount_price')</label>
            <input type="number" name="discount_price" id="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price) }}" step="0.01">
        </div>
        <div class="mb-3 form-check">
            <input
                type="checkbox"
                name="is_out_of_stock"
                id="is_out_of_stock"
                class="form-check-input"
                value="1"
                @checked(old('is_out_of_stock', $product->is_out_of_stock))
            >
            <label class="form-check-label" for="is_out_of_stock">@lang('products.mark_as_out_of_stock')</label>
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

            <hr>
            <h5 class="card-title mb-3">@lang('products.assign_vat')</h5>
            <form action="{{ route('admin.products.assignVat') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="mb-2">
                    <label for="vat_rate_id" class="form-label">@lang('products.vat_rate')</label>
                    <select name="vat_rate_id" id="vat_rate_id" class="form-select" required>
                        @foreach($vats as $vat)
                            <option value="{{ $vat->id }}" @if($product->vat_rate_id == $vat->id) selected @endif>
                                {{ $vat->name }} ({{ $vat->rate }}%)
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-outline-success w-100">@lang('products.assign_vat')</button>
            </form>
        </div>
    </div>
@endsection