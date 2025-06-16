<div id="navbar" class="navbar navbar-expand-lg bg-primary navbar-dark d-none d-lg-flex">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="{{ route('admin.products.index') }}">Admin Panel</a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Product Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-seam"></i> Product
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="productDropdown">
                        @can('manage_products')
                            <li>
                                <a href="{{ route('admin.products.index') }}" class="dropdown-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                    Products
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products.create') }}" class="dropdown-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                    Create Product
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.product-categories.index') }}" class="dropdown-item {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                                    Product Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.media.library') }}" class="dropdown-item {{ request()->routeIs('admin.media.library') ? 'active' : '' }}">
                                    Media Library
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <!-- User Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-people"></i> User
                    </a>
                    <ul class="dropdown-menu"aria-labelledby="userDropdown">
                        @can('manage_users')
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="dropdown-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invite.create') }}" class="dropdown-item {{ request()->routeIs('admin.invite.create') ? 'active' : '' }}">
                                    Invite User
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <!-- System Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="systemDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear"></i> System Management
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="systemDropdown">
                        <li>
                            <a href="{{ route('admin.settings.index') }}" class="dropdown-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                Settings
                            </a>
                        </li>
                        @can('view_horizon')
                            <li>
                                <a href="{{ url('/horizon') }}" class="dropdown-item {{ request()->is('horizon*') ? 'active' : '' }}">
                                    Horizon
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