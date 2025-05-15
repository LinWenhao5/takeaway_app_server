<div>
    <label for="media" class="form-label">{{ $label }}</label>
    <div class="row">
        @foreach ($media as $item)
            <div class="col-md-3 mb-3">
                <div class="card media-card" data-id="{{ $item->id }}">
                    <img src="{{ asset('storage/' . $item->path) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body text-center">
                        <p class="card-text">{{ $item->name }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <input type="hidden" name="{{ $name }}" id="selected-media">
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mediaCards = document.querySelectorAll('.media-card');
        const selectedMediaInput = document.getElementById('selected-media');
        let selectedMedia = [];

        mediaCards.forEach(card => {
            card.addEventListener('click', () => {
                const mediaId = card.getAttribute('data-id');

                if (selectedMedia.includes(mediaId)) {
                    selectedMedia = selectedMedia.filter(id => id !== mediaId);
                    card.classList.remove('border-primary');
                } else {
                    selectedMedia.push(mediaId);
                    card.classList.add('border-primary');
                }

                selectedMediaInput.value = JSON.stringify(selectedMedia);             
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