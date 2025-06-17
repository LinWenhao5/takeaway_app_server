<div id="navbar" class="navbar navbar-expand-lg bg-primary navbar-dark d-none d-lg-flex">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="{{ route('admin.products.index') }}">@lang('navigation.admin_panel')</a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Product Management -->
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

                <!-- User Management -->
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

                <!-- System Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="systemDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear"></i> @lang('navigation.system_management')
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="systemDropdown">
                        <li>
                            <a href="{{ route('admin.settings.index') }}" class="dropdown-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                @lang('navigation.settings')
                            </a>
                        </li>
                        @can('view_horizon')
                            <li>
                                <a href="{{ url('/horizon') }}" class="dropdown-item {{ request()->is('horizon*') ? 'active' : '' }}">
                                    @lang('navigation.horizon')
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            </ul>

            <!-- Logout Button -->
            <div class="d-flex align-items-center">
                <x-logout-button class="btn btn-danger ms-3" />
            </div>
        </div>
    </div>
</div>