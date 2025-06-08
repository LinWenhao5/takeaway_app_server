@props(['class' => 'btn btn-outline-light'])

<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-inline">
    @csrf
    <button type="button" class="{{ $class }}" id="logout-button" title="Logout">
        <i class="bi bi-box-arrow-right"></i> Logout
    </button>
</form>

<x-sweet-alert 
    formId="logout-form" 
    title="Are you sure?" 
    text="You will be logged out!" 
    confirmButtonText="Yes, logout!" 
    successMessage="Logged out successfully!" 
    errorMessage="Failed to logout."
/>