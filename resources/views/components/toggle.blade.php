<div>
    <button id="theme-toggle-btn" class="btn btn-sm btn-dark w-100">
        Dark Mode
    </button>
</div>

@push('scripts')
<script>
(function() {
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('bs-theme', theme);
        document.getElementById('theme-toggle-btn').className =
            theme === 'dark'
                ? 'btn btn-sm btn-light w-100'
                : 'btn btn-sm btn-dark w-100';
        document.getElementById('theme-toggle-btn').innerText =
            theme === 'dark' ? 'Light Mode' : 'Dark Mode';
    }

    let theme = localStorage.getItem('bs-theme') || 'light';
    applyTheme(theme);

    document.getElementById('theme-toggle-btn').addEventListener('click', function() {
        theme = theme === 'dark' ? 'light' : 'dark';
        applyTheme(theme);
    });
})();
</script>
@endpush