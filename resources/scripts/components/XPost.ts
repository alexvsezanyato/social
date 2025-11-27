import XSections from '@/ui/XSections';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import {repeat} from 'lit/directives/repeat.js';
import PostData from '@/types/post.d';
import PostCommentData from '@/types/post-comment.d';
import {deletePost} from '@/api/post';

@customElement('x-post')
export default class XPost extends XSections {
    static styles: CSSResultGroup = [XSections.styles, css`
        :host {
            gap: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #fff;
            font-family: Roboto, Arial, Tahoma;
            font-size: 14px;
        }

        .title {
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
        }

        .documents-header {
            padding: 10px;
        }

        .document {
            display: flex;
            align-items: center;
            white-space: nowrap;
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
        }

        .comments > * {
            display: block;
        }
    `];

    constructor() {
        super();

        this.addEventListener('post-comment:deleted', (e: CustomEvent<PostCommentData>) => {
            this.data.comments = this.data.comments.filter(postComment => postComment.id !== e.detail.id);
            this.requestUpdate();
        });

        this.addEventListener('post-comment:created', (e: CustomEvent<PostCommentData>) => {
            this.data.comments.unshift(e.detail);
            this.requestUpdate();
        });
    }

    @property({attribute: false})
    public data: PostData;

    render() {
        return html`
            <div>
                <div class="title">
                    <div class="user">
                        <a href="/profile/index?id=${this.data.author.id}">${this.data.author.public}</a>
                    </div>

                    <div>
                        <div class="datetime"> 
                            <div class="date">${this.data.createdAt.date} at</div>
                            <div class="time">${this.data.createdAt.time}</div>
                        </div>

                        <x-action-dropdown>
                            <x-dropdown-item @click="${this.delete}" x-icon="trash" x-text="Delete"></x-dropdown-item>
                        </x-action-dropdown>
                    </div>
                </div>
            </div>

            <div>
                <div class="data">${this.data.text}</div>
            </div>

            <div ?hidden="${this.data.pictures.length === 0}">
                <div class="pictures">
                    ${map(this.data.pictures, picture => html`<a href="/pictures/${picture.id}/download">
                        <div class="picture" style="background: url('/pictures/${picture.id}/download') center / cover no-repeat"></div>
                    </a>`)}
                </div>
            </div>
            
            <div ?hidden="${this.data.documents.length === 0}">
                <div class="documents">
                    <div class="documents-header">${this.data.documents.length} document(s)</div>

                    ${map(this.data.documents, document => html`<div class="document">
                        <div class="icon"><x-icon x-name="file"></x-icon></div>
                        <div class="name"><a class="link" href="/documents/${document.id}/download" download>${document.name}</a></div>
                    </div>`)}
                </div>
            </div>

            <div>
                <x-post-comment-form .postId="${this.data.id}"></x-post-comment-form>
            </div>

            <div ?hidden="${this.data.comments.length === 0}">
                <div class="comments">
                    ${repeat(this.data.comments, comment => comment.id, comment => html`<x-post-comment .data="${comment}"></x-post-comment>`)}
                </div>
            </div>
        `;
    }

    public async delete() {
        await deletePost(this.data.id);

        this.dispatchEvent(new CustomEvent('post:deleted', {
            bubbles: true,
            composed: true,
            detail: this.data,
        }));
    }
}