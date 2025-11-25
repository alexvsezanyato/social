import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement, state} from 'lit/decorators.js';

@customElement('x-dropdown-action')
export default class DropdownAction extends LitElement {
    static styles: CSSResultGroup = css`
        [hidden] {
            display: none!important;
        }
        .wrapper {
            position: relative;
        }
        .items {
            position: absolute;
            top: 0;
            right: 0;
            margin: 4px;
            box-shadow: 0 0 100px 0 rgba(0,0,0,.4);
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-width: 120px;
            font-weight: 600;
            font-size: 12px;
            z-index: 100;
        }
    `;

    @state()
    private _hidden: boolean = true;

    render() {
        return html`<div class="wrapper">
            <x-action @click="${this.toggle}" x-icon="caret-down"></x-action>

            <div class="items" ?hidden="${this._hidden}">
                <x-action @click="${this.toggle}" x-icon="caret-left" x-text="Back"></x-action>
                <slot></slot>
            </div>
        </div>`;
    }

    toggle() {
        this._hidden = !this._hidden;
    }
}