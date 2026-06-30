@extends('layouts.auth')

@section('title', __('auth.login'))

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100" data-bs-theme="auto">
    <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
        
        <div class="text-center mb-4">
            <h2 class="fw-bold">Zen Sushi</h2>
            <p class="text-muted">@lang('auth.welcome_back')</p>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-body-tertiary">
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold mb-4 text-center">@lang('auth.login_title')</h4>
                
                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold">@lang('auth.email')</label>
                        <input type="email" name="email" id="email" 
                               class="form-control form-control-lg border-0 rounded-3"
                               placeholder="name@example.com" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label small fw-bold">@lang('auth.password')</label>
                        <input type="password" name="password" id="password" 
                               class="form-control form-control-lg border-0 rounded-3"
                               placeholder="••••••••" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold shadow-sm">
                            @lang('auth.login_button')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="small text-muted">&copy; {{ date('Y') }} Zen Sushi. All rights reserved.</p>
        </div>
    </div>
</div>
@endsection