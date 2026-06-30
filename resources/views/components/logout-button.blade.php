<div class="d-flex align-items-center">
    @auth
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle d-flex align-items-center" 
                    type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2"></i>
                <span class="fw-semibold">{{ auth()->user()->name }}</span>
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userMenu">
                <li>
                    <form id="logout-form" method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="button" class="dropdown-item text-danger d-flex align-items-center" id="logout-button">
                            <i class="bi bi-box-arrow-right me-2"></i> @lang('navigation.logout')
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-box-arrow-in-right me-2"></i> @lang('navigation.login')
        </a>
    @endauth
</div>

@auth
    <x-sweet-alert 
        formId="logout-form" 
        :title="__('auth.logout_title')"
        :text="__('auth.logout_message')" 
        icon="warning" 
        :successMessage="__('auth.logout_success')" 
        :errorMessage="__('auth.logout_error')"
    />
@endauth