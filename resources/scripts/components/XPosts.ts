import XElement from '@/ui/XElement';
import {css, CSSResultGroup, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {repeat} from 'lit/directives/repeat.js';
import PostData from '@/types/post.d';
import { getPosts } from '@/api/post';

@customElement('x-posts')
export default class XPosts extends XElement {
    static styles?: CSSResultGroup = [XElement.styles, css`
        :host {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    `];

    @property({type: Array})
    public posts: PostData[];

    constructor() {
        super();

        this.addEventListener('post:deleted', (e: CustomEvent<PostData>) => {
            this.posts = this.posts.filter(post => post.id !== e.detail.id);
        });
    }

    render() {
        return html`
            ${repeat(this.posts, post => post.id, post => html`<x-post .data=${post}></x-post>`)}
            <x-block><x-action @click="${this.more}" x-text="More"></x-action></x-block>
        `;
    }

    async more() {
        this.posts = this.posts.concat(
            await getPosts({
                before: this.posts[this.posts.length - 1].id,
                limit: 10,
            }),
        );
    }
}