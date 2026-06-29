<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="{{ $_COOKIE['bs-theme'] ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Zen Sushi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: var(--bs-body-bg);
            min-height: 100vh;
        }
        [data-bs-theme="light"] body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e2e6ea 100%);
        }
        .alert {
            border-radius: 0.75rem !important;
            border: none;
        }
    </style>
</head>
<body class="bg-body-tertiary">
    <div class="position-fixed top-0 start-0 p-4">
        <a href="/" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="bi bi-house-door me-1"></i> @lang('auth.back_to_home')
        </a>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4 mt-4">
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success shadow-sm mb-4">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        @yield('content')
    </div>
</body>
</html>