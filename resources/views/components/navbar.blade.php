<div id="navbar" class="navbar navbar-expand-lg bg-primary navbar-dark d-none d-lg-flex fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="{{ route('admin.products.index') }}">@lang('navigation.admin_panel')</a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Product Management -->
                @canany(['manage_products'])
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-seam"></i> @lang('navigation.product')
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="productDropdown">
                        @can('manage_products')
                            <li>
                                <a href="{{ route('admin.products.index') }}" class="dropdown-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                    @lang('navigation.products')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products.create') }}" class="dropdown-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                    @lang('navigation.create_product')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.product-categories.index') }}" class="dropdown-item {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                                    @lang('navigation.product_categories')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.media.library') }}" class="dropdown-item {{ request()->routeIs('admin.media.library') ? 'active' : '' }}">
                                    @lang('navigation.media_library')
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!-- User Management -->
                @canany(['manage_users'])
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-people"></i> @lang('navigation.user')
                    </a>
                    <ul class="dropdown-menu"aria-labelledby="userDropdown">
                        @can('manage_users')
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="dropdown-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    @lang('navigation.users')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invite.create') }}" class="dropdown-item {{ request()->routeIs('admin.invite.create') ? 'active' : '' }}">
                                    @lang('navigation.invite_user')
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!-- Shop Management -->
                @canany(['manage_shops'])
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="shopDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-shop"></i> @lang('navigation.shop_management')
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="shopDropdown">
                        @can('manage_shops')
                            <li>
                                <a href="{{ route('admin.allowed-postcodes.index') }}" class="dropdown-item {{ request()->routeIs('admin.allowed-postcodes.*') ? 'active' : '' }}">
                                    @lang('navigation.allowed_postcodes')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.business-hours.index') }}" class="dropdown-item {{ request()->routeIs('admin.business-hours.*') ? 'active' : '' }}">
                                    @lang('navigation.business_hours')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.delivery.index') }}" class="dropdown-item {{ request()->routeIs('admin.delivery.*') ? 'active' : '' }}">
                                    @lang('navigation.delivery')
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['manage_orders'])
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="orderDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-receipt"></i> @lang('navigation.order_management')
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="orderDropdown">
                        @can('manage_orders')
                            <li>
                                <a href="{{ route('admin.orders.index') }}" class="dropdown-item {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                    @lang('navigation.orders')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders.history') }}" class="dropdown-item {{ request()->routeIs('admin.orders.history') ? 'active' : '' }}">
                                    @lang('navigation.order_history')
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['manage_settings', 'view_horizon'])
                <!-- System Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="systemDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear"></i> @lang('navigation.system_management')
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="systemDropdown">
                        @can('manage_settings')
                        <li>
                            <a href="{{ route('admin.settings.index') }}" class="dropdown-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                @lang('navigation.settings')
                            </a>
                        </li>
                        @endcan
                        @can('view_horizon')
                        <li>
                            <a href="{{ url('/horizon') }}" class="dropdown-item {{ request()->is('horizon*') ? 'active' : '' }}">
                                @lang('navigation.horizon')
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany
            </ul>

            <!-- Logout Button -->
            <div class="d-flex align-items-center">
                <x-logout-button class="btn btn-danger ms-3" />
            </div>
        </div>
    </div>
</div>