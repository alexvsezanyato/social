import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import PostData from '@/types/post.d';
import XElement from '@/ui/XElement';

@customElement('x-post-content')
export default class XPostContent extends XElement {
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

        .text {
            padding: 10px;
            margin-bottom: -4px;
            margin-top: -4px;
            overflow: hidden;
            min-height: 33px;
            line-height: 21px;
            word-spacing: 1.5px;
            text-align: justify;
            white-space: pre-wrap;
        }
    `];

    @property({attribute: false})
    public data: PostData;

    render() {
        return html`
            <div>
                <div class="text">${this.data.text}</div>
            </div>

            ${this.data.pictures.length !== 0 ? html`<div>
                <x-pictures .data="${this.data.pictures}"></x-pictures>
            </div>` : ''}

            ${this.data.documents.length !== 0 ? html`<div>
                <x-documents .data="${this.data.documents}"></x-documents>
            </div>` : ''}
        `;
    }
}