;(() => {
// begin scope

fetch('/posts.php', {
    method: 'POST',

    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        from: 0,
        limit: 10
    })
}).then(r => r.json()).then(r => {
    if (!r.data) {
        new notification(
            'Cannot parse the recieved data.'
        )

        console.log(r)
        return
    }

    for (let row of r.data) {
        let postList = document.querySelector('#post-list')
        let post = document.createElement('div')
        post.innerHTML = `
        <div class="post" data-id="${row.id}">
            <div class="title">
                <div class="user"><a href="#">Username</a></div>
                <div class="right">
                    <div class="datetime"> 
                        <div class="date">${row.date} at</div>
                        <div class="time">${row.time}</div>
                    </div>
                    <div class="menu" data-menu><i class="fas fa-caret-down" data-menu></i></div>
                </div>
            </div>
            <div class="data">${row.text}</div>

            <ul class="file-list">
                <li class="file-block">
                    <div class="file-name"> documents</div>
                </li>

                <li class="file-block">
                    <div class="file-name">
                        <i class="fas fa-file"></i>
                        <a href="download.php?id=&name=&type=" download></a>
                    </div>
                </li>
            </ul>
        </div>
        `
        postList.append(post)
    }
})

let list = document.querySelector('#post-list');
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
        <li class="back"><i class="fas fa-caret-left"></i>Back</li>
        <li class="delete"><i class="far fa-trash-alt"></i>Delete</li>
        `

        menu.querySelector('.back').onclick = (e) => {
            menu.closest('.post-options').remove()
            menu = false
            return
        }
        
        button.after(menu)
        let remove = menu.querySelector('.delete')

        remove.addEventListener('click', (e) => {
            let post = menu.closest('.post') 
            let id = post.dataset.id 

            fetch('/remove-post.php', {
                method: 'POST', 
                body: id
            }).then(r => r.text()).then(text => {
                switch (text) {
                case '0':
                    new notification(
                        'The post has been removed'
                    )
                    menu.remove()
                    menu = false
                    document.onclick = null
                    post.remove()
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
        setTimeout(() => menu.classList.remove('scaled'), 0)
        return
    }
})

// end scope 
})()
