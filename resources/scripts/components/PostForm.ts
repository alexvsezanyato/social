import {LitElement, html, css, CSSResultGroup} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import PostData from '../types/post';
import { getPost } from '../app/Post';

@customElement('x-post-form')
export default class PostForm extends LitElement {
    static styles: CSSResultGroup = css`
        [hidden] {
            display: none!important;
        }

        .post-form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        textarea {
            line-height: 20px;
            resize: none;
            border: none;
            font-family: Roboto, Arial, Tahoma;
            font-size: 14px;
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
        }

        input[type=file] {
            display: none;
        }

        .submit {
            background: none;
            border: none;
            border-top: 1px solid #ddd;
            padding: 10px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            font-family: Roboto, Arial;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top-left-radius: 1px;
            border-top-right-radius: 1px;
            transition: .1s background, .1s color;
        }

        .submit:hover {
            background: #ddd;
            background: rgba(137, 196, 244, .3);
            color: rgba(34, 167, 240, 1);
        }

        .documents {
            list-style-type: none;   
            margin: 0;
            padding: 0;
        }

        .document {
            border-top: 1px solid #ddd;
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

        .actions {
            display: flex;
        }

        .icon {
            width: 25px;
            height: 25px;
            padding: 0;
            margin: 4px;
            border-radius: 20%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
        }

        .action .icon {
            cursor: pointer;
        }

        .action .icon:hover {
            background: #ddd;
        }

        .action.unpin {
            visibility: hidden;
        }

        .document:hover .action.unpin {
            visibility: visible;
        }

        .pictures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            padding: 5px;
            margin: 0;
            border-top: 1px solid #ddd;
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

        .picture .action .icon {
            background: rgba(0, 0, 0, .6);
            color: #fff;
            transform: scale(.85);
            opacity: 0;
            transition: transform .1s, opacity .1s, background-color .1s; 
        }

        .picture:hover .action .icon {
            transform: scale(1);
            opacity: 1;
        }

        .picture .action .icon:hover {
            background: rgba(0, 0, 0, .9);
        }
    `;

    @property({type: Array})
    pictures: File[] = [];

    @property({type: Array})
    documents: File[] = [];

    render() {
        return html`<div class="post-form">
            <div class="actions">
                <div class="action" @click="${this._openFileDialog}">
                    <wa-icon class="icon" name="file-arrow-up"></wa-icon>
                    <input type="file" multiple @change="${this._onDocumentsSelected}">
                </div>
                <div class="action" @click="${this._openFileDialog}">
                    <wa-icon class="icon" name="file-image" variant="regular"></wa-icon>
                    <input type="file" multiple @change="${this._onPicturesSelected}">
                </div>
            </div>

            <textarea class="input"></textarea>
            <div class="pictures" ?hidden="${this.pictures.length === 0}">
                ${map(this.pictures, picture => html`<div class="picture" style="background: url('${URL.createObjectURL(picture)}') center / cover no-repeat">
                    <div class="actions">
                        <div class="action" @click="${() => this.pictures = this.pictures.filter(e => e !== picture)}"><wa-icon class="icon" name="xmark"></wa-icon></div>
                    </div>
                </div>`)}
            </div>
            <div class="documents" ?hidden="${this.documents.length === 0}">
                <div class="document">
                    <div class="info" style="padding-left: 10px">${this.documents.length} document(s) to upload</div>

                    <div class="actions">
                        <div class="action" @click="${() => this.documents = []}"><wa-icon class="icon" name="trash"></wa-icon></div>
                    </div>
                </div>

                ${map(this.documents, document => html`<div class="document">
                    <div class="info"><wa-icon class="icon" name="file"></wa-icon>${document.name}</div>

                    <div class="actions">
                        <div class="action" @click="${() => this.documents = this.documents.filter(e => e !== document)}"><wa-icon class="icon" name="xmark"></wa-icon></div>
                    </div>
                </div>`)}
            </div>
            <button class="submit" @click="${this.send}">Post</button>
        </div>`;
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
        const formData = new FormData()

        formData.append('text', this.shadowRoot.querySelector('textarea').value);

        for (const document of this.documents) {
            formData.append('documents[]', document);
        }

        for (const picture of this.pictures) {
            formData.append('pictures[]', picture);
        }

        const response = await fetch('/api/post/create', {
            method: 'POST',
            body: formData,
        });

        this.dispatchEvent(new CustomEvent<PostData>('post:created', {
            bubbles: true,
            composed: true,
            detail: await getPost(Number(await response.text())),
        }));
    }

    public clean() {
        (this.shadowRoot.querySelector('.input') as HTMLTextAreaElement).value = '';
        this.pictures = [];
        this.documents = [];
    }
}