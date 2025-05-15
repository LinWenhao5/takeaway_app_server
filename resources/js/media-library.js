import { LitElement, html } from 'lit';
import axios from 'axios';
import './delete-confirm';

export class MediaLibrary extends LitElement {
    createRenderRoot() {
        return this;
    }

    static properties = {
        media: { type: Array },
    };

    constructor() {
        super();
        this.media = [];
    }

    connectedCallback() {
        super.connectedCallback();
        this.loadMedia();
    }

    async loadMedia() {
        try {
            const response = await axios.get('/api/media');
            this.media = response.data;
        } catch (error) {
            console.error('Error loading media:', error);
            Swal.fire('Error!', 'Failed to load media.', 'error');
        }
    }

    render() {
        return html`
            <div class="container mt-4">
                <div class="row">
                    ${this.media.map(
                        item => html`
                            <div class="col-md-3">
                                <div class="card">
                                    <img src="/storage/${item.path}" alt="${item.name}" class="card-img-top" />
                                    <div class="card-body">
                                        <p class="card-text">${item.name}</p>
                                        <delete-confirm
                                            action="/api/media/${item.id}"
                                            title="Delete Media?"
                                            text="Are you sure you want to delete '${item.name}'?"
                                            confirm-button-text="Yes, delete it!"
                                            success-message="Media deleted successfully!"
                                            error-message="Failed to delete media."
                                        >
                                        </delete-confirm>
                                    </div>
                                </div>
                            </div>
                        `
                    )}
                </div>
            </div>
        `;
    }
}

customElements.define('media-library', MediaLibrary);