import { LitElement, html } from 'lit';
import Swal from 'sweetalert2';
import axios from 'axios';

export class DeleteConfirm extends LitElement {
    createRenderRoot() {
        return this;
    }

    static properties = {
        action: { type: String },
        title: { type: String },
        text: { type: String },
        confirmButtonText: { type: String },
        successMessage: { type: String },
        errorMessage: { type: String },
    };

    constructor() {
        super();
        this.action = '';
        this.title = 'Are you sure?';
        this.text = "You won't be able to revert this!";
        this.confirmButtonText = 'Yes, delete it!';
        this.successMessage = 'Deleted successfully!';
        this.errorMessage = 'An error occurred while deleting.';
    }

    async handleDelete(e) {
        e.preventDefault();

        const result = await Swal.fire({
            title: this.title,
            text: this.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.confirmButtonText,
        });

        if (result.isConfirmed) {
            try {
                const response = await axios.delete(this.action, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (response.data.message) {
                    await Swal.fire('Deleted!', this.successMessage, 'success');
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error!', this.errorMessage, 'error');
            }
        }
    }

    render() {
        return html`
            <button @click=${this.handleDelete} class="btn btn-danger btn-sm">
                Delete
            </button>
        `;
    }
}

customElements.define('delete-confirm', DeleteConfirm);