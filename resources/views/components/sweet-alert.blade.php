<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById(@json($formId));
    if (form) {
        const trigger = form.querySelector('button[type="button"], .trigger');
        if (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();

                const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

                Swal.fire({
                    title: @json($title),
                    text: @json($text),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: @json($confirmButtonText),
                    background: isDark ? '#23272b' : '#fff',
                    color: isDark ? '#fff' : '#23272b',
                    iconColor: isDark ? '#ffc107' : '#f8bb86',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    }
});
</script>