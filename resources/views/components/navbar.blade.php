<div id="navbar" class="navbar navbar-expand-lg bg-primary navbar-dark d-none d-lg-flex">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="{{ route('admin.products.index') }}">Admin Panel</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @can('manage_products')
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                </li>
                @endcan

                @can('manage_products')
                <li class="nav-item">
                    <a href="{{ route('admin.product-categories.index') }}" class="nav-link text-white {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Product Categories
                    </a>
                </li>
                @endcan

                @can('manage_users')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
                @endcan

                @can('manage_products')
                <li class="nav-item">
                    <a href="{{ route('admin.media.library') }}" class="nav-link text-white {{ request()->routeIs('admin.media.library') ? 'active' : '' }}">
                        <i class="bi bi-images"></i> Media Library
                    </a>
                </li>
                @endcan

                @can('view_horizon')
                <li class="nav-item">
                    <a href="{{ url('/horizon') }}" class="nav-link text-white {{ request()->is('horizon*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Horizon
                    </a>
                </li>
                @endcan
            </ul>
            <div class="d-flex align-items-center">
                <x-toggle />

                <x-logout-button class="btn btn-danger ms-3" />
            </div>
        </div>
    </div>
</div>