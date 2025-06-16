<div class="language-switcher">
    <a href="{{ route('set.locale', ['locale' => 'en']) }}" 
       class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
        English
    </a>

    <a href="{{ route('set.locale', ['locale' => 'zh-cn']) }}" 
       class="btn btn-sm {{ app()->getLocale() === 'zh-cn' ? 'btn-primary' : 'btn-outline-primary' }}">
        简体中文
    </a>
</div>