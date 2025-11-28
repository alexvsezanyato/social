import XElement from '@/ui/XElement';
import {html} from 'lit';
import {customElement, state} from 'lit/decorators.js';
import {repeat} from 'lit/directives/repeat.js';
import User from '@/types/user';
import {getFriends, getSuggestedFriends} from '@/api/user';
import states from '@/states';
import XFriend from '@/components/XFriend';

@customElement('x-friends-page')
export default class XFriendsPage extends XElement {
    @state()
    private _suggestedFriends: User[] = [];

    @state()
    private _friends: User[] = [];

    constructor() {
        super();

        getSuggestedFriends(states.user.id).then(users => {
            this._suggestedFriends = users;
        });

        getFriends(states.user.id).then(users => {
            this._friends = users;
        });

        this.addEventListener('friend:added', (e: CustomEvent) => {
            const friend = e.detail as XFriend;
            friend.actions = friend.actions.filter(action => action !== 'add');
            friend.actions = ['remove', ...friend.actions];
        });

        this.addEventListener('friend:removed', (e: CustomEvent) => {
            const friend = e.detail as XFriend;
            friend.actions = friend.actions.filter(action => action !== 'remove');
            friend.actions = ['add', ...friend.actions];
        });
    }

    render() {
        return html`
            <h3>Friends</h3>
            ${this._friends.length ? html`
                <x-blocks>
                    ${repeat(this._friends, user => user.id, user => html`
                        <x-friend .user="${states.user}" .friend="${user}" .actions="${['remove']}"></x-friend>
                    `)}
                </x-blocks>
            `: ''}

            ${this._suggestedFriends.length ? html`
                <h3>Suggestions</h3>
                <x-blocks>
                    ${repeat(this._suggestedFriends, user => user.id, user => html`
                        <x-friend .user="${states.user}" .friend="${user}" .actions="${['add']}"></x-friend>
                    `)}
                </x-blocks>
            ` : ''}
        `;
    }
}