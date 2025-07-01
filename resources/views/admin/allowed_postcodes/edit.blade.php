@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.allowed-postcodes.index') }}">@lang('allowed_postcodes.breadcrumb')</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">@lang('allowed_postcodes.edit')</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h1 class="mb-4">@lang('allowed_postcodes.edit_title')</h1>
    <form action="{{ route('admin.allowed-postcodes.update', $allowedPostcode) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="postcode_pattern" class="form-label">@lang('allowed_postcodes.postcode_label')</label>
            <input type="text" name="postcode_pattern" id="postcode_pattern" class="form-control"
                   value="{{ old('postcode_pattern', $allowedPostcode->postcode_pattern) }}" required pattern="\d{4}">
        </div>
        <button type="submit" class="btn btn-primary">@lang('allowed_postcodes.update')</button>
        <a href="{{ route('admin.allowed-postcodes.index') }}" class="btn btn-secondary">@lang('allowed_postcodes.back')</a>
    </form>
</div>
@endsection