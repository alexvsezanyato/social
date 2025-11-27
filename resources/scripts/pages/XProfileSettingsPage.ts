import XElement from '@/ui/XElement';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, state} from 'lit/decorators.js';
import {getUser, patchUser} from '@/api/user';
import IUser from '@/types/user';

@customElement('x-profile-settings-page')
export default class XProfileSettingsPage extends XElement {
    static styles?: CSSResultGroup = [XElement.styles, css`
        .sets .group {
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #fff;
        }

        .sets .group-item {
            border-top: 1px solid #ddd;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .sets .group-item:first-of-type {
            border-top: none;
            padding-top: 0;
        }

        .sets .group-item:last-of-type {
            padding-bottom: 0;
        }

        .sets .group-item > * {
            padding: 10px;
        }

        .sets .title {
            margin-top: 20px;
        }

        .sets .set-desc {
            padding: 10px 0;
            margin: 0px 10px;
            border-top: 1px dashed #ddd;
            color: #555;
        }

        .sets .input {
            padding: 0;
            user-select: none;
            display: flex;
            flex-direction: row;
            justify-content: center;
            cursor: text;
            transition: background .08s ease;
        }

        .sets .input a {
            display: block; 
            padding: 10px;
        }

        .sets .input > * {
            padding: 10px;
        }

        .sets .input:hover {
            background: #f0f0f0;
        }

        .sets .input input {
            font-family: 'Times New Roman', Arial, 'Courier New';
            background: rgba(0, 0, 0, .0);
            border: none;
            flex-grow: 1;
            outline: none;
            cursor: text;
            padding-top: 0;
            padding-bottom: 0;
            margin: auto;
        }

        .sets .input .set-name {
            color: #000;
            padding-right: 0;
        }

        .sets .input a {
            color: #EA2027;
            text-decoration: none;
        }

        .sets .input a:hover {
            background: rgba(231, 76, 60, .1);
        }

        .sets .apply {
            padding: 8px 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            background: #009432;
            color: #f7fffa;
            box-shadow: 0 2px 0 0 #006321;
            border: none;
            border-bottom: 1px solid #006321;
            margin-bottom: 40px;

            transition: 
                box-shadow .1s ease,
                background .1s ease,
                transform .1s ease;
        }

        .sets .apply:hover {
            background: #009e35;
            border-bottom: 1px solid #017d2a;
            box-shadow: 0 2px 0 0 #017d2a;
        }

        .sets .apply:active {
            transform: translate(0, 3px);
            box-shadow: none;
        }
    `];

    @state()
    private _user: IUser;

    constructor() {
        super();
        
        getUser().then(user => {
            this._user = user;
        });
    }

    render() {
        return html`
            <h3>Profile settings</h3>

            <div class="sets">
                <div class="group">
                    <div class="group-item">
                        <label class="input">
                            <div class="set-name">Public name: </div>
                            <input placeholder="..." value="${this._user.public || ''}" @input="${(e: Event) => this._user.public = (e.target as HTMLInputElement).value}">
                        </label>
                        <div class="set-desc">You must have public name to post or to comment.</div>
                    </div>
                </div>
                <button class="apply" @click="${this.save}">Apply</button>
            </div>
        `;
    }

    save() {
        patchUser(this._user.id, {
            public: this._user.public,
        });
    }
}