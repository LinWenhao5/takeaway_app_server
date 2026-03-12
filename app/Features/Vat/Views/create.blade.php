@extends('layouts.app')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.vat.index') }}">@lang('vat.vat_management')</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('vat.create')</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <h1 class="mb-4">@lang('vat.create')</h1>
    <form method="POST" action="{{ route('admin.vat.store') }}" class="card p-4">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ __('vat.name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('vat.rate') }}</label>
            <input type="number" name="rate" value="{{ old('rate') }}" step="0.01" class="form-control" required>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">{{ __('vat.create') }}</button>
            <a href="{{ route('admin.vat.index') }}" class="btn btn-secondary ms-2">@lang('vat.back')</a>
        </div>
    </form>
</div>
@endsection