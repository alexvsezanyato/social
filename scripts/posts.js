function $(selector) {
    let data = document.querySelectorAll(selector);
    return data;
}

for (data of $('.post .data')) {
    data.innerHTML = data.innerHTML
        // .replace(/\{b:s\}/g, '<b>')
        // .replace(/\{b:e\}/g, '</b>')
        // .replace(/\r\n|\r|\n/g, '<br/>')
        ;
}
