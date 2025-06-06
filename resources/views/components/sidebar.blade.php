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
            @can('manage products')
            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam me-2"></i> Products
                </a>
            </li>
            @endcan

            @can('manage products')
            <li>
                <a href="{{ route('admin.product-categories.index') }}" class="nav-link {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags me-2"></i> Product Categories
                </a>
            </li>
            @endcan

            @can('manage users')
            <li>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Users
                </a>
            </li>
            @endcan

            @can('manage products')
            <li>
                <a href="{{ route('admin.media.library') }}" class="nav-link {{ request()->routeIs('admin.media.library') ? 'active' : '' }}">
                    <i class="bi bi-images me-2"></i> Media Library
                </a>
            </li>
            @endcan
        </ul>

        <div class="d-flex align-items-center justify-content-between mt-3">
            <x-toggle id="sidebar-theme-toggle" />
            <x-logout-button class="btn btn-danger ms-3" />
        </div>
    </div>
</nav>
