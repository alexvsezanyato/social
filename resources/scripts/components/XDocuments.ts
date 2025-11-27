import XElement from '@/ui/XElement';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import IDocument from '@/types/document';

@customElement('x-documents')
export default class XDocuments extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host > *::after {
            content: '';
            display: block;
            width: calc(100% - 20px);
            margin: 0 10px;
            height: 1px;
            border-bottom: 1px dashed #ddd;
        }

        :host > *:last-child::after {
            content: none;
        }

        .header {
            display: flex;
            align-items: center;
            height: 33px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .item {
            display: flex;
            align-items: center;
            white-space: nowrap;
            flex-shrink: 1;
            flex-grow: 1;
            overflow: hidden;
            padding: 0;
        }

        .item .link {
            color: #000;
            text-decoration: none;
        }

        .item .link:hover {
            text-decoration: underline;
        }
    `];

    @property({attribute: false})
    public data: IDocument[];

    render() {
        return html`
            <div>
                <div class="header">${this.data.length} document(s)</div>
            </div>

            ${map(this.data, document => html`<div>
                <div class="item">
                    <div class="icon"><x-icon x-name="file"></x-icon></div>
                    <div class="name"><a class="link" href="/documents/${document.id}/download" download>${document.name}</a></div>
                </div>
            </div>`)}
        `;
    }
}