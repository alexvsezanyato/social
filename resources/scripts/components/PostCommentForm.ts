import {CSSResultGroup, LitElement, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {createPostComment, deletePostComment, getPostComment} from '@/api/post-comment';

@customElement('x-post-comment-form')
export default class PostCommentForm extends LitElement {
    static styles?: CSSResultGroup = css`
        .new-comment {
            display: flex;
            align-items: stretch;
            border-top: 1px solid #ddd;
        }

        .input {
            width: 100%;
            border: none;
            outline: none;
            padding: 0;
        }
    `;

    @property({attribute: false})
    public postId: number;

    render() {
        return html`<div class="new-comment">
            <div class="author">
                <x-icon class="icon" x-name="user"></x-icon>
            </div>

            <input name="comment" class="input" type="text" placeholder="Comment">
            <x-action @click="${this.save}" x-icon="paper-plane"></x-action>
        </div>`;
    }

    async save() {
        const input = this.shadowRoot.querySelector('.input') as HTMLInputElement;

        const commentId = await createPostComment({
            postId: this.postId,
            text: input.value,
        });

        input.value = '';

        this.dispatchEvent(new CustomEvent('post-comment:created', {
            bubbles: true,
            composed: true,
            detail: await getPostComment(commentId),
        }));
    }
}