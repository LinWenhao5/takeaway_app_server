<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="{{ $_COOKIE['bs-theme'] ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('welcome.title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-card { transition: transform 0.2s; border-radius: 1.5rem !important; }
        .hero-card:hover { transform: translateY(-5px); }
        .feature-icon { font-size: 2rem; }
    </style>
</head>
<body class="bg-body text-body">

    <div class="container py-5">
        <div class="d-flex justify-content-end mb-5">
            <div class="btn-group shadow-sm border border-secondary-subtle rounded">
                <a href="{{ route('set.locale', ['locale' => 'en']) }}" class="btn {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-body bg-body-tertiary' }}">EN</a>
                <a href="{{ route('set.locale', ['locale' => 'zh-cn']) }}" class="btn {{ app()->getLocale() === 'zh-cn' ? 'btn-primary' : 'btn-body bg-body-tertiary' }}">ZH</a>
            </div>
        </div>
        <div class="text-center mb-5">
            <h1 class="display-3 fw-bold text-body mb-3">@lang('welcome.title')</h1>
            <p class="lead text-body-secondary">@lang('welcome.manage')</p>
        </div>

        <div class="row justify-content-center g-4">
            
            <!-- POS Terminal -->
            <div class="col-md-3">
                <a href="{{ route('pos.terminal') }}" class="text-decoration-none">
                    <div class="card hero-card border-0 shadow-sm h-100 p-4 bg-body-tertiary">
                        <div class="feature-icon mb-3"><i class="bi bi-cart-check"></i></div>
                        <h4 class="fw-bold">@lang('welcome.pos_terminal')</h4>
                        <p class="opacity-75">@lang('welcome.pos_desc')</p>
                    </div>
                </a>
            </div>

            <!-- order Display -->
            <div class="col-md-3">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="card hero-card border-0 shadow-sm h-100 p-4 bg-body-tertiary">
                        <div class="feature-icon mb-3 text-success"><i class="bi bi-receipt"></i></div>
                        <h4 class="fw-bold text-body">@lang('welcome.order_display')</h4>
                        <p class="text-body-secondary">@lang('welcome.order_display_desc')</p>
                    </div>
                </a>
            </div>

            <!-- Dashboard -->
            <div class="col-md-3">
                <a href="{{ route('admin.product-categories.index') }}" class="text-decoration-none">
                    <div class="card hero-card border-0 shadow-sm h-100 p-4 bg-body-tertiary">
                        <div class="feature-icon mb-3 text-primary"><i class="bi bi-pie-chart"></i></div>
                        <h4 class="fw-bold text-body">@lang('welcome.dashboard')</h4>
                        <p class="text-body-secondary">@lang('welcome.dashboard_desc')</p>
                    </div>
                </a>
            </div>

            <!-- API Docs -->
            <div class="col-md-3">
                <a href="/api/documentation" class="text-decoration-none">
                    <div class="card hero-card border-0 shadow-sm h-100 p-4 bg-body-tertiary">
                        <div class="feature-icon mb-3 text-secondary"><i class="bi bi-code-square"></i></div>
                        <h4 class="fw-bold text-body">@lang('welcome.api_doc')</h4>
                        <p class="text-body-secondary">@lang('welcome.api_desc')</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

</body>
</html>