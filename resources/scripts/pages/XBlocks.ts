import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement} from 'lit/decorators.js';

@customElement('x-blocks')
export default class XBlocks extends XElement {
    static styles?: CSSResultGroup = [XElement.styles, css`
        :host {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    `];

    render() {
        return html`<slot></slot>`;
    }
}