import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import PostData from '@/types/post.d';
import {deletePost} from '@/api/post';
import XElement from '@/ui/XElement';

@customElement('x-post-header')
export default class XPostHeader extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        :host > * {
            display: flex;
            align-items: center;
        }

        .user {
            margin-left: 10px;
        }

        .user > a {
            color: blue;
        }

        .datetime {
            color: #444;
            margin-right: 10px;
        }
    `];

    @property({attribute: false})
    public data: PostData;

    @property({attribute: 'x-readonly', type: Boolean})
    public readonly: boolean = false;

    render() {
        return html`
            <div class="user">
                <a href="/profile/index?id=${this.data.author.id}">${this.data.author.public}</a>
            </div>

            <div>
                <div class="datetime"> 
                    <span class="date">${this.data.createdAt.date} at</span>
                    <span class="time">${this.data.createdAt.time}</span>
                </div>

                <x-action-dropdown>
                    ${!this.readonly ? html`<x-dropdown-item @click="${this.delete}" x-icon="trash" x-text="Delete"></x-dropdown-item>` : ''}
                </x-action-dropdown>
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