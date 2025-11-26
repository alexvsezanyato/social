import {css, CSSResultGroup, LitElement} from 'lit';

export default class XElement extends LitElement {
    static styles?: CSSResultGroup = css`
        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none!important;
        }

        :host {
            display: block;
        }
    `;
    
    public toggle() {
        this.hidden = !this.hidden;
    }

    public show() {
        this.hidden = false;
    }

    public hide() {
        this.hidden = true;
    }
}
