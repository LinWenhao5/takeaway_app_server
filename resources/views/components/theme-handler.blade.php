@push('scripts')
<script>
(function() {
    const storedTheme = localStorage.getItem('bs-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    const theme = storedTheme || (systemPrefersDark ? 'dark' : 'light');
    applyTheme(theme);

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('bs-theme', theme);
    }

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(event) {
        const newTheme = event.matches ? 'dark' : 'light';
        applyTheme(newTheme);
    });
})();
</script>
@endpush