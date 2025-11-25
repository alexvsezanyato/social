import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-action')
export default class Action extends LitElement {
    static styles: CSSResultGroup = css`
        .action {
            display: flex;
            align-items: center;
            margin: 4px;
            border-radius: 5px;
            cursor: pointer;
        }
        .action:hover {
            background-color: #ddd;
        }
        .icon::part(root) {
            margin: 0;
            border-radius: 5px;
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
        return html`<div class="action" part="root">
            <x-icon class="icon" x-name="${this._icon}"></x-icon>
            <div class="text">${this._text}</div>
        </div>`;
    }
}
