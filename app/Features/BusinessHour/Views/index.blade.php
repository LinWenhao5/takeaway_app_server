@extends('layouts.app')

@section('title', __('business_hours.business_hours_management'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.business-hours.index') }}">{{ __('navigation.business_hours') }}</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ __('business_hours.business_hours_management') }}</h1>
    </div>

    <x-table class="mt-4">
        <x-slot:head>
            <tr>
                <th>{{ __('business_hours.weekday') }}</th>
                <th>{{ __('business_hours.open_time') }}</th>
                <th>{{ __('business_hours.close_time') }}</th>
                <th>{{ __('business_hours.is_closed') }}</th>
                <th>{{ __('business_hours.action') }}</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @php
                $weekdays = [
                    __('business_hours.sunday'),
                    __('business_hours.monday'),
                    __('business_hours.tuesday'),
                    __('business_hours.wednesday'),
                    __('business_hours.thursday'),
                    __('business_hours.friday'),
                    __('business_hours.saturday'),
                ];
            @endphp
            @foreach($businessHours as $hour)
            <tr>
                <td>{{ $weekdays[$hour->weekday] }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.business-hours.update-time', $hour->id) }}" id="form-time-{{ $hour->id }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="time"
                               name="open_time"
                               value="{{ substr($hour->open_time, 0, 5) }}"
                               class="form-control {{ $hour->is_closed ? 'bg-body-tertiary' : '' }}"
                               {{ $hour->is_closed ? 'disabled' : '' }}
                               style="width: 120px; display: inline-block;"
                               {{ $hour->is_closed ? 'tabindex=-1' : '' }}>
                </td>
                <td>
                        <input type="time"
                               name="close_time"
                               value="{{ substr($hour->close_time, 0, 5) }}"
                               class="form-control {{ $hour->is_closed ? 'bg-body-tertiary' : '' }}"
                               {{ $hour->is_closed ? 'disabled' : '' }}
                               style="width: 120px; display: inline-block;"
                               {{ $hour->is_closed ? 'tabindex=-1' : '' }}>
                </td>
                <td class="text-center">
                    </form>
                    <form method="POST" action="{{ route('admin.business-hours.update-closed', $hour->id) }}" id="form-closed-{{ $hour->id }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_closed" value="0">
                        <input type="checkbox" name="is_closed" value="1"
                            {{ $hour->is_closed ? 'checked' : '' }}
                            onchange="this.form.submit();">
                    </form>
                </td>
                <td>
                    <button type="submit" form="form-time-{{ $hour->id }}" class="btn btn-primary btn-sm" {{ $hour->is_closed ? 'disabled' : '' }}>
                        {{ __('business_hours.save') }}
                    </button>
                </td>
            </tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>
@endsection