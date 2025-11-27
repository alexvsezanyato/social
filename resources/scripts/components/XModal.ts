import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';

@customElement('x-modal')
export default class XModal extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            z-index: 2;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            overflow-y: scroll;
            background: rgba(0, 0, 0, .6);
        }

        .wrapper {
            height: 100%;
            min-height: max-content;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .window {
            flex: 1 1 100%;
            background: #fff;
            border-radius: 5px;
            max-width: 500px;
            margin: 30px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            align-content: stretch;
            height: max-content;
        }

        .header {
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 11px;
            font-family: Arial;
            font-weight: bold;
        }
    `];

    @property({attribute: 'x-title'})
    private _title: string;

    render() {
        return html`
            <div class="wrapper">
                <div class="window">
                    <div class="header">
                        <div class="title">${this._title}</div>
                        <x-action @click="${this.toggle}" x-icon="xmark"></x-action>
                    </div>
                    <div class="content">
                        <slot name="content"></slot>
                    </div>
                </div>
            </div>
        `;
    }
}