import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement} from 'lit/decorators.js';

@customElement('x-dropdown')
export default class XDropdown extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
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
            box-shadow: 0 2px 20px 5px rgba(0, 0, 0, .35);
        }

        ::slotted(*) {
            border-bottom: 1px solid #ddd;
        }

        ::slotted(*:last-child) {
            border-bottom: none;
        }
    `];

    render() {
        return html`<slot></slot>`;
    }
}