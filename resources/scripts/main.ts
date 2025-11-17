import './components/PostFormModal';
import './components/Modal';
import './components/PostForm';

document.addEventListener('click', async (e: MouseEvent) => {
    const target = e.target as HTMLElement;

    if (
        !target.closest('.post .menu .toggler')
        && !target.closest('.post .menu .item.back')
    ) {
        return;
    }

    target.closest('.menu')?.querySelector('.items')?.classList.toggle('hidden');
});

document.addEventListener('click', async (e: MouseEvent) => {
    const target = e.target as HTMLElement;

    if (!target.closest('.post .menu .item.delete')) {
        return;
    }

    const item = target.closest('.item.delete') as HTMLElement;

    const responseBody = await fetch(item.dataset.href as string).then(response => {
        return response.json();
    });

    if (responseBody.status === 'success') {
        target.closest('.post')?.remove();
    } else if (responseBody.error) {
        console.error(responseBody.error);
    }
});

document.addEventListener('click', async (e: MouseEvent) => {
    const target = e.target as HTMLElement;

    if (target.closest('.post .new-comment .sending') === null) {
        return;
    }

    const post = target.closest('.post') as HTMLElement;
    const input = target.closest('.new-comment')?.querySelector('input') as HTMLInputElement;
    const comments = post.querySelector('.comments') as HTMLElement;

    const formData = new FormData();
    formData.append('post_id', post.dataset.id as string);
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
});