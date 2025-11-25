import {css, CSSResultGroup, html, LitElement} from 'lit';
import {customElement} from 'lit/decorators.js';

@customElement('x-actions')
export default class Actions extends LitElement {
    static styles: CSSResultGroup = css`
        .actions {
            display: flex;
        }
    `;

    render() {
        return html`<div class="actions">
            <slot></slot>
        </div>`;
    }
}
