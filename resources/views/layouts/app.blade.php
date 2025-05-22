<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex">
        <nav
            class="offcanvas offcanvas-start"
            tabindex="-1"
            id="sidebarMenu"
            aria-labelledby="sidebarMenuLabel"
            style="width: 320px;"
        >
            
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarMenuLabel">Admin Panel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column p-3">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.product-categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            Product Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.media.library') }}" class="nav-link {{ request()->routeIs('media.library') ? 'active' : '' }}">
                            Media Library
                        </a>
                    </li>
                </ul>
                <theme-toggle></theme-toggle>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                @php
                    $mainCol = View::hasSection('right-sidebar') ? 'col-lg-10' : 'col-lg-8 mx-auto';
                @endphp
                <div class="{{ $mainCol }}">
                    <button class="btn btn-outline-secondary m-2"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu">
                        ☰ Menu
                    </button>
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
                @hasSection('right-sidebar')
                    <div class="col-lg-2 d-none d-lg-block border-start bg-body-tertiary px-0">
                        <div class="position-sticky top-0" style="min-height: 100vh;">
                            <div class="p-3">
                                @yield('right-sidebar')
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>