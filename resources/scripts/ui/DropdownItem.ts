import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-dropdown-item')
export default class DropdownItem extends LitElement {
    static styles: CSSResultGroup = css`
        .action {
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .action:hover {
            background-color: #eee;
        }
        .action:hover .icon::part(root) {
            background-color: #bbb;
        }
        .icon::part(root) {
            border-radius: 5px;
            background: #ddd;
        }
        .text:not(:empty) {
            padding-left: 4px;
        }
    `;

    @property({attribute: 'x-icon'})
    private _icon: string = '';

    @property({attribute: 'x-text'})
    private _text: string = '';

    render() {
        return html`<div part="root" class="action">
            <x-icon class="icon" x-name="${this._icon}"></x-icon>
            <div class="text">${this._text}</div>
        </div>`;
    }
}
