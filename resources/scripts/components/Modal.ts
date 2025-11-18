import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-modal')
export default class Modal extends LitElement {
    static styles: CSSResultGroup = css`
        .modal {
            z-index: 2;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            display: flex;
            justify-content: center;
            align-items: center;

            overflow-y: scroll;
            background: rgba(0, 0, 0, .6);
        }

        .window {
            flex: 1 1 100%;
            background: #fff;
            border-radius: 5px;
            border: 1px solid #ddd;
            max-width: 500px;
            margin: 30px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            align-content: stretch;
            font-family: Roboto, Arial;
            font-size: 13px;
            font-weight: bold;
        }

        .header {
            font-family: Roboto, Arial;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 11px;
            user-select: none;
            font-family: Arial;
            font-size: 13px;
            font-weight: bold;
        }

        .header button {
            width: 33px;
            height: 33px;
            margin: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: 50%;
            font-size: 12px;
        }

        .header button:hover {
            background: rgba(137, 196, 244, .3);
            color: rgba(34, 167, 240, 1);
        }
    `;

    @property({attribute: 'x-title'})
    private _title: string;

    @property({attribute: 'x-hidden', type: Boolean})
    private _hidden: boolean = true;

    render() {
        return html`<div ?hidden=${this._hidden}>
            <div class="modal">
                <div class="window">
                    <div class="header">
                        <div class="title">${this._title}</div>
                        <button @click="${this.toggle}"><wa-icon name="xmark"></wa-icon></button>
                    </div>
                    <div class="content">
                        <slot name="content"></slot>
                    </div>
                </div>
            </div>
        </div>`;
    }

    public toggle() {
        this._hidden = !this._hidden;
    }

    public show() {
        this._hidden = false;
    }

    public hide() {
        this._hidden = true;
    }
}