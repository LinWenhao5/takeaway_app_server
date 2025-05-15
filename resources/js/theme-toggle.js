import { LitElement, html } from 'lit';

export class ThemeToggle extends LitElement {
    constructor() {
        super();
        this.theme = localStorage.getItem('bs-theme') || 'light';
    }

    createRenderRoot() {
        return this;
    }

    connectedCallback() {
        super.connectedCallback();
        this._applyTheme();
    }

    _applyTheme() {
        document.documentElement.setAttribute('data-bs-theme', this.theme);
        localStorage.setItem('bs-theme', this.theme);
        this.requestUpdate();
    }

    _toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        this._applyTheme();
    }

    render() {
        const btnClass = this.theme === 'dark'
            ? 'btn btn-sm btn-light w-100'
            : 'btn btn-sm btn-dark w-100';
        const btnText = this.theme === 'dark' ? 'Light Mode' : 'Dark Mode';
        return html`
            <button class="${btnClass}" @click=${this._toggleTheme}>
                ${btnText}
            </button>
        `;
    }
}

if (!customElements.get('theme-toggle')) {
    customElements.define('theme-toggle', ThemeToggle);
}