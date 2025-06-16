document.addEventListener('DOMContentLoaded', () => {
    const userTheme = document.documentElement.getAttribute('data-bs-theme') || 'auto';

    if (userTheme === 'auto') {
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', systemTheme);
    } else {
        document.documentElement.setAttribute('data-bs-theme', userTheme);
    }

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (userTheme === 'auto') {
            const newTheme = e.matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
        }
    });
});