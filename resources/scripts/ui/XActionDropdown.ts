import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement} from 'lit/decorators.js';
import XDropdown from './XDropdown';

@customElement('x-action-dropdown')
export default class XActionDropdown extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            position: relative;
        }

        .items {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 100;
        }
    `];

    render() {
        return html`
            <x-action @click="${() => this.dropdown.toggle()}" x-icon="caret-down"></x-action>

            <x-dropdown class="items" hidden>
                <x-dropdown-item @click="${() => this.dropdown.toggle()}" x-icon="caret-left" x-text="Back"></x-dropdown-item>
                <slot></slot>
            </x-dropdown>
        `;
    }

    get dropdown(): XDropdown {
        return this.shadowRoot.querySelector('.items');
    }
}