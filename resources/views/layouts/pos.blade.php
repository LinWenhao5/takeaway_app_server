<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="{{ $_COOKIE['bs-theme'] ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    background-color: var(--bs-gray-700) !important; /* 悬停时稍微变灰，增加质感 */
    border-color: var(--bs-gray-700) !important;
    color: var(--bs-body-bg) !important;
}
</style>