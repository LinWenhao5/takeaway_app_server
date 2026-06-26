@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">@lang('coupon.coupon_management')</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('coupon.add_new')</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-body py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-body">@lang('coupon.create_coupon')</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">@lang('coupon.name') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="code" class="form-label fw-semibold">@lang('coupon.code') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="e.g., SUMMER20" required>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">@lang('coupon.type') <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>@lang('coupon.type_fixed') (€)</option>
                            <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>@lang('coupon.type_percent') (%)</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="value" class="form-label fw-semibold">@lang('coupon.value') <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" required>
                        @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="min_order_amount" class="form-label fw-semibold">@lang('coupon.min_order_amount') (€)</label>
                        <input type="number" step="0.01" class="form-control @error('min_order_amount') is-invalid @enderror" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', '0.00') }}">
                        @error('min_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="total_quantity" class="form-label fw-semibold">@lang('coupon.total_quantity')</label>
                        <input type="number" class="form-control @error('total_quantity') is-invalid @enderror" id="total_quantity" name="total_quantity" value="{{ old('total_quantity') }}" placeholder="@lang('coupon.infinity_placeholder')">
                        @error('total_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="per_customer_limit" class="form-label fw-semibold">@lang('coupon.per_customer_limit')</label>
                        <input type="number" class="form-control @error('per_customer_limit') is-invalid @enderror" id="per_customer_limit" name="per_customer_limit" value="{{ old('per_customer_limit') }}" placeholder="@lang('coupon.limit_placeholder')">
                        @error('per_customer_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pickup_start_at" class="form-label fw-semibold">@lang('coupon.pickup_start_at')</label>
                        <input type="date" class="form-control @error('pickup_start_at') is-invalid @enderror" id="pickup_start_at" name="pickup_start_at" value="{{ old('pickup_start_at') }}">
                        @error('pickup_start_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pickup_end_at" class="form-label fw-semibold">@lang('coupon.pickup_end_at')</label>
                        <input type="date" class="form-control @error('pickup_end_at') is-invalid @enderror" id="pickup_end_at" name="pickup_end_at" value="{{ old('pickup_end_at') }}">
                        @error('pickup_end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <hr class="text-muted my-2">
                        <div class="alert alert-light border-start border-3 border-info py-2 px-3 mb-0">
                            <small class="text-muted d-block">💡 <strong>@lang('coupon.validity_type')：</strong>@lang('coupon.valid_days_tips')</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="valid_days" class="form-label fw-semibold text-primary">@lang('coupon.valid_days')</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('valid_days') is-invalid @enderror" id="valid_days" name="valid_days" value="{{ old('valid_days') }}" placeholder="@lang('coupon.valid_days_placeholder')">
                            <span class="input-group-text">@lang('coupon.days_unit')</span>
                        </div>
                        @error('valid_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="use_start_at" class="form-label fw-semibold">@lang('coupon.use_start_at')</label>
                        <input type="date" class="form-control @error('use_start_at') is-invalid @enderror" id="use_start_at" name="use_start_at" value="{{ old('use_start_at') }}">
                        @error('use_start_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="use_end_at" class="form-label fw-semibold">@lang('coupon.use_end_at')</label>
                        <input type="date" class="form-control @error('use_end_at') is-invalid @enderror" id="use_end_at" name="use_end_at" value="{{ old('use_end_at') }}">
                        @error('use_end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <hr class="text-muted my-2">
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">@lang('coupon.status_active')</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">@lang('coupon.cancel')</a>
                    <button type="submit" class="btn btn-primary">@lang('coupon.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection