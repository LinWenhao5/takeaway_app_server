@props([
    'action',
    'title' => 'Are you sure?',
    'text' => "You won't be able to revert this!",
    'confirmButtonText' => 'Yes, delete it!',
    'successMessage' => 'Deleted successfully!',
    'errorMessage' => 'An error occurred while deleting.',
])

<form action="{{ $action }}" method="POST" class="d-inline delete-confirm-form">
    @csrf
    @method('DELETE')
    <span class="delete-confirm-trigger w-100" style="display:inline-block; cursor:pointer;">
        {{ $slot->isEmpty() ? 'Delete' : $slot }}
    </span>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-confirm-form').forEach(function(form) {
        const trigger = form.querySelector('.delete-confirm-trigger');
        if (trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();

                // 检测深色模式
                const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

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
    });
});
</script>
@endpush