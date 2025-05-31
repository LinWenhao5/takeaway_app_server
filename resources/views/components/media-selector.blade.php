@props([
    'media' => [],
    'selected' => [],
    'name' => 'media',
    'label' => 'Media',
    'multiple' => false
])

<div>
    <div class="d-flex align-items-center mb-2">
        <label for="media" class="form-label mb-0 me-2">{{ $label }}</label>
        <a href="{{ route('admin.media.library') }}" target="_blank" class="btn btn-sm btn-outline-primary">
            Upload Media
        </a>
    </div>
    <div class="row">
        @foreach ($media as $item)
            <div class="col-md-3 mb-3">
                <div class="card media-card {{ (is_array($selected) && in_array($item->id, $selected)) || (!is_array($selected) && $selected == $item->id) ? 'border-primary' : '' }}" data-id="{{ $item->id }}">
                    <img src="{{ $item->path }}" class="card-img-top" alt="{{ $item->name }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body text-center">
                        <p class="card-text">{{ $item->name }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div id="media-inputs-{{ $name }}"></div>
</div>

@push('scripts')
<script>
// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function () {
    // All media card elements
    const mediaCards = document.querySelectorAll('.media-card');
    // Whether multi-select is enabled
    const multiple = @json($multiple);
    // The container for hidden input(s)
    const inputsContainer = document.getElementById('media-inputs-{{ $name }}');
    // Array of selected media IDs
    let selectedMedia = [];

    // Initialize selected media from backend
    @if($multiple)
        selectedMedia = {!! json_encode(is_array($selected) ? $selected : (empty($selected) ? [] : [$selected])) !!};
    @else
        selectedMedia = ["{{ is_array($selected) ? (count($selected) ? $selected[0] : '') : $selected }}"].filter(Boolean);
    @endif

    selectedMedia = selectedMedia.map(String);
    
    // Update hidden input(s) based on current selection
    function updateInputs() {
        inputsContainer.innerHTML = '';
        if (multiple) {
            selectedMedia.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '{{ $name }}[]';
                input.value = id;
                inputsContainer.appendChild(input);
            });
        } else {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '{{ $name }}';
            input.id = 'selected-media-{{ $name }}';
            input.value = selectedMedia[0] || '';
            inputsContainer.appendChild(input);
        }
    }

    // Handle click event for each media card
    mediaCards.forEach(card => {
        card.addEventListener('click', function () {
            const mediaId = String(card.getAttribute('data-id'));
            if (multiple) {
                if (selectedMedia.includes(mediaId)) {
                    // Deselect if already selected
                    selectedMedia = selectedMedia.filter(id => id !== mediaId);
                    card.classList.remove('border-primary');
                } else {
                    // Select new media
                    selectedMedia.push(mediaId);
                    card.classList.add('border-primary');
                }
            } else {
                // Single select: clear others and select current
                selectedMedia = [mediaId];
                mediaCards.forEach(c => c.classList.remove('border-primary'));
                card.classList.add('border-primary');
            }
            updateInputs();
        });
    });

    // Initial rendering of hidden input(s)
    updateInputs();
});
</script>
@endpush

<style>
/* Make media cards clickable and highlight selected ones */
.media-card {
    cursor: pointer;
    transition: border-color 0.3s ease;
}
.media-card.border-primary {
    border: 2px solid #007bff;
}
</style>