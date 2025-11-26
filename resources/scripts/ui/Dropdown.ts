import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement} from 'lit/decorators.js';

@customElement('x-dropdown')
export default class Dropdown extends LitElement {
    static styles: CSSResultGroup = css`
        .items {
            min-width: 120px;
            font-size: 13px;
            display: flex;
            flex-direction: column;
            border-radius: 5px;
            margin: 4px;
            background-color: #eee;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 12px 2px rgba(0, 0, 0, .35)
        }
        ::slotted(*) {
            display: block;
        }
        ::slotted(*)::after {
            content: '';
            display: block;
            width: 100%;
            height: 1px;
            background: #ddd;
        }
        ::slotted(*:last-child)::after {
            content: none;
        }
    `;

    render() {
        return html`<div part="root" class="items">
            <slot></slot>
        </div>`;
    }
}