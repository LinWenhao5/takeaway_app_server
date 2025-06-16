<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('welcome.title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="text-center">
            <x-language-switcher />
            <div class="mb-4"></div> 
            <h1 class="display-4 text-primary">{{ __('welcome.title') }}</h1>
            <p class="lead text-secondary">{{ __('welcome.manage') }}</p>
            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-box-seam me-2"></i> {{ __('welcome.dashboard') }}
            </a>

            <a href="/api/documentation" class="btn btn-outline-secondary btn-lg mt-3">
                <i class="bi bi-file-earmark-code me-2"></i> {{ __('welcome.api_doc') }}
            </a>
        </div>
    </div>
</body>
</html>