<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="{{ $_COOKIE['bs-theme'] ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>POS Terminal - 收银系统</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="bg-body text-body overflow-hidden select-none">
    {{ $slot }}
    @livewireScripts
</body>
</html>

<style>

body {
    overscroll-behavior: none;
    touch-action: manipulation;
    -webkit-user-select: none;
    user-select: none;
}

::-webkit-scrollbar {
    display: none;
}

.btn-outline-secondary {
    transition: all 0.2s ease;
}

.btn-outline-secondary:hover, 
.btn-outline-secondary:active, 
.btn-outline-secondary.active {
    background-color: var(--bs-body-color) !important;
    color: var(--bs-body-bg) !important;
    border-color: var(--bs-body-color) !important;
}

.btn-outline-secondary:hover .text-body,
.btn-outline-secondary:hover .text-muted,
.btn-outline-secondary:active .text-body,
.btn-outline-secondary:active .text-muted,
.btn-outline-secondary.active .text-body,
.btn-outline-secondary.active .text-muted {
    color: var(--bs-body-bg) !important;
}


.list-group-item.active {
    background-color: var(--bs-body-color) !important;
    border-color: var(--bs-body-color) !important;
    color: var(--bs-body-bg) !important;
}

.btn-primary {
    background-color: var(--bs-body-color) !important;
    border-color: var(--bs-body-color) !important;
    color: var(--bs-body-bg) !important;
}

.btn-primary:hover,
.btn-primary:active,
.btn-primary:focus {
    background-color: var(--bs-gray-700) !important;
    border-color: var(--bs-gray-700) !important;
    color: var(--bs-body-bg) !important;
}
</style>