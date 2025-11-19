import {html, LitElement} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import Modal from './Modal';

@customElement('x-post-form-modal')
export default class PostFormModal extends LitElement {
    @property({attribute: 'x-title'})
    private _title: string = 'New post';

    render() {
        return html`<x-modal class="modal" x-title="${this._title}">
            <x-post-form class="form" slot="content" @post:created="${() => this.getModal().hide()}"></x-post-form>
        </x-modal>`;
    }

    public getModal(): Modal {
        return this.shadowRoot.querySelector('.modal');
    }
}