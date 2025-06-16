<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ $_COOKIE['bs-theme'] }}"">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex flex-column">
        <!-- Navbar for large screens -->
        <x-navbar />

        <!-- Sidebar for small screens -->
        <x-sidebar />


        <nav class="navbar navbar-expand-lg bg-primary navbar-dark d-lg-none shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-house-door-fill me-2"></i> Admin Panel
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

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <main class="py-4">
                        <div class="container">
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
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>