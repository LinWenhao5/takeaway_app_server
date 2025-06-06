@props(['id' => 'theme-toggle'])

<div>
    <button id="{{ $id }}-btn" class="btn btn-outline-light d-flex align-items-center">
        <i id="{{ $id }}-icon" class="bi bi-moon me-2"></i>
        <span id="{{ $id }}-text">Light Mode</span>
    </button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('{{ $id }}-btn');
    const toggleIcon = document.getElementById('{{ $id }}-icon');
    const toggleText = document.getElementById('{{ $id }}-text');
    const logoutBtn = document.querySelector('.btn-outline-light');

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('bs-theme', theme);

        if (theme === 'dark') {
            toggleBtn.className = 'btn btn-outline-light d-flex align-items-center';
            toggleIcon.className = 'bi bi-sun me-2';
            toggleText.textContent = 'Light Mode';
        } else {
            toggleBtn.className = 'btn btn-dark d-flex align-items-center';
            toggleIcon.className = 'bi bi-moon me-2';
            toggleText.textContent = 'Dark Mode';
        }
    }

    let theme = localStorage.getItem('bs-theme') || 'light';
    applyTheme(theme);

    toggleBtn.addEventListener('click', function() {
        theme = theme === 'dark' ? 'light' : 'dark';
        applyTheme(theme);
    });
});
</script>
@endpush