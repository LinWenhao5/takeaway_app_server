<div>
    <div class="d-flex align-items-center mb-2">
        <label for="media" class="form-label mb-0 me-2">{{ $label }}</label>
        <!-- Button to open the media library in a new tab -->
        <a href="{{ route('admin.media.library') }}" target="_blank" class="btn btn-sm btn-outline-primary">
            Upload Media
        </a>
    </div>
    <div class="row">
        @foreach ($media as $item)
            <div class="col-md-3 mb-3">
                <!-- Card for each media item, highlight if selected -->
                <div class="card media-card {{ (isset($selected) && in_array($item->id, $selected)) ? 'border-primary' : '' }}" data-id="{{ $item->id }}">
                    <img src="{{ asset('storage/' . $item->path) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body text-center">
                        <p class="card-text">{{ $item->name }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Hidden input to store selected media IDs, separated by commas -->
    <input type="hidden" name="{{ $name }}" id="selected-media" value="{{ isset($selected) ? implode(',', $selected) : '' }}">
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mediaCards = document.querySelectorAll('.media-card');
        const selectedMediaInput = document.getElementById('selected-media');
        // Initialize selectedMedia from the hidden input value (comma separated)
        let selectedMedia = (selectedMediaInput.value ? selectedMediaInput.value.split(',').filter(Boolean) : []);

        mediaCards.forEach(card => {
            card.addEventListener('click', () => {
                const mediaId = card.getAttribute('data-id');

                // Toggle selection: add or remove mediaId from selectedMedia
                if (selectedMedia.includes(mediaId)) {
                    selectedMedia = selectedMedia.filter(id => id !== mediaId);
                    card.classList.remove('border-primary');
                } else {
                    selectedMedia.push(mediaId);
                    card.classList.add('border-primary');
                }

                // Update the hidden input value with the current selection
                selectedMediaInput.value = selectedMedia.join(',');
                console.log('Selected Media:', selectedMediaInput.value);
            });
        });
    });
</script>

<style>
    .media-card {
        cursor: pointer;
        transition: border-color 0.3s ease;
    }
    .media-card.border-primary {
        border: 2px solid #007bff;
    }
</style>