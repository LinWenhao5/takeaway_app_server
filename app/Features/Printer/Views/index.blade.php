@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.printers.index') }}">@lang('printer.title_index')</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">@lang('printer.title_index')</h1>
        <a href="{{ route('admin.printers.create') }}" class="btn btn-primary">@lang('printer.title_create')</a>
    </div>

    <x-table class="mt-4">
        <x-slot:head>
            <tr>
                <th>@lang('printer.name')</th>
                <th>@lang('printer.mac_address')</th>
                <th>@lang('printer.status')</th>
                <th>@lang('printer.actions')</th>
            </tr>
        </x-slot:head>
        
        <x-slot:body>
            @foreach ($printers as $printer)
            <tr>
                <td class="fw-bold">{{ $printer->name }}</td>
                <td><code>{{ $printer->mac_address }}</code></td>
                <td>
                    @if($printer->is_online)
                        <span class="badge bg-success">🟢 @lang('printer.online')</span>
                    @else
                        <span class="badge bg-danger">🔴 @lang('printer.offline')</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a href="{{ route('admin.printers.edit', $printer) }}" class="btn btn-outline-primary btn-sm">
                            @lang('printer.edit')
                        </a>
                        
                        <x-delete-confirm
                            :action="route('admin.printers.destroy', $printer)"
                            :title="__('printer.delete_title')"
                            :text="__('printer.delete_text', ['name' => $printer->name])"
                            :confirm-button-text="__('printer.confirm_delete')"
                            :success-message="__('printer.success_delete')"
                            :error-message="__('printer.error_delete')"
                            button-class="btn btn-outline-danger btn-sm"
                        >
                            <button type="button" class="btn btn-outline-danger btn-sm">@lang('printer.delete')</button>
                        </x-delete-confirm>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-slot:body>
        
        <x-slot:pagination>
            {{ $printers->links() }}
        </x-slot:pagination>
    </x-table>
</div>
@endsection