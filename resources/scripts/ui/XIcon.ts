import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-icon')
export default class XIcon extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            width: 25px;
            height: 25px;
            padding: 0;
            margin: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
        }
    `];

    @property({attribute: 'x-name'})
    private _name: string;

    render() {
        return html`<wa-icon name="${this._name}"></wa-icon>`;
    }
}