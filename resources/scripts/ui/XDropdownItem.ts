import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-dropdown-item')
export default class XDropdownItem extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        :host(:hover) {
            background-color: #eee;
        }

        .icon {
            border-right: 1px solid #ddd;
            margin: 0;
        }

        .text:not(:empty) {
            padding-left: 10px;
        }
    `];

    @property({attribute: 'x-icon'})
    private _icon: string = '';

    @property({attribute: 'x-text'})
    private _text: string = '';

    render() {
        return html`
            <x-icon class="icon" x-name="${this._icon}"></x-icon>
            <div class="text">${this._text}</div>
        `;
    }
}