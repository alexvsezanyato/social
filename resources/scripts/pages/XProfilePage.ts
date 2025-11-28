import XElement from '@/ui/XElement';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, state} from 'lit/decorators.js';
import PostData from '@/types/post';
import Modal from '@/components/XModal';
import {getUser} from '@/api/user';
import User from '@/types/user';
import {getPost, getPosts} from '@/api/post';
import PostForm from '@/components/XPostForm';
import states from '@/states';

@customElement('x-profile-page')
export default class XProfilePage extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        .posts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .new-post {
            padding: 8px 10px;
            border-radius: 5px;
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
    `];

    @state()
    private _posts: PostData[];

    @state()
    private _user: User;

    constructor() {
        super();

        const uri = new URL(window.location.href);
        const userId = Number(uri.searchParams.get('id'));
        
        getUser(userId).then(user => {
            this._user = user;
        });

        getPosts({
            authorId: userId,
            limit: 10,
        }).then(posts => {
            this._posts = posts;
        });

        this.addEventListener('post:created', (e: CustomEvent<PostData>) => {
            this.postFormModal.hide();
            this.postForm.clean();
            this._posts = [e.detail, ...this._posts];
        });
    }

    render() {
        return html`
            <h3>Profile</h3>

            ${this._user !== undefined ? html`<x-profile .user="${this._user}"></x-profile>` : '...'}

            <div class="posts-header">
                <h3>Posts</h3>
                ${states.user.id === this._user.id ? html`<button @click="${() => this.postFormModal.show()}" class="new-post">New</button>` : ''}
            </div>

            ${this._posts !== undefined ? html`<x-posts .posts="${this._posts}"></x-posts>` : ''}

            <x-modal class="post-form-modal" x-title="New post" hidden>
                <x-post-form class="post-form" slot="content"></x-post-form>
            </x-modal>
        `;
    }

    get postFormModal(): Modal {
        return this.shadowRoot.querySelector('.post-form-modal');
    }

    get postForm(): PostForm {
        return this.shadowRoot.querySelector('.post-form');
    }
}