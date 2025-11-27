import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import PostData from '@/types/post.d';
import PostCommentData from '@/types/post-comment.d';
import XElement from '@/ui/XElement';

@customElement('x-post')
export default class XPost extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
        }

        :host > * {
            border-bottom: 1px solid #ddd;
        }

        :host > *:last-child {
            border-bottom: none;
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
            <x-post-header .data="${this.data}"></x-post-header>
            <x-post-content .data="${this.data}"></x-post-content>
            <x-post-comment-form .postId="${this.data.id}"></x-post-comment-form>
            ${this.data.comments.length !== 0 ? html`<x-post-comments .data="${this.data.comments}"></x-post-comments>` : ''}
        `;
    }
}