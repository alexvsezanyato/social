import {CSSResultGroup, LitElement, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import PostCommentData from '@/types/post-comment.d';
import {deletePostComment} from '@/api/post-comment';
import IPostComment from '@/types/post-comment';

@customElement('x-post-comment')
export default class PostComment extends LitElement {
    static styles?: CSSResultGroup = css`
        .author {
            display: flex;
            align-items: center;
        }

        .text {
            padding: 10px;
            padding-top: 0;
            font-weight: 400;
        }

        .header {
            display: flex;
            justify-content: space-between;
        }
    `;

    @property({attribute: false})
    public data: PostCommentData;

    render() {
        return html`<div class="comment">
            <div class="header">
                <div class="author">
                    <x-icon x-name="user"></x-icon>
                    <a class="name" href="/profile/index?id=${this.data.author.id}">${this.data.author.public}</a>
                </div>
                <x-action-dropdown>
                    <x-dropdown-item @click="${this.delete}" x-icon="trash" x-text="Delete"></x-dropdown-item>
                </x-action-dropdown>
            </div>

            <div class="text">${this.data.text}</div>
        </div>`;
    }

    public async delete() {
        await deletePostComment(this.data.id);

        this.dispatchEvent(new CustomEvent('post-comment:deleted', {
            bubbles: true,
            composed: true,
            detail: this.data,
        }));
    }
}