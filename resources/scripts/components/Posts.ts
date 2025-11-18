import {LitElement, html} from 'lit';
import {customElement, state} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import PostData from './../types/post.d';
import {getPosts} from './../app/Post';

@customElement('x-posts')
export default class Posts extends LitElement {
    @state()
    private _posts: PostData[] = [];

    constructor() {
        super();
        getPosts().then(posts => this._posts = posts);

        this.addEventListener('post:deleted', (e: CustomEvent<PostData>) => {
            this._posts = this._posts.filter(post => post.id !== e.detail.id);
        });
    }

    render() {
        return html`<div class="posts">
            ${map(this._posts, post => html`<x-post .data=${post}></x-post>`)}
        </div>`;
    }
}