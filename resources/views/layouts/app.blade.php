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
                        <a href="{{ route('media.library') }}" class="nav-link {{ request()->routeIs('media.library') ? 'active' : '' }}">
                            Media Library
                        </a>
                    </li>
                </ul>
                <theme-toggle></theme-toggle>
            </div>
        </nav>

        <div class="flex-grow-1">
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
    </div>
</body>
</html>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('themeToggleBtn');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('bs-theme');
    if (savedTheme) html.setAttribute('data-bs-theme', savedTheme);

    function updateBtn() {
        const current = html.getAttribute('data-bs-theme');
        btn.classList.toggle('btn-light', current === 'dark');
        btn.classList.toggle('btn-dark', current === 'light');
        btn.textContent = current === 'dark' ? 'Light Mode' : 'Dark Mode';
    }

    btn.addEventListener('click', function () {
        const current = html.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('bs-theme', next);
        updateBtn();
    });

    updateBtn();
});
</script>