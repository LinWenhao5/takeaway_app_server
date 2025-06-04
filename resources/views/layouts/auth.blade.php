<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('components.theme-handler')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
            <div class="card-header text-center">
                <h4 class="mb-0">@yield('header', 'Welcome')</h4>
            </div>
            <div class="card-body">
                @yield('content')
            </div>
            <div class="card-footer text-center">
                <small>© {{ date('Y') }} Zen Sushi</small>
            </div>
        </div>
    </div>
</body>
@stack('scripts')
</html>