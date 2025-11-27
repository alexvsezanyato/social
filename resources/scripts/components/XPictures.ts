import XElement from '@/ui/XElement';
import {CSSResultGroup, css, html} from 'lit';
import {customElement, property} from 'lit/decorators.js';
import {map} from 'lit/directives/map.js';
import IPicture from '@/types/picture';

@customElement('x-pictures')
export default class XPictures extends XElement {
    static styles: CSSResultGroup = [XElement.styles, css`
        :host {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 5px;
            padding: 10px;
        }

        .item {
            cursor: pointer;
            flex-grow: 1;
            max-height: 100px;
            min-height: 30px;
            min-width: 20%;
            background-size: contain;
            border-radius: 5px;
            min-height: 70px;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            align-items: flex-start;
            padding: 5px;
            border: 1px solid #ddd;
        }
    `];

    @property({attribute: false})
    public data: IPicture[];

    render() {
        return map(this.data, picture => html`<a href="/pictures/${picture.id}/download">
            <div class="item" style="background: url('/pictures/${picture.id}/download') center / cover no-repeat"></div>
        </a>`);
    }
}