;let notification = (() => {
    let nots = document.querySelector('#global-notifications')
    let close = nots.querySelector('.close')
    let hide = (e) => e.style.visibility = 'hidden'
    let show = (e) => e.style.visibility = 'visible'
    let list = nots.querySelector('.list')
    show(nots)

    nots.onclick = (e) => {
        if ('close' in e.target.dataset) e.target
            .closest('.notification')
            .remove()
        return
    }

    return function (data, options) {
        let item = document.createElement('li')
        item.className = 'notification'

        item.innerHTML = `
            <div class="message">${data}</div>
            <div class="close" data-close><i class="fas fa-times" data-close></i></div>
        `;
        
        if (list.children.length >= 3) list.firstChild.remove()
        list.append(item)
        setTimeout(() => item.remove(), 8000)
        return
    }
})()
