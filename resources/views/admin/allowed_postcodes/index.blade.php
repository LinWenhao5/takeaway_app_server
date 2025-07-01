@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.allowed-postcodes.index') }}">@lang('allowed_postcodes.breadcrumb')</a>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">@lang('allowed_postcodes.management')</h1>
        <a href="{{ route('admin.allowed-postcodes.create') }}" class="btn btn-primary">@lang('allowed_postcodes.add_new')</a>
    </div>

    <div class="row">
        @foreach($postcodes as $postcode)
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title text-center">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                            {{ $postcode->postcode_pattern }}
                        </h5>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a href="{{ route('admin.allowed-postcodes.edit', $postcode) }}" class="btn btn-outline-primary btn-sm">
                                @lang('allowed_postcodes.edit')
                            </a>
                            <x-delete-confirm
                                :action="route('admin.allowed-postcodes.destroy', $postcode)"
                                title="{{ __('allowed_postcodes.delete_title') }}"
                                text="{{ __('allowed_postcodes.delete_text', ['postcode' => $postcode->postcode_pattern]) }}"
                                confirm-button-text="{{ __('allowed_postcodes.delete_confirm') }}"
                                success-message="{{ __('allowed_postcodes.delete_success') }}"
                                error-message="{{ __('allowed_postcodes.delete_failed') }}"
                                button-class="btn btn-outline-danger btn-sm"
                            >
                                <button type="button" class="btn btn-outline-danger btn-sm">
                                    @lang('allowed_postcodes.delete')
                                </button>
                            </x-delete-confirm>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection