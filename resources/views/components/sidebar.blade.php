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
        <div class="accordion" id="sidebarAccordion">
            <!-- 产品管理分组 -->
            @can('manage_products')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingProduct">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProduct" aria-expanded="false" aria-controls="collapseProduct">
                        <i class="bi bi-box-seam me-2"></i> @lang('navigation.product')
                    </button>
                </h2>
                <div id="collapseProduct" class="accordion-collapse collapse" aria-labelledby="headingProduct" data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body p-0">
                        <ul class="nav flex-column">
                            <li>
                                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                    @lang('navigation.products')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.product-categories.index') }}" class="nav-link {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                                    @lang('navigation.product_categories')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.media.library') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                                    @lang('navigation.media_library')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endcan
            <!-- 用户管理分组 -->
            @can('manage_users')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingUser">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                        <i class="bi bi-people me-2"></i> @lang('navigation.user')
                    </button>
                </h2>
                <div id="collapseUser" class="accordion-collapse collapse" aria-labelledby="headingUser" data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body p-0">
                        <ul class="nav flex-column">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    @lang('navigation.users')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endcan
            <!-- 店铺管理分组 -->
            @can('manage_shops')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingShop">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShop" aria-expanded="false" aria-controls="collapseShop">
                        <i class="bi bi-shop me-2"></i> @lang('navigation.shop_management')
                    </button>
                </h2>
                <div id="collapseShop" class="accordion-collapse collapse" aria-labelledby="headingShop" data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body p-0">
                        <ul class="nav flex-column">
                            <li>
                                <a href="{{ route('admin.allowed-postcodes.index') }}" class="nav-link {{ request()->routeIs('admin.allowed-postcodes.*') ? 'active' : '' }}">
                                    @lang('navigation.allowed_postcodes')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.business-hours.index') }}" class="nav-link {{ request()->routeIs('admin.business-hours.*') ? 'active' : '' }}">
                                    @lang('navigation.business_hours')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.delivery.index') }}" class="nav-link {{ request()->routeIs('admin.delivery.*') ? 'active' : '' }}">
                                    @lang('navigation.delivery')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endcan
            <!-- 订单管理分组 -->
            @can('manage_orders')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOrder">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrder" aria-expanded="false" aria-controls="collapseOrder">
                        <i class="bi bi-receipt me-2"></i> @lang('navigation.order_management')
                    </button>
                </h2>
                <div id="collapseOrder" class="accordion-collapse collapse" aria-labelledby="headingOrder" data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body p-0">
                        <ul class="nav flex-column">
                            <li>
                                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                    @lang('navigation.orders')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endcan
            <!-- 系统管理分组 -->
            @canany(['manage_settings', 'view_horizon'])
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSystem">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSystem" aria-expanded="false" aria-controls="collapseSystem">
                        <i class="bi bi-gear me-2"></i> @lang('navigation.system_management')
                    </button>
                </h2>
                <div id="collapseSystem" class="accordion-collapse collapse" aria-labelledby="headingSystem" data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body p-0">
                        <ul class="nav flex-column">
                            @can('manage_settings')
                            <li>
                                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                    @lang('navigation.settings')
                                </a>
                            </li>
                            @endcan
                            @can('view_horizon')
                            <li>
                                <a href="{{ url('/horizon') }}" class="nav-link {{ request()->is('horizon*') ? 'active' : '' }}">
                                    @lang('navigation.horizon')
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
            @endcanany
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3">
            <x-logout-button class="btn btn-danger ms-3" />
        </div>
    </div>
</nav>

<style>
    .accordion-item {
        border: none;
        box-shadow: none;
    }
    .accordion-body {
        border: none;
        padding-left: 0;
        padding-right: 0;
    }
    .accordion-button {
        box-shadow: none;
    }
</style>
