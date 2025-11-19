import {LitElement, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {repeat} from 'lit/directives/repeat.js';
import PostData from './../types/post.d';

@customElement('x-posts')
export default class Posts extends LitElement {
    @property({type: Array})
    public posts: PostData[];

    constructor() {
        super();

        this.addEventListener('post:deleted', (e: CustomEvent<PostData>) => {
            this.posts = this.posts.filter(post => post.id !== e.detail.id);
        });
    }

    render() {
        return html`<div class="posts">
            ${repeat(this.posts, post => post.id, post => html`<x-post .data=${post}></x-post>`)}
        </div>`;
    }
}