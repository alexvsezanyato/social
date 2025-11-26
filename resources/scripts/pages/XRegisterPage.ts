import XElement from '@/ui/XElement';
import axios from 'axios';
import {CSSResultGroup, LitElement, css, html} from 'lit';
import {customElement} from 'lit/decorators.js';

@customElement('x-register-page')
export default class XRegisterPage extends XElement {
    static styles?: CSSResultGroup = [XElement.styles, css`
        :host {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: 0 15px 50px 0 #e3e3e3;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
            font-size: 20px;
        }
        .form .title {
            font-weight: bold;
        }
        .form input, .form .submit {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            font-size: 13px;
            cursor: pointer;
            font-family: "Times New Roman", Times, serif;
            font-size: 16px;
        }
        .form input:hover {
            box-shadow: 0 0 0 1px #ddd;
        }
        .form input:focus {
            outline: none;
            border: 1px solid #aaa;
            box-shadow: 0 0 0 1px #aaa;
        }
        .form .submit {
            background: #eee;
            box-shadow: 0 2px 0 0 #d0d0d0;
            border: 1px solid #e8e8e8;
            transition: all .1s ease;
        }
        .form .submit:hover {
            background: #e6e6e6;
            box-shadow: 0 2px 0 0 #bbb;
        }
        .form .submit:active {
            transform: translate(0, 3px);
            box-shadow: none;
        }
        .form input::placeholder {
            color: #888;
        }
    `];

    render() {
        return html`
            <div class="form"> 
                <div class="title">Register</div> 
                <input name="login" required autocomplete="off" type="text" placeholder="Login">
                <input name="password" required autocomplete="new-password" type="password" placeholder="Password">
                <input name="password-repeat" required autocomplete="new-password" type="password" placeholder="Repeat password">
                <input name="age" required type="text" placeholder="Age">
                <button class="submit" @click="${this.submit}">Submit</button>
            </div>
            <div>Or <a class="link" href="/auth/login">login</a></div>
        `;
    }

    submit() {
        axios.post('/api/auth/register', {
            login: (this.shadowRoot.querySelector('[name="login"]') as HTMLInputElement).value,
            password: (this.shadowRoot.querySelector('[name="password"]') as HTMLInputElement).value,
            prepeat: (this.shadowRoot.querySelector('[name="password-repeat"]') as HTMLInputElement).value,
            age: (this.shadowRoot.querySelector('[name="age"]') as HTMLInputElement).value,
        }).then(() => {
            window.location.href = '/auth/login';
        });
    }
}