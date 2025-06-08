@props(['class' => 'btn btn-outline-light'])

@php
    $formId = 'logout-form-' . uniqid();
@endphp

<form id="{{ $formId }}" method="POST" action="{{ route('logout') }}" class="d-inline">
    @csrf
    <button type="button" class="btn btn-danger ms-3" id="logout-button" title="Logout">
        <i class="bi bi-box-arrow-right"></i> Logout
    </button>
</form>

<x-sweet-alert 
    :formId="$formId" 
    title="Are you sure?" 
    text="You will be logged out!" 
    icon="warning" 
    confirmButtonText="Yes, logout!" 
    successMessage="Logged out successfully!" 
    errorMessage="Failed to logout."
/>