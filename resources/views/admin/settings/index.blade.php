@extends('layouts.app')

@section('title', __('settings.title'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">@lang('settings.breadcrumb')</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">@lang('settings.title')</h1>
    </div>

    <!-- Settings List -->
    <div class="list-group shadow-sm">
        <!-- Language Settings -->
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">@lang('settings.language')</span>
                <div class="btn-group">
                    <a href="{{ route('set.locale', ['locale' => 'en']) }}" 
                       class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
                        @lang('settings.language_english')
                    </a>
                    <a href="{{ route('set.locale', ['locale' => 'zh-cn']) }}" 
                       class="btn btn-sm {{ app()->getLocale() === 'zh-cn' ? 'btn-primary' : 'btn-outline-primary' }}">
                        @lang('settings.language_chinese')
                    </a>
                </div>
            </div>
        </div>

        <!-- Theme Settings -->
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">@lang('settings.theme')</span>
                <div class="btn-group">
                    <a href="{{ route('set.theme', ['theme' => 'light']) }}" 
                       class="btn btn-sm {{ (isset($_COOKIE['bs-theme']) && $_COOKIE['bs-theme'] === 'light') ? 'btn-primary' : 'btn-outline-primary' }}">
                        @lang('settings.theme_light')
                    </a>
                    <a href="{{ route('set.theme', ['theme' => 'dark']) }}" 
                       class="btn btn-sm {{ (isset($_COOKIE['bs-theme']) && $_COOKIE['bs-theme'] === 'dark') ? 'btn-primary' : 'btn-outline-primary' }}">
                        @lang('settings.theme_dark')
                    </a>
                    <a href="{{ route('set.theme', ['theme' => 'auto']) }}" 
                       class="btn btn-sm {{ (isset($_COOKIE['bs-theme']) && $_COOKIE['bs-theme'] === 'auto') ? 'btn-primary' : 'btn-outline-primary' }}">
                        @lang('settings.theme_auto')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection