import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement} from 'lit/decorators.js';

@customElement('x-actions')
export default class XActions extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            display: flex;
        }
    `];

    render() {
        return html`<slot></slot>`;
    }
}