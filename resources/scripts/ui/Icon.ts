import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-icon')
export default class Icon extends LitElement {
    static styles: CSSResultGroup = css`
        .icon {
            width: 25px;
            height: 25px;
            padding: 0;
            margin: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
        }
    `;

    @property({attribute: 'x-name'})
    private _name: string;

    render() {
        return html`<wa-icon part="root" class="icon" name="${this._name}"></wa-icon>`;
    }
}
