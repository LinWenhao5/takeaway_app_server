@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.vat.index') }}">@lang('vat.vat_management')</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">@lang('vat.vat_management')</h1>
        <a href="{{ route('admin.vat.create') }}" class="btn btn-primary">@lang('vat.add_new')</a>
    </div>

    <x-table class="mt-4">
        <x-slot:head>
            <tr>
                <th>@lang('vat.name')</th>
                <th>@lang('vat.rate')</th>
                <th>@lang('vat.actions')</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @foreach ($vatRates as $vat)
            <tr>
                <td>{{ $vat->name }}</td>
                <td>{{ $vat->rate }}%</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a href="{{ route('admin.vat.edit', $vat) }}" class="btn btn-outline-primary btn-sm">@lang('vat.edit')</a>
                        <x-delete-confirm
                            :action="route('admin.vat.destroy', $vat)"
                            :title="__('vat.delete_title')"
                            :text="__('vat.delete_text', ['name' => $vat->name])"
                            :confirm-button-text="__('vat.confirm_delete')"
                            :success-message="__('vat.success_delete')"
                            :error-message="__('vat.error_delete')"
                            button-class="btn btn-outline-danger btn-sm"
                        >
                            <button type="button" class="btn btn-outline-danger btn-sm">@lang('vat.delete')</button>
                        </x-delete-confirm>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-slot:body>
        <x-slot:pagination>
            {{ $vatRates->links() }}
        </x-slot:pagination>
    </x-table>
</div>
@endsection