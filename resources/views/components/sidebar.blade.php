<nav
    class="offcanvas offcanvas-start d-lg-none"
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
            @can('manage_products')
            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam me-2"></i> Products
                </a>
            </li>
            @endcan

            @can('manage_products')
            <li>
                <a href="{{ route('admin.product-categories.index') }}" class="nav-link {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags me-2"></i> Product Categories
                </a>
            </li>
            @endcan

            @can('manage_users')
            <li>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Users
                </a>
            </li>
            @endcan

            @can('manage_products')
            <li>
                <a href="{{ route('admin.media.library') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <i class="bi bi-images me-2"></i> Media Library
                </a>
            </li>
            @endcan

            <li>
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi bi-gear me-2"></i> Settings
                </a>
            </li>

            @can('view_horizon')
            <li>
                <a href="{{ url('/horizon') }}" class="nav-link {{ request()->is('horizon*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Horizon
                </a>
            </li>
            @endcan
        </ul>

        <div class="d-flex align-items-center justify-content-between mt-3">
            <x-logout-button class="btn btn-danger ms-3" />
        </div>
    </div>
</nav>
