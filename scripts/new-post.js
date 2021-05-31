;(() => {
// begin scope

let input = document.getElementById('np-btn')
let inputblock = document.getElementById('np-blockjs')
let inputclose = document.getElementById('np-close')
let post = document.getElementById('np-post')
let textarea = document.getElementById('np-textarea')
let iwindow = document.getElementById('np-window')
let wrapper = document.querySelector('body > .wrapper')
let files = []
let sizeError = false

input.addEventListener('click', (e) => {
    inputblock.style.visibility = 'visible'
    inputblock.style.opacity = '1'
    iwindow.style.transform = 'scale(1)'
    setTimeout(() => textarea.focus(), 100)
})

inputclose.addEventListener('click', (e) => {
    textarea.blur()
    iwindow.style.transform= 'scale(0.94)'
    inputblock.style.opacity = '0'
    inputblock.style.visibility = 'hidden'
})

post.addEventListener('click', (e) => {
    // send post

    if (sizeError) { 
        new notification(
            'Not posted: some data is too large'
        )
        return
    }

    let value = textarea.value
    let request = new XMLHttpRequest()
    let data = new FormData()
    data.append('text', value)
    files.forEach((e, i) => data.append('f' + i, e))

    request.addEventListener('readystatechange', (e) => {
        switch (request.readyState) {
        case 4:
            if (request.status === 200) {
                switch (request.responseText) {
                case '0':
                    iwindow.style.transform= 'scale(0.94)'
                    inputblock.style.opacity = '0'
                    inputblock.style.visibility = 'hidden'
                    setTimeout(() => { location.reload() }, 100)
                    break

                case '1': 
                    new notification(
                        'Not posted. Try again later'
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
                    console.log(request.responseText)
                    break
                }
            }
            break
        }
    })

    // form data сам задает тип контента 
    // в котором также указывается boundary
    // request.setRequestHeader('Content-Type', 'multipart/form-data')

    request.open('post', '/app/create-post.php', true)
    request.send(data)
})

textarea.addEventListener('keydown', function (e) {
    if (e.key === 'Tab') {
        // check writing
        // e.preventDefault()
        // let start = this.selectionStart
        // let end = this.selectionEnd
        // this.value = this.value.substring(0, start) + "    " + this.value.substring(end)
        // this.selectionStart = start + 4
        // this.selectionEnd = this.selectionStart
    }
})

;(() => {
    let options = inputblock.querySelector('.options')
    let filebutton = options.querySelector('.files')    
    let fileinput = options.querySelector('.input-file')
    let filelist = inputblock.querySelector('.file-list')
    let count = null
    let maxcount = 5
    let free = []

    filebutton.addEventListener('click', e => {
        fileinput.click()
        return
    })

    fileinput.addEventListener('change', function (e) {
        files = Array.from(this.files)
        let length = files.length

        if (length > maxcount || !length) { 
            filelist.style.display = "none"
            if (length) new notification(`${maxcount} files maximum`)
            files = []
            fileinput.value = null
            filelist.innerHTML = ""
            count = null
            console.log(files)
            return
        }

        let html = ""
        html += `<li><div class="count">${length} documents to upload</div></li>`

        files.forEach((e, i) => {
            let toolarge = false
            if (e.size > 2 * 1024 * 1024) toolarge = true // > 2MB
            if (toolarge) sizeError = true
            e.id = i

            html += `
            <li class="file-block">
                <div class="file-name">
                <i class="fas fa-file"></i>${toolarge? '[Too large] ' : ''} ${this.files[i].name}
                </div>

                <div class="unpin-file" data-fbid="${i}">
                <i data-unpin="true" class="fas fa-times"></i>
                </div>
            </li>
            `
        })


        if (sizeError) new notification('Some files are too large (> 2MB)')
        filelist.innerHTML = html
        filelist.style.display = 'block'
    })

    filelist.addEventListener('click', e => {
        // to remove file item
        let set = e.target.dataset
        if (!set.unpin && !set.fbid) return

        let t = e.target
        let button = set.unpin? t.closest('.unpin-file') : t

        // id = dataset.fbid
        // filter if doesn't match
        let {fbid: id} = button.dataset
        files = files.filter(f => f.id != id)
        free.push(id)
        console.log(free)

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
    })
})(files)

// end scope
})()
