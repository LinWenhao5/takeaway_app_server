@extends('layouts.app')

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
                    <input type="time"
                           name="open_time"
                           value="{{ substr($hour->open_time, 0, 5) }}"
                           class="form-control {{ $hour->is_closed ? 'bg-light' : '' }}"
                           {{ $hour->is_closed ? 'readonly' : '' }}
                           form="form-{{ $hour->id }}">
                </td>
                <td>
                    <input type="time"
                           name="close_time"
                           value="{{ substr($hour->close_time, 0, 5) }}"
                           class="form-control {{ $hour->is_closed ? 'bg-light' : '' }}"
                           {{ $hour->is_closed ? 'readonly' : '' }}
                           form="form-{{ $hour->id }}">
                </td>
                <td class="text-center">
                    <input type="hidden" name="is_closed" value="0" form="form-{{ $hour->id }}">
                    <input type="checkbox" name="is_closed" value="1" {{ $hour->is_closed ? 'checked' : '' }} onchange="document.getElementById('form-{{ $hour->id }}').submit();" form="form-{{ $hour->id }}">
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.business-hours.update', $hour->id) }}" id="form-{{ $hour->id }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('business_hours.save') }}</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>
@endsection