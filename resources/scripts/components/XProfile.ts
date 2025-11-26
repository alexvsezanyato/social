import XElement from '@/ui/XElement';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import User from '@/types/user';

@customElement('x-profile')
export default class XProfile extends XElement {
    static styles?: CSSResultGroup = [XElement.styles, css`
        .profile-header {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 0;
            margin: 0;
            margin-bottom: 15px;
        }

        .profile-header > * {
            display: flex;
            padding: 10px;
            list-style-type: none;
            border-bottom: 1px solid #ddd;
        }

        .profile-header > *:last-of-type {
            border-bottom: none;
        }

        .profile-header .title {
            color: #444;
            margin-right: 5px;
        }
    `];

    @property({type: Object})
    user: User;

    render() {
        return html`${this.user !== undefined ? html`<div class="profile-header">
            <div>
                <div class="title">Name: </div>
                <div class="value">${this.user.public}</div>
            </div>
            <div>
                <div class="title">Login: </div>
                <div class="value">${this.user.login}</div>
            </div>
            <div>
                <div class="title">Age: </div>
                <div class="value">${this.user.age}</div>
            </div>
        </div>` : ''}`;
    }
}