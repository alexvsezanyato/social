import XElement from '@/ui/XElement';
import {html} from 'lit';
import {customElement, state} from 'lit/decorators.js';
import PostData from '@/types/post.d';
import {getRecommendedPosts} from '@/api/recommended-post';

@customElement('x-index-page')
export default class XIndexPage extends XElement {
    @state()
    private _posts: PostData[];

    constructor() {
        super();

        getRecommendedPosts({
            limit: 10,
        }).then(posts => {
            this._posts = posts;
        });
    }

    render() {
        return html`
            <h3>News</h3>
            ${this._posts !== undefined ? html`<x-posts .posts="${this._posts}"></x-posts>` : ''}
        `;
    }
}