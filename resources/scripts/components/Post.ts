import {CSSResultGroup, LitElement, css, html} from 'lit';
import {customElement, property, state} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import PostData from './../types/post.d';

@customElement('x-post')
export default class Post extends LitElement {
    static styles?: CSSResultGroup = css`
    * {
        box-sizing: border-box;
    }
        [hidden] {
            display: none!important;
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

        .post {
            position: relative;
        }

        .post > .wrapper {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            background: #fff;
            font-family: Roboto, Arial, Tahoma;
            font-size: 14px;
        }

        .title {
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title > * {
            display: flex;
            align-items: center;
        }

        .title .user {
            margin-left: 10px;
        }

        .title .user > a {
            color: blue;
        }

        .title .datetime {
            font-size: 13px;
            font-weight: bold;
            color: #444;
            margin-right: 10px;
        }

        .title .datetime > * {
            display: inline;
        } 

        .title .delimiter {
            color: #444;
        }

        .data {
            padding: 10px;
            line-height: 21px;
            word-spacing: 1.5px;
            text-align: justify;
            font-family: Roboto, Arial, Tahoma;
            white-space: pre-wrap;
        }

        .documents {
            list-style-type: none;   
            margin: 0;
            padding: 0;
            font-size: 13px;
            font-family: Roboto, Arial, Tahoma;
            font-weight: bold;
        }

        .documents-header {
            padding: 10px;
        }

        .document {
            border-top: 1px solid #ddd;
            display: flex;
            align-items: center;
            white-space: nowrap;
            overflow: hidden;
            flex-shrink: 1;
            flex-grow: 1;
            overflow: hidden;
        }

        .document .link {
            color: #000;
            text-decoration: none;
        }

        .document .link:hover {
            text-decoration: underline;
        }

        .menu .items {
            position: absolute;
            top: 0;
            right: 0;
            margin: 4px;

            transition: 
                transform .1s,
                opacity .1s,
                box-shadow .1s;

            box-shadow: 0 0 100px 0 rgba(0,0,0,.4);
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0;
            list-style-type: none;
            min-width: 120px;
            color: #222;
            font-weight: 600;
            font-size: 13px;
            user-select: none;
        }

        .menu .item {
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background-color .03s;
            display: flex;
            align-items: center;
            padding-right: 10px;
        }

        .menu .item:last-of-type {
            border-bottom: none;
        }

        .menu .item:hover {
            background: #eee;
        }

        .menu .item i {
            box-sizing: content-box;
            padding-right: 10px;
            width: 14px;
            text-align: center;
        }

        .pictures {
            list-style-type: none;   
            padding: 5px;
            margin: 0;
            border-top: 1px solid #ddd;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }

        .picture {
            margin: 5px;
            flex-grow: 1;
            max-height: 100px;
            min-height: 30px;
            min-width: 20%;
            background-size: contain;
            border-radius: 5px;
            min-height: 70px;
            cursor: pointer;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            align-items: flex-start;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .comments {
            list-style-type: none;   
            margin: 0;
            padding: 0;
            font-size: 13px;
            font-family: Roboto, Arial, Tahoma;
            font-weight: bold;
            border-top: 1px solid #ddd;
        }

        .new-comment {
            display: flex;
            align-items: stretch;
            border-top: 1px solid #ddd;
        }

        .new-comment .input {
            width: 100%;
            border: none;
            outline: none;
            padding: 0;
        }

        .comment:before {
            content: '';
            width: calc(100% - 20px);
            margin: 0 10px;
            height: 1px;
            background: #e8e8e8;
            display: block;
        }

        .comments > .comment:first-of-type:before {
            content: none;
        }

        .comment .author {
            display: flex;
            align-items: center;
        }

        .comment .author .icon {
            display: flex;
            align-items: center;
        }

        .comment .text {
            padding: 10px;
            padding-top: 0;
            font-weight: 400;
        }
    `;

    @property({attribute: false})
    public data: PostData;

    @state()
    private _hiddenMenu = true;

    render() {
        return html`<div class="post" data-id="${this.data.id}">
            <div class="wrapper">
                <div class="title">
                    <div class="user">
                        <a href="/profile/index?id=${this.data.author.id}">${this.data.author.public}</a>
                    </div>

                    <div>
                        <div class="datetime"> 
                            <div class="date">${this.data.createdAt.date} at</div>
                            <div class="time">${this.data.createdAt.time}</div>
                        </div>

                        <div class="actions">
                            <div class="menu">
                                <div class="action" @click="${this.toggleMenu}"><wa-icon class="icon" name="caret-down"></wa-icon></div>

                                <div class="items" ?hidden="${this._hiddenMenu}">
                                    <div class="item" @click="${this.toggleMenu}"><wa-icon class="icon" name="caret-left"></wa-icon>Back</div>
                                    <div class="item" @click="${this.delete}"><wa-icon class="icon" name="trash"></wa-icon>Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="data">${this.data.text}</div>

                <ul class="pictures" ?hidden="${this.data.pictures.length === 0}">
                    ${map(this.data.pictures, picture => html`<li class="picture" style="background: url('/uploads/pictures/${picture.source}') center / cover no-repeat"></li>`)}
                </ul>
                
                <div class="documents" ?hidden="${this.data.documents.length === 0}">
                    <div class="documents-header">${this.data.documents.length} document(s)</div>

                    ${map(this.data.documents, document => html`<div class="document">
                        <div class="icon"><wa-icon name="file"></wa-icon></div>
                        <div class="name"><a class="link" href="/document/download?id=${document.source}&name=${document.name}&type=${document.mime}" download>${document.name}</a></div>
                    </div>`)}
                </div>

                <div class="new-comment">
                    <div class="author">
                        <wa-icon class="icon" name="user"></wa-icon>
                    </div>

                    <input name="comment" class="input" type="text" placeholder="Comment">

                    <div class="actions">
                        <div class="action" @click="${this.createComment}"><wa-icon class="icon" name="paper-plane"></wa-icon></div>
                    </div>
                </div>

                <div class="comments" ?hidden="${this.data.comments.length === 0}">
                    ${map(this.data.comments, comment => html`<div class="comment">
                        <div class="author">
                            <wa-icon class="icon" name="user"></wa-icon>
                            <a class="name" href="/profile/index?id=${comment.author.id}">${comment.author.public}</a>
                        </div>

                        <div class="text">${comment.text}</div>
                    </div>`)}
                </div>
            </div>
        </div>`;
    }

    public toggleMenu() {
        this._hiddenMenu = !this._hiddenMenu;
    }

    public async delete() {
        const responseBody = await fetch(`/api/post/delete?id=${this.data.id}`).then(response => {
            return response.json();
        });

        if (responseBody.status === 'success') {
            this.dispatchEvent(new CustomEvent('post:deleted', {
                bubbles: true,
                composed: true,
                detail: this.data,
            }));
        } else if (responseBody.error) {
            console.error(responseBody.error);
        }
    }

    public async createComment() {
        const post = this.shadowRoot.querySelector('.post') as HTMLElement;
        const input = post.querySelector('.new-comment input') as HTMLInputElement;

        const formData = new FormData();
        formData.append('post_id', String(this.data.id));
        formData.append('text', input.value);

        const response = await fetch('/api/post-comment/create', {
            method: 'POST',
            body: formData,
        });

        this.data.comments.push(await response.json());
        this.requestUpdate();
    }
}