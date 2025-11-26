import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-action')
export default class XAction extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            display: flex;
            align-items: center;
            margin: 4px;
            border-radius: 5px;
            height: 25px;
            cursor: pointer;
        }

        :host(:hover) {
            background: #ddd;
        }

        :host([x-align="center"]) {
            justify-content: center;
        }

        .icon {
            margin: 0;
            border-radius: 5px;
        }

        .text {
            margin-left: 4px;
        }

        .icon[x-name=""] {
            display: none;
        }

        .text:empty {
            display: none;
        }
    `];

    @property({attribute: 'x-icon'})
    private _icon: string = '';

    @property({attribute: 'x-text'})
    private _text: string = '';

    @property({attribute: 'x-align', reflect: true, type: String})
    align: 'left'|'center'|'right' = 'center';

    render() {
        return html`
            <x-icon class="icon" x-name="${this._icon}"></x-icon>
            <div class="text">${this._text}</div>
        `;
    }
}