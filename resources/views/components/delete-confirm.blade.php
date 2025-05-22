@props([
    'action',
    'title' => 'Are you sure?',
    'text' => "You won't be able to revert this!",
    'confirmButtonText' => 'Yes, delete it!',
    'successMessage' => 'Deleted successfully!',
    'errorMessage' => 'An error occurred while deleting.',
    'buttonClass' => 'btn btn-danger btn-sm',
])

<form action="{{ $action }}" method="POST" class="d-inline delete-confirm-form">
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $buttonClass }}">
        {{ $slot->isEmpty() ? 'Delete' : $slot }}
    </button>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-confirm-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: @json($title),
                text: @json($text),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: @json($confirmButtonText),
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush