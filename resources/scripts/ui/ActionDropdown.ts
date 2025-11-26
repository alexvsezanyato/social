import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement, property, state} from 'lit/decorators.js';

@customElement('x-action-dropdown')
export default class ActionDropdown extends LitElement {
    static styles: CSSResultGroup = css`
        [hidden] {
            display: none!important;
        }
        :host {
            position: relative;
        }
        .items {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 100;
        }
    `;

    @state()
    private _hidden: boolean = true;

    render() {
        return html`<div part="root">
            <x-action @click="${this.toggle}" x-icon="caret-down"></x-action>

            <x-dropdown class="items" ?hidden="${this._hidden}">
                <x-dropdown-item @click="${this.toggle}" x-icon="caret-left" x-text="Back"></x-dropdown-item>
                <slot></slot>
            </x-dropdown>
        </div>`;
    }

    toggle() {
        this._hidden = !this._hidden;
    }
}