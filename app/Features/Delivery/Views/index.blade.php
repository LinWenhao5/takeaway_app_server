{{-- filepath: /Users/linwenhao/Projects/takeaway_app_server/app/Features/Delivery/Views/index.blade.php --}}
@extends('layouts.app')

@section('title', __('delivery.title'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.delivery.index') }}">@lang('navigation.delivery')</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">@lang('delivery.title')</h1>
    </div>

    <div class="list-group shadow-sm">
        <!-- Minimum Delivery Amount Setting -->
        <div class="list-group-item">
            <form action="{{ route('admin.delivery.updateMinimumAmount') }}" method="POST" class="w-100">
                @csrf
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <span class="text-muted">@lang('delivery.minimum_amount_label')</span>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" step="0.01" min="0" name="minimum_amount" id="minimum_amount"
                               class="form-control" style="max-width:120px"
                               value="{{ old('minimum_amount', $minimumAmount) }}" required>
                        <button type="submit" class="btn btn-primary btn-sm">@lang('delivery.save')</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Delivery Fee Setting -->
        <div class="list-group-item">
            <form action="{{ route('admin.delivery.updateFee') }}" method="POST" class="w-100">
                @csrf
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <span class="text-muted">@lang('delivery.fee_label')</span>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" step="0.01" min="0" name="fee" id="fee"
                               class="form-control" style="max-width:120px"
                               value="{{ old('fee', $fee) }}" required>
                        <button type="submit" class="btn btn-primary btn-sm">@lang('delivery.save')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection