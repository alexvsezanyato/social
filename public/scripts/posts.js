;(() => {
// begin scope

function getCommentHtml(authorName, text) {
    return `<div class="comment">
        <div class="author">
            <div class="icon"><i class="fa-solid fa-user"></i></div>
            <div class="name">${authorName}</div>
        </div>
        <div class="text">${text}</div>
    </div>`
}

let list = document.querySelector('#post-list')
let more
let limit = 3

let fetchPosts = (from, limit) => {
    fetch('/api/posts', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},

        body: JSON.stringify({
            'from': from,
            'limit': limit
        })
    })
    .then(r => r.json())
    .then(r => printPosts(r))
}

let printPosts = r => {
    if (!r.posts || !r.user) {
        new notification(
            'Cannot parse the recieved data.'
        )
    }

    let minId = r.posts[0]?.id

    for (let post of r.posts) {
        let postBlock = document.createElement('div')
        postBlock.className = 'post'
        postBlock.dataset.id = post.id
        let docsHtml = ``
        let picsHtml = ``
        let commentsHtml = ``
        if (minId > post.id) minId = Number(post.id)


        for (let pic of post.pics) {
            let background = 
                `url('http://${document.domain}/uploads/pictures/${pic.source}')` + ` ` +
                `center / cover no-repeat`

            picsHtml += ` 
            <li class="image" style="
                background: ${background};
            ">

            </li>
            `
        }

        if (post.pics.length) picsHtml = `
        <ul class="pic-list">
            ${picsHtml}
        </ul>
        `

        for (let doc of post.docs) {
            docsHtml += ` 
            <li class="file-block">
                <div class="file-name">
                    <i class="fas fa-file"></i>
                    <a 
                        href="/document/download?id=${doc.source}&name=${doc.name}&type=${doc.mime}" 
                        download
                    >${doc.name}</a>
                </div>
            </li>
            `
        }

        if (post.docs.length) docsHtml = `
        <ul class="file-list">
            <li class="file-block file-list-header">
                <div class="file-name">${post.docs.length} documents</div>
            </li>
            ${docsHtml}
        </ul>
        `

        commentsHtml += ` 
        <li class="new-comment">
            <div class="author"><i class="fa-solid fa-user"></i></div>
            <input name="comment" class="input" type="text" placeholder="Comment">
            <div class="sending"><div class="send-comment"><i class="fas fa-solid fa-paper-plane"></i></div></div>
        </li>
        `

        for (let comment of post.comments) {
            commentsHtml += '<li>' + getCommentHtml(comment.author.public, comment.text) + '</li>'
        }

        commentsHtml = `
        <ul class="comment-list">
            ${commentsHtml}
        </ul>
        `

        postBlock.innerHTML = `
        <div class="title">
            <div class="user">
                <a href="/home">${r.user.public}</a>
            </div>

            <div class="right">
                <div class="datetime"> 
                    <div class="date">${post.date} at</div>
                    <div class="time">${post.time}</div>
                </div>

                <div class="menu" data-menu>
                    <i class="fas fa-caret-down" data-menu></i>
                </div>
            </div>
        </div>

        <div class="data">${post.text}</div>
        ${picsHtml}
        ${docsHtml}
        ${commentsHtml}
        `
        
        postBlock.innerHTML = `<div class="wrapper">${postBlock.innerHTML}</div>`
        list.append(postBlock)
    }

    if (r.posts.length < limit) {
        let end = document.createElement('div')
        end.className = 'notice posts-end'
        end.innerHTML = 'The end'

        if (r.posts.length) {
            if (more) more.remove()
            more = false
        }

        if (!list.querySelector('.post')) {
            list.innerHTML = `
                <hr class="hr">
                ${list.innerHTML}
            `
        }

        if (more) list.replaceChild(end, more)
        else list.append(end)
        more = false
    }
    else {
        if (more) more.remove()

        else {
            more = document.createElement('div')
            more.className = 'more-posts'
            more.innerHTML = '<div class="wrapper">Show more</div>'
        }

        let moreButton = more.querySelector('.wrapper')
        moreButton.onclick = e => fetchPosts(minId - 1, limit)
        list.append(more)
    }

    list.addEventListener('click', (e) => {
        if (e.target.closest('.send-comment')) {
            const post = e.target.closest('.post')
            const commentInput = post.querySelector('.new-comment').querySelector('.input')
            const commentText = commentInput.value.trim()

            if (!commentText.length) {
                new notification(
                    'Comment cannot be empty.'
                )
                return
            }

            const data = new FormData()
            data.append('post_id', post.dataset.id)
            data.append('text', commentText)

            fetch('/api/post-comment/create', {
                method: 'POST',
                body: data,
            }).then(r => r.json()).then(response => {
                if (response.status === 'success') {
                    const commentList = post.querySelector('.comment-list')
                    commentList.innerHTML += '<li>' + getCommentHtml(r.user.public, commentText) + '</li>'
                    commentInput.value = ''
                } else {
                    new notification(
                        'Failed to add comment. Please try again.'
                    )
                }
            })
        }
    })
}

fetchPosts(0, limit)
let menu

let hideMenu = (e) => {
    if (!e.target.closest('[data-options]') && menu) {
        menu.remove()
        menu = false
        document.onclick = null
        return
    }
}

list.addEventListener('click', (e) => {
    if ('menu' in e.target.dataset) {
        let button = e
            .target
            .closest('.menu')

        if (menu) {
            menu.remove()
            menu = false
            return
        }

        menu = document.createElement('ul')
        menu.className = 'post-options scaled'
        menu.dataset.options = 'options'

        menu.innerHTML = `
        <li class="option back"><i class="fas fa-caret-left"></i>Back</li>
        <li class="option delete"><i class="far fa-trash-alt"></i>Delete</li>
        `

        menu.querySelector('.back').onclick = (e) => {
            menu.closest('.post-options').remove()
            menu = false
            return
        }
        
        button.append(menu)
        let remove = menu.querySelector('.delete')

        remove.addEventListener('click', (e) => {
            let post = menu.closest('.post') 
            let id = post.dataset.id 

            fetch('/api/post/remove', {
                method: 'POST', 
                body: id
            }).then(r => r.text()).then(text => {
                switch (text) {
                case '0':
                    new notification(
                        // remove post
                        'The post has been removed'
                    )

                    menu.remove()
                    menu = false
                    document.onclick = null
                    post.remove()

                    if (!list.querySelector('.post')) {
                        list.innerHTML = `
                            <hr class="hr">
                            ${list.innerHTML}
                        `
                    }
                    break

                default: 
                    new notification(
                        'Cannot handle answer. Server says: ' + text
                    )

                    console.log(text)
                    break
                }
            })
        })

        e.stopPropagation()
        document.onclick = hideMenu
        setTimeout(() => menu.classList.remove('scaled'))
        return
    }
})

// end scope 
})()
