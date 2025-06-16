<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="{{ isset($_COOKIE['bs-theme']) ? $_COOKIE['bs-theme'] : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('welcome.title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="text-center">
            <div class="language-switcher">
                <a href="{{ route('set.locale', ['locale' => 'en']) }}" 
                class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
                    @lang('settings.language_english')
                </a>

                <a href="{{ route('set.locale', ['locale' => 'zh-cn']) }}" 
                class="btn btn-sm {{ app()->getLocale() === 'zh-cn' ? 'btn-primary' : 'btn-outline-primary' }}">
                    @lang('settings.language_chinese')
                </a>
            </div>
            <div class="mb-4"></div> 
            <h1 class="display-4 text-primary">@lang('welcome.title')</h1>
            <p class="lead text">@lang('welcome.manage')</p>
            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-box-seam me-2"></i> @lang('welcome.dashboard')
            </a>

            <a href="/api/documentation" class="btn btn-outline-secondary btn-lg mt-3">
                <i class="bi bi-file-earmark-code me-2"></i> @lang('welcome.api_doc')
            </a>
        </div>
    </div>
</body>
</html>