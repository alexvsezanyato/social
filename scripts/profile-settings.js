;(function(){
let input = document.querySelectorAll('.sets .input input');
let submit = document.querySelectorAll('.sets .apply');
let description = document.querySelectorAll('.sets .label-description');

for (const dome of description) {
    dome.addEventListener('click', function (e) {
        let target = this.parentNode.querySelector('input');
        target.focus();
        target.setSelectionRange(0, 0);
    });
}

for (const dome of input) {
    dome.addEventListener('input', function (e) {
        let c = this.value.slice(-1);
        let l = this.value.length;
        let v = this.value;
        if (!c.match(/^[a-zA-Z0-9\ ]$/) || l > 20) this.value = v.slice(0, l - 1);
    });
}

document.getElementById('apply-ps').addEventListener('click', function (e) {
    let request = new XMLHttpRequest();

    request.addEventListener('readystatechange', (e) => {
        switch (request.readyState) {
        case 4:
            if (request.status == 200) {
                switch (request.responseText) {
                case '0':
                    if (this.hasAttribute('data-reload')) {
                        window.location = 'home.php';
                    } else {
                        alert('Successfully applied');
                    }
                    break;
                case '1': 
                    alert('Fail to apply');
                    break;
                case '2': 
                    alert('Doesn\'t meet the requirements');
                    break;
                }
            }
            break;
        }
    });

    let i = this.parentNode.querySelector('#public-name');
    request.open('post', '/app/profile-apply.php', true);
    let data = 'public=' + i.value;
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(data);
});
})();
