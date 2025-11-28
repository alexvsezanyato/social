import XElement from '@/ui/XElement';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {repeat} from 'lit/directives/repeat.js';
import IPostComment from '@/types/post-comment';
import states from '@/states';

@customElement('x-post-comments')
export default class XPostComments extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host > *::after {
            content: '';
            display: block;
            width: calc(100% - 20px);
            margin: 0 10px;
            height: 1px;
            border-bottom: 1px dashed #ddd;
        }

        :host > *:last-child::after {
            content: none;
        }
    `];

    @property({attribute: false})
    public data: IPostComment[];

    render() {
        return repeat(this.data, comment => comment.id, comment => html`<div>
            <x-post-comment .data="${comment}" ?x-readonly="${states.user.id !== comment.author.id}"></x-post-comment>
        </div>`);
    }
}