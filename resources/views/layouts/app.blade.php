<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #f8f9fa;
        }
        .sidebar {
            min-width: 200px;
            max-width: 220px;
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #495057;
            color: #fff;
        }
        .sidebar .navbar-brand {
            color: #fff;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                min-width: 100%;
                max-width: 100%;
                min-height: auto;
                position: fixed;
                z-index: 1040;
                left: 0;
                top: 0;
                height: 100vh;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-backdrop {
                display: block;
            }
        }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1039;
        }
        @media (min-width: 992px) {
            .sidebar-backdrop {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar backdrop for mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column p-3" id="sidebarMenu">
            <a class="navbar-brand mb-4" href="#">Admin Panel</a>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('media.library') }}" class="nav-link {{ request()->routeIs('media.library') ? 'active' : '' }}">
                        Media Library
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Main content -->
        <div class="flex-grow-1">
            <!-- Mobile toggle button -->
            <button class="btn btn-outline-secondary d-lg-none m-2" id="sidebarToggle">
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        if(sidebarToggle && sidebarMenu && sidebarBackdrop) {
            sidebarToggle.addEventListener('click', () => {
                sidebarMenu.classList.toggle('show');
                sidebarBackdrop.style.display = sidebarMenu.classList.contains('show') ? 'block' : 'none';
            });
            sidebarBackdrop.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarBackdrop.style.display = 'none';
            });
        }
    </script>
</body>
</html>