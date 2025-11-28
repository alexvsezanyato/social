import XBlock from '@/ui/XBlock';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import User from '@/types/user';
import {addFriend, removeFriend} from '@/api/user';

@customElement('x-friend')
export default class XFriend extends XBlock {
    static styles?: CSSResultGroup = [XBlock.styles, css`
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #ddd;
        }

        .login {
            display: flex;
            align-items: center;
            padding-left: 10px;
            height: 100%;
            color: blue;
        }

        .login:hover {
            text-decoration: underline;
        }

        .content {
            padding: 10px;
        }
    `];

    @property({attribute: false})
    user: User;

    @property({attribute: false})
    friend: User;

    @property({attribute: false})
    actions: string[] = [];

    render() {
        return html`
            <div class="header">
                <div>
                    <a class="login" href="/profile/index?id=${this.friend.id}">${this.friend.login}</a>
                </div>

                ${this.actions.length !== 0 ? html`<x-actions>
                    ${this.actions.includes('add') ? html`<x-action @click="${this.onAdd}" x-icon="user-plus"></x-action>` : ''}
                    ${this.actions.includes('remove') ? html`<x-action @click="${this.onRemove}" x-icon="user-minus"></x-action>` : ''}
                </x-actions>` : ''}
            </div>

            <div class="content">
                <span>Name: ${this.friend.public}</span><br>
                <span>Age: ${this.friend.age}</span>
            </div>
        `;
    }

    async onAdd() {
        await addFriend(this.user.id, this.friend.id);

        this.dispatchEvent(new CustomEvent('friend:added', {
            bubbles: true,
            composed: true,
            detail: this,
        }));
    }

    async onRemove() {
        await removeFriend(this.user.id, this.friend.id);

        this.dispatchEvent(new CustomEvent('friend:removed', {
            bubbles: true,
            composed: true,
            detail: this,
        }));
    }
}