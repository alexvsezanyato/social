;(() => {
// begin scope

let openbtn = document.querySelector('#np-btn')
let modal = document.querySelector('#np-modal')
let wrapper = modal.querySelector('.wrapper')

let closebtn = modal.querySelector('.close')
let send = modal.querySelector('.submit')
let input = modal.querySelector('.input')
let iwindow = modal.querySelector('.window')

let files = []
let sizeError = false
modal.onscroll = (e) => e.preventDefault();

openbtn.onclick = (e) => {
    document.body.style.overflowY = 'hidden'
    modal.style.overflowY = 'hidden'

    wrapper.style.visibility = 'visible'
    modal.style.visibility = 'visible'
    wrapper.style.opacity = '1'
    iwindow.style.transform = 'scale(1)'
    setTimeout(() => input.focus(), 100)
}

closebtn.onclick = (e) => {
    input.blur()
    iwindow.style.transform = 'scale(0.94)'
    wrapper.style.opacity = '0'
    modal.style.visibility = 'hidden'
    wrapper.style.visibility = 'hidden'

    modal.style.overflowY = 'hidden'
    document.body.style.overflowY = 'scroll'
}

let handleRequest = (e) => {
    let r = e.target
    let state = r.readyState
    let status = r.status
    let body = r.responseText

    if (state === 4) {
        if (status === 200) switch (body) {
            case '0':
                iwindow.style.transform = 'scale(0.94)'
                modal.style.opacity = '0'
                modal.style.visibility = 'hidden'
                setTimeout(() => location.reload(), 100)
                break

            case '1': 
                new notification(
                    'Notd. Try again later'
                )
                break

            case '2': 
                new notification(
                    'Does not meet the requirements'
                )
                break

            case '3':
                new notification(
                    'Too short message (< 4)'
                )
                break

            case '4':
                new notification(
                    'Too much files (> 5)'
                )
                break

            case '6':
                new notification(
                    'Some files have too long names (> 64)'
                )
                break

            default: 
                new notification(
                    'Something went wrong'
                )
                console.log(body)
                break
        }
    }
}


send.onclick = (e) => {
    if (sizeError) { 
        new notification(
            'Notd: some data is too large'
        )
        return
    }

    // send post
    let value = input.value
    let request = new XMLHttpRequest()
    let data = new FormData()
    data.append('text', value)
    files.forEach((e, i) => data.append('f' + i, e))

    request.onreadystatechange = handleRequest
    request.open('post', '/app/create-post.php', true)
    request.send(data)

    // form data сам задает тип контента 
    // в котором также указывается boundary
    // request.setRequestHeader('Content-Type', 'multipart/form-data')
}

;(() => {
    let options = modal.querySelector('.options')
    let filebutton = options.querySelector('.files')    
    let fileinput = options.querySelector('.input-file')
    let filelist = modal.querySelector('.file-list')
    let count = null
    let maxcount = 5
    // let free = []

    function unselectFiles() {
        filelist.style.display = "none"
        files = []
        fileinput.value = null
        filelist.innerHTML = ""
        count = null
        return
    }

    function printFiles(files) {
        let length = files.length
        let sizeError = false
        let html = ""

        html += `
        <li class="item">
            <div class="count left">
                ${length} documents to upload
            </div>
            
            <div class="right btn-list">
                <i class="remove fas fa-trash-alt"></i>
                <i class="add fas fa-plus-square"></i>
            </div>
        </li>
        `

        files.forEach((e, i) => {
            let toolarge = false
            if (e.size > 2 * 1024 * 1024) toolarge = true // > 2MB
            if (toolarge) sizeError = true
            e.id = i

            html += `
            <li class="item file-block">
                <div class="left file-name">
                <i class="fas fa-file"></i>${toolarge? '[Too large] ' : ''} ${e.name}
                </div>

                <div class="right unpin-file" data-fbid="${i}">
                <i data-unpin="true" class="fas fa-times"></i>
                </div>
            </li>
            `
        })

        filelist.innerHTML = html
        filelist.style.display = 'block'
        let moreBtn = filelist.querySelector('.add')
        let removeBtn = filelist.querySelector('.remove')

        moreBtn.onclick = () => {
            fileinput.click() 
            return
        }

        removeBtn.onclick = () => {
            unselectFiles()
        }

        if (sizeError) { 
            new notification(
                'Some files are too large (> 2MB)'
            )
        }
    }

    filebutton.onclick = e => {
        files = []
        fileinput.click()
        return
    }

    fileinput.onchange = e => {
        files = Array.from(e.target.files).concat(files)
        let length = files.length

        if (length > maxcount || !length) { 
            // if nothing is selected 
            // then file list 
            // shouldn't be visible

            if (length) {
                new notification(
                    `${maxcount} files maximum`
                )
            }

            return
        }

        printFiles(files)
        return
    }

    filelist.onclick = e => {
        // to remove file item
        let set = e.target.dataset
        if (!set.unpin && !set.fbid) return

        let t = e.target
        let button = set.unpin? t.closest('.unpin-file') : t

        // id = dataset.fbid
        // filter if doesn't match
        let {fbid: id} = button.dataset
        files = files.filter(f => f.id != id)
        // free.push(id)

        let block = button.closest('.file-block')
        block.remove()
        if (!count) count = filelist.querySelector('.count')
        count.textContent = `${files.length} documents to upload`

        if (!files.length) {
            filelist.style.display = 'none'
            files = []
            fileinput.value = null
            filelist.innerHTML = '' 
            count.remove()
            count = null
            return
        }

        files.forEach((e, i) => {
            sizeError = false
            if (e.size > 2 * 1024 * 1024) sizeError = true // > 2MB
            return
        })
    }
})(files)

// end scope
})()
