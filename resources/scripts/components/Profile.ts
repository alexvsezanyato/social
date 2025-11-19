import {CSSResultGroup, LitElement, css, html} from 'lit';
import {customElement, state} from 'lit/decorators.js';
import PostData from './../types/post.d';
import Modal from './Modal';
import {getUser} from '../app/User';
import User from '../types/user';
import {getPosts} from '../app/Post';
import PostForm from './PostForm';

@customElement('x-profile')
export default class Profile extends LitElement {
    static styles?: CSSResultGroup = css`
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

        .posts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .new-post {
            padding: 8px 10px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            background: #009432;
            color: #f7fffa;
            box-shadow: 0 2px 0 0 #006321;
            border: none;
            border-bottom: 1px solid #006321;
            transition: all .1s ease;
        }

        .new-post:hover {
            background: #009e35;
            border-bottom: 1px solid #017d2a;
            box-shadow: 0 2px 0 0 #017d2a;
        }

        .new-post:active {
            transform: translate(0, 3px);
            box-shadow: none;
        }

        .more-posts {
            text-align: center;
        }

        .more-posts .wrapper {
            display: inline-block;
            font-family: Roboto, Arial, Tahoma;
            font-weight: bold;
            text-align: center;
            padding: 8px 10px;
            border-radius: 1.8em;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            background: #fff;
            color: #000;
            border: 1px solid #ddd;

            box-shadow: 
                0 6px 15px 0 #ccc,
                0 1px 0 0 #aaa;

            transition: all .1s ease;
            user-select: none;
        }

        .more-posts .wrapper:active {
            transform: translate(0, 2px);
            box-shadow: 
                0 3px 4px 0 #ddd,
                0 1px 0 0 #aaa;
        }

        .posts-end {
            padding: 10px;
            padding-bottom: 3px;
            font-family: Roboto, Arial, Tahoma;
            font-size: 13px;
            font-weight: bold;
        }
    `;

    @state()
    private _posts: PostData[];

    @state()
    private _user: User;

    constructor() {
        super();
        
        getUser().then(user => {
            this._user = user;
        });

        getPosts().then(posts => {
            this._posts = posts;
        });

        this.addEventListener('post:created', (e: CustomEvent<PostData>) => {
            this.getPostFormModal().hide();
            this.getPostForm().clean();
            this._posts = [e.detail, ...this._posts];
        });
    }

    render() {
        return html`
            <h3>Profile</h3>

            ${this._user !== undefined ? html`<div class="profile-header">
                <div>
                    <div class="title">Name: </div>
                    <div class="value">${this._user.public}</div>
                </div>
                <div>
                    <div class="title">Login: </div>
                    <div class="value">${this._user.login}</div>
                </div>
                <div>
                    <div class="title">Age: </div>
                    <div class="value">${this._user.age}</div>
                </div>
            </div>` : ''}

            ${this._posts !== undefined ? html`<div class="posts">
                <div class="posts-header">
                    <h3>Posts</h3>
                    <button @click="${() => this.getPostFormModal().show()}" class="new-post">New</button>
                </div>

                <x-posts .posts="${this._posts}"></x-posts>
            </div>` : ''}

            <x-modal class="post-form-modal" x-title="New post">
                <x-post-form class="post-form" slot="content"></x-post-form>
            </x-modal>
        `;
    }

    public getPostFormModal(): Modal {
        return this.shadowRoot.querySelector('.post-form-modal');
    }

    public getPostForm(): PostForm {
        return this.shadowRoot.querySelector('.post-form');
    }
}