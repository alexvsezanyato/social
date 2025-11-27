import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-block')
export default class XBlock extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    `];

    render() {
        return html`<slot></slot>`;
    }
}