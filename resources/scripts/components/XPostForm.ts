import XElement from '@/ui/XElement';
import {html, css, CSSResultGroup} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import PostData from '@/types/post';
import {createPost, getPost} from '@/api/post';

@customElement('x-post-form')
export default class XPostForm extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        textarea {
            line-height: 20px;
            resize: none;
            border: none;
            font-family: Roboto, Arial, Tahoma;
            word-wrap: break-word;
            line-height: 21px;
            outline: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
            overflow-x: hidden;
            overflow-y: scroll;
            padding: 10px;
            min-height: 150px;
            max-height: 500px;
            width: 100%;
        }

        input[type=file] {
            display: none;
        }

        .documents {
            list-style-type: none;   
            margin: 0;
            padding: 0;
        }

        .document {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: default;
            white-space: nowrap;
            overflow: hidden;
        }

        .document .info {
            display: flex;
            align-items: center;
            flex-grow: 1;
            overflow: hidden;
        }

        .pictures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            padding: 5px;
            margin: 0;
        }

        .picture {
            margin: 5px;
            height: 70px;
            display: flex;
            justify-content: flex-end;
            background-size: contain;
            border-radius: 3%;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .picture .action {
            background-color: rgba(0, 0, 0, .6);
            color: #fff;
            opacity: 0;
        }

        .picture:hover .action {
            opacity: 1;
        }
    `];

    @property({type: Array})
    pictures: File[] = [];

    @property({type: Array})
    documents: File[] = [];

    render() {
        return html`
            <div>
                <x-actions>
                    <div @click="${this._openFileDialog}">
                        <x-action x-icon="file-arrow-up"></x-action>
                        <input type="file" multiple @change="${this._onDocumentsSelected}">
                    </div>
                    <div @click="${this._openFileDialog}">
                        <x-action x-icon="file-image"></x-action>
                        <input type="file" multiple @change="${this._onPicturesSelected}">
                    </div>
                </x-actions>
            </div>

            <div>
                <textarea name="text"></textarea>
            </div>

            ${this.pictures.length !== 0 ? html`<div>
                <div class="pictures">
                    ${map(this.pictures, picture => html`<div class="picture" style="background: url('${URL.createObjectURL(picture)}') center / cover no-repeat">
                        <x-action @click="${() => this.pictures = this.pictures.filter(e => e !== picture)}" x-icon="xmark" class="action"></x-action>
                    </div>`)}
                </div>
            </div>` : ''}

            ${this.documents.length !== 0 ? html`<div>
                <div class="documents">
                    <div class="document">
                        <div class="info" style="padding-left: 10px">${this.documents.length} document(s) to upload</div>
                        <x-action @click="${() => this.documents = []}" x-icon="trash"></x-action>
                    </div>

                    ${map(this.documents, document => html`<div class="document">
                        <div class="info"><x-icon x-name="file"></x-icon>${document.name}</div>
                        <x-action @click="${() => this.documents = this.documents.filter(e => e !== document)}" x-icon="xmark"></x-action>
                    </div>`)}
                </div>
            </div>` : ''}

            <div>
                <x-action @click="${this.send}" x-text="Post" x-align="center" class="submit"></x-action>
            </div>
        `;
    }

    get textElement(): HTMLTextAreaElement {
        return this.shadowRoot.querySelector('[name="text"]');
    }

    private _openFileDialog(e: Event) {
        const currentTarget = e.currentTarget as HTMLElement;
        currentTarget.querySelector('input').click();
    }

    private _onPicturesSelected(e: Event) {
        const target = e.target as HTMLInputElement;
        this.pictures = target.files.length ? Array.from(target.files) : [];
    }

    private _onDocumentsSelected(e: Event) {
        const target = e.target as HTMLInputElement;
        this.documents = target.files.length ? Array.from(target.files) : [];
    }

    public async send() {
        const postId = await createPost({
            text: this.textElement.value,
            documents: this.documents,
            pictures: this.pictures,
        });

        this.dispatchEvent(new CustomEvent<PostData>('post:created', {
            bubbles: true,
            composed: true,
            detail: await getPost(postId),
        }));
    }

    public clean() {
        this.textElement.value = '';
        this.pictures = [];
        this.documents = [];
    }
}