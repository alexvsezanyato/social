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

        .pictures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 5px;
            padding: 10px;
        }

        .picture {
            cursor: pointer;
            flex-grow: 1;
            max-height: 100px;
            min-height: 30px;
            min-width: 20%;
            background-size: contain;
            border-radius: 5px;
            min-height: 70px;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            align-items: flex-start;
            padding: 5px;
            border: 1px solid #ddd;
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
                <div class="pictures">
                    ${map(this.data.pictures, picture => html`<a href="/pictures/${picture.id}/download">
                        <div class="picture" style="background: url('/pictures/${picture.id}/download') center / cover no-repeat"></div>
                    </a>`)}
                </div>
            </div>` : ''}

            ${this.data.documents.length !== 0 ? html`<div>
                <x-documents class="documents" .data="${this.data.documents}"></x-documents>
            </div>` : ''}
        `;
    }
}