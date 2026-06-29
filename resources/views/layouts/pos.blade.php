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
/* 1. 原有的基础交互逻辑 */
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

/* 确保内部文字在交互时跟随变色 */
.btn-outline-secondary:hover .text-body,
.btn-outline-secondary:hover .text-muted,
.btn-outline-secondary:active .text-body,
.btn-outline-secondary:active .text-muted,
.btn-outline-secondary.active .text-body,
.btn-outline-secondary.active .text-muted {
    color: var(--bs-body-bg) !important;
}

/* 2. 核心修复：彻底消灭蓝色 */

/* 修复左侧分类选中状态 (把蓝色换成黑/白) */
.list-group-item.active {
    background-color: var(--bs-body-color) !important;
    border-color: var(--bs-body-color) !important;
    color: var(--bs-body-bg) !important;
}

/* 修复右侧支付方式按钮 (原 btn-primary 的蓝色) */
.btn-primary {
    background-color: var(--bs-body-color) !important;
    border-color: var(--bs-body-color) !important;
    color: var(--bs-body-bg) !important;
}

/* 修复支付按钮被点击/选中后的状态 */
.btn-primary:hover,
.btn-primary:active,
.btn-primary:focus {
    background-color: var(--bs-gray-700) !important; /* 悬停时稍微变灰，增加质感 */
    border-color: var(--bs-gray-700) !important;
    color: var(--bs-body-bg) !important;
}
</style>