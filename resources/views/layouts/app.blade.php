<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ isset($_COOKIE['bs-theme']) ? $_COOKIE['bs-theme'] : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex flex-column">
        <!-- Navbar for large screens -->
        <x-navbar />

        <!-- Sidebar for small screens -->
        <x-sidebar />


        <nav class="navbar navbar-expand-lg bg-primary navbar-dark d-lg-none shadow-sm fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-house-door-fill me-2"></i> @lang('navigation.admin_panel')
                </a>
                <button class="btn border-0 p-0"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    title="Toggle Sidebar">
                    <i class="bi bi-list text-white" style="font-size: 1.5rem;"></i>
                </button>
            </div>
        </nav>

        <div class="container-fluid px-0" style="height: 100vh;">
            <div class="d-flex flex-row h-100" style="padding-top: 60px;">
                <div class="flex-grow-1">
                    <main class="py-4 h-100">
                        <div class="container h-100">
                            @yield('breadcrumb')
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mx-auto" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @yield('content')
                        </div>
                    </main>
                </div>
                @hasSection('right-sidebar')
                    <div class="d-none d-lg-block bg-body-tertiary p-3" style="width:260px;min-width:200px;max-width:320px;height:100%;">
                        @yield('right-sidebar')
                    </div>
                @endif
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>