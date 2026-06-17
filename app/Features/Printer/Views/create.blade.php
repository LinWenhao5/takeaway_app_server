@extends('layouts.app')

@section('title', __('printer.title_create'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.printers.index') }}">@lang('printer.title_index')</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('printer.create')</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="mb-0">@lang('printer.title_create')</h1>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('admin.printers.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">@lang('printer.name') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Kitchen Printer">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="mac_address" class="form-label fw-bold">@lang('printer.mac_address') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('mac_address') is-invalid @enderror" id="mac_address" name="mac_address" value="{{ old('mac_address') }}" required placeholder="e.g. AA:BB:CC:DD:EE:FF">
                    @error('mac_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch p-0 ps-5 fs-5">
                        <input class="form-check-input ms-n5" type="checkbox" role="switch" id="is_online" name="is_online" value="1" {{ old('is_online', true) ? 'checked' : '' }}>
                        <label class="form-check-input-label small text-dark fw-bold" for="is_online">@lang('printer.mark_as_online')</label>
                    </div>
                    <div class="form-text ps-1">@lang('printer.status_hint')</div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.printers.index') }}" class="btn btn-light border px-4">@lang('printer.cancel')</a>
                    <button type="submit" class="btn btn-primary px-4">@lang('printer.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection