import {CSSResultGroup, LitElement, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import PostData from '@/types/post.d';
import {deletePost} from '@/api/post';
import {createPostComment, deletePostComment, getPostComment} from '@/api/post-comment';
import IPostComment from '@/types/post-comment';
import {repeat} from 'lit/directives/repeat.js';

@customElement('x-post')
export default class Post extends LitElement {
    static styles?: CSSResultGroup = css`
        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none!important;
        }

        .post {
            position: relative;
        }

        .post > .wrapper {
            border: 1px solid #ddd;
            border-radius: 8px;
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

        .pictures {
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

        .comment .header {
            display: flex;
            justify-content: space-between;
        }
    `;

    @property({attribute: false})
    public data: PostData;

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

                        <x-dropdown-action>
                            <x-action @click="${this.delete}" x-icon="trash" x-text="Delete"></x-action>
                        </x-dropdown-action>
                    </div>
                </div>

                <div class="data">${this.data.text}</div>

                <div class="pictures" ?hidden="${this.data.pictures.length === 0}">
                    ${map(this.data.pictures, picture => html`<a href="/pictures/${picture.id}/download">
                        <div class="picture" style="background: url('/pictures/${picture.id}/download') center / cover no-repeat"></div>
                    </a>`)}
                </div>
                
                <div class="documents" ?hidden="${this.data.documents.length === 0}">
                    <div class="documents-header">${this.data.documents.length} document(s)</div>

                    ${map(this.data.documents, document => html`<div class="document">
                        <div class="icon"><x-icon x-name="file"></x-icon></div>
                        <div class="name"><a class="link" href="/documents/${document.id}/download" download>${document.name}</a></div>
                    </div>`)}
                </div>

                <div class="new-comment">
                    <div class="author">
                        <x-icon class="icon" x-name="user"></x-icon>
                    </div>

                    <input name="comment" class="input" type="text" placeholder="Comment">
                    <x-action @click="${this.createComment}" x-icon="paper-plane"></x-action>
                </div>

                <div class="comments" ?hidden="${this.data.comments.length === 0}">
                    ${repeat(this.data.comments, comment => comment.id, comment => html`<div class="comment">
                        <div class="header">
                            <div class="author">
                                <x-icon class="icon" x-name="user"></x-icon>
                                <a class="name" href="/profile/index?id=${comment.author.id}">${comment.author.public}</a>
                            </div>
                            <x-dropdown-action>
                                <x-action @click="${() => this.deleteComment(comment)}" x-icon="trash" x-text="Delete"></x-action>
                            </x-dropdown-action>
                        </div>

                        <div class="text">${comment.text}</div>
                    </div>`)}
                </div>
            </div>
        </div>`;
    }

    public async delete() {
        await deletePost(this.data.id);

        this.dispatchEvent(new CustomEvent('post:deleted', {
            bubbles: true,
            composed: true,
            detail: this.data,
        }));
    }

    public async createComment() {
        const post = this.shadowRoot.querySelector('.post') as HTMLElement;
        const input = post.querySelector('.new-comment input') as HTMLInputElement;

        const commentId = await createPostComment({
            postId: this.data.id,
            text: input.value,
        });

        input.value = '';
        this.data.comments.push(await getPostComment(commentId));
        this.requestUpdate();
    }

    public async deleteComment(comment: IPostComment) {
        await deletePostComment(comment.id);
        this.data.comments = this.data.comments.filter(e => e.id !== comment.id);
        this.requestUpdate();
    }
}