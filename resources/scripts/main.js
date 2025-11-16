{
    let user = null;

    window.globals = {
        getUser: async function(id) {
            if (user !== null) {
                return user;
            }

            const uri = new URL('/api/profile/get', window.location.origin);

            if (id) {
                uri.searchParams.set('id', id);
            }

            try {
                const response = await fetch(uri);
                user = response.json();
            } catch (e) {
                console.error('Failed to fetch user data', e);
                return null;
            }

            return user;
        },
    };
}

customElements.define('x-modal', class extends HTMLElement {
    constructor() {
        const shadow = this.attachShadow({mode: 'open'});
        const template = document.querySelector('#tpl-modal');
        shadow.appendChild(template.content.cloneNode(true));
    }
});

document.addEventListener('click', async e => {
    if (e.target.closest('.post .menu .toggler') || e.target.closest('.post .menu .item.back')) {
        const menu = e.target.closest('.menu');
        const items = menu.querySelector('.items');
        items.classList.toggle('hidden');
    } else if (e.target.closest('.post .menu .item.delete')) {
        const item = e.target.closest('.item.delete');

        const responseBody = await fetch(item.dataset.href).then(response => {
            return response.json();
        });

        if (responseBody.status === 'success') {
            const post = e.target.closest('.post');
            post.remove();
        } else {
            if (responseBody.error) {
                console.error(responseBody.error);
            }
        }
    } else if (e.target.closest('.post .new-comment .sending')) {
        const post = e.target.closest('.post');
        const input = e.target.closest('.new-comment').querySelector('input');
        const comments = post.querySelector('.comments');

        const formData = new FormData();
        formData.append('post_id', post.dataset.id);
        formData.append('text', input.value);

        const responseBody = await fetch('/api/post-comment/create', {
            method: 'POST',
            body: formData,
        }).then(response => {
            return response.json();
        });

        const uri = new URL('/post-comment/index', window.location.origin);
        uri.searchParams.append('id', responseBody.comment_id);

        const commentHtml = await fetch(uri).then(response => {
            return response.text();
        });

        const template = document.createElement('template');
        template.innerHTML = commentHtml;
        comments.append(template.content);
    }
});