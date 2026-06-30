<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="{{ $_COOKIE['bs-theme'] ?? 'light' }}">
<head>
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/180.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Zen Sushi">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@lang('welcome.title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body text-body">
    <div class="container py-5">
    <div class="d-flex justify-content-end mb-5">
        <div class="btn-group shadow-sm border border-secondary-subtle rounded">
            <a href="{{ route('set.locale', ['locale' => 'en']) }}" class="btn {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-body bg-body-tertiary' }}">EN</a>
            <a href="{{ route('set.locale', ['locale' => 'zh-cn']) }}" class="btn {{ app()->getLocale() === 'zh-cn' ? 'btn-primary' : 'btn-body bg-body-tertiary' }}">ZH</a>
        </div>
        <div class="btn-group shadow-sm border border-primary-subtle rounded ms-3">
                <x-logout-button class="btn btn-danger ms-3" />
        </div>
    </div>

    <div class="text-center mb-5">
        <h1 class="display-3 fw-bold text-body mb-3">@lang('welcome.title')</h1>
        <p class="lead text-body-secondary">@lang('welcome.manage')</p>
    </div>

    @php
        $menuItems = [
            ['route' => 'pos.terminal', 'icon' => 'bi-cart-check', 'color' => 'text-primary', 'title' => 'welcome.pos_terminal', 'desc' => 'welcome.pos_desc'],
            ['route' => 'admin.orders.index', 'icon' => 'bi-receipt', 'color' => 'text-success', 'title' => 'welcome.order_display', 'desc' => 'welcome.order_display_desc'],
             ['route' => 'admin.business-hours.index', 'icon' => 'bi-clock', 'color' => 'text-danger', 'title' => 'welcome.business_hours', 'desc' => 'welcome.business_hours_desc'],
            ['route' => 'admin.product-categories.index', 'icon' => 'bi-pie-chart', 'color' => 'text-secondary', 'title' => 'welcome.dashboard', 'desc' => 'welcome.dashboard_desc'],
            ['route' => 'admin.settings.index', 'icon' => 'bi-gear', 'color' => 'text-warning', 'title' => 'welcome.settings', 'desc' => 'welcome.settings_desc'],
        ];
    @endphp

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 justify-content-start">
        @foreach($menuItems as $item)
            <div class="col d-flex">
                <a href="{{ route($item['route']) }}" class="text-decoration-none w-100">
                    <div class="card hero-card border-0 shadow-sm h-100 p-4 bg-body-tertiary">
                        <div class="feature-icon mb-3 {{ $item['color'] }}">
                            <i class="bi {{ $item['icon'] }}"></i>
                        </div>
                        <h4 class="fw-bold text-body">@lang($item['title'])</h4>
                        <p class="text-body-secondary mb-0">@lang($item['desc'])</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>

<style>
    body {
        overscroll-behavior: none;
        touch-action: manipulation;
        -webkit-user-select: none;
        user-select: none;
    }

    ::-webkit-scrollbar {
        display: none;
    }

    .hero-card { transition: transform 0.2s; border-radius: 1.5rem !important; }
    .hero-card:hover { transform: translateY(-5px); }
    .feature-icon { font-size: 2rem; }
</style>