<nav
    class="offcanvas offcanvas-start d-lg-none"
    tabindex="-1"
    id="sidebarMenu"
    aria-labelledby="sidebarMenuLabel"
    style="width: 320px;"
>
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">@lang('navigation.admin_panel')</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-3">
        <ul class="nav nav-pills flex-column mb-auto">
            @can('manage_products')
            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam me-2"></i> @lang('navigation.products')
                </a>
            </li>
            @endcan

            @can('manage_products')
            <li>
                <a href="{{ route('admin.product-categories.index') }}" class="nav-link {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags me-2"></i> @lang('navigation.product_categories')
                </a>
            </li>
            @endcan

            @can('manage_users')
            <li>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> @lang('navigation.users')
                </a>
            </li>
            @endcan

            @can('manage_products')
            <li>
                <a href="{{ route('admin.media.library') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <i class="bi bi-images me-2"></i> @lang('navigation.media_library')
                </a>
            </li>
            @endcan

            @can('manage_orders')
            <li>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt me-2"></i> @lang('navigation.orders')
                </a>
            </li>
            @endcan
            
            @can('manage_shops')
            <li>
                <a href="{{ route('admin.allowed-postcodes.index') }}" class="nav-link {{ request()->routeIs('admin.allowed-postcodes.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt-fill me-2"></i> @lang('navigation.allowed_postcodes')
                </a>
            </li>
            @endcan

            <li>
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi bi-gear me-2"></i> @lang('navigation.settings')
                </a>
            </li>
            
            
            @can('view_horizon')
            <li>
                <a href="{{ url('/horizon') }}" class="nav-link {{ request()->is('horizon*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> @lang('navigation.horizon')
                </a>
            </li>
            @endcan
        </ul>

        <div class="d-flex align-items-center justify-content-between mt-3">
            <x-logout-button class="btn btn-danger ms-3" />
        </div>
    </div>
</nav>
