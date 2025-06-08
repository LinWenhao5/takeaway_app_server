@props([
    'action',
    'title' => 'Are you sure?',
    'text' => "You won't be able to revert this!",
    'confirmButtonText' => 'Yes, delete it!',
    'successMessage' => 'Deleted successfully!',
    'errorMessage' => 'An error occurred while deleting.',
])

@php
    $formId = 'delete-form-' . uniqid();
@endphp

<form id="{{ $formId }}" action="{{ $action }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <span class="trigger w-100" style="display:inline-block; cursor:pointer;">
        {{ $slot->isEmpty() ? 'Delete' : $slot }}
    </span>
</form>

<x-sweet-alert 
    :formId="$formId" 
    :title="$title" 
    :text="$text" 
    :confirmButtonText="$confirmButtonText" 
    :successMessage="$successMessage" 
    :errorMessage="$errorMessage"
/>