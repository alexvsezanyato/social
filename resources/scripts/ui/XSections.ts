import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement} from 'lit/decorators.js';

@customElement('x-sections')
export default class XSections extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host > * {
            display: flex;
            flex-direction: column;
        }

        :host > *::after {
            content: '';
            display: block;
            height: 1px;
            width: 100%;
            background: #ddd;
        }

        :host > *:last-child::after {
            content: none;
        }
    `];
}