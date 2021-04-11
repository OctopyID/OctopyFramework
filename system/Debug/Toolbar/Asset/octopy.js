document.addEventListener('DOMContentLoaded', loadDoc, false);

function loadDoc(time) {
    if (isNaN(time)) {
        time = document.getElementById("toolbar_loader").getAttribute("data-time");
        localStorage.setItem('toolbar-time', time);
    }

    localStorage.setItem('toolbar-time-new', time);

    const url = "{{ ROUTE }}" + time;

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let toolbar = document.getElementById("toolbarContainer");
            if (! toolbar) {
                toolbar = document.createElement('div');
                toolbar.setAttribute('id', 'toolbarContainer');
                document.body.appendChild(toolbar);
            }
            // copy for easier manipulation
            let responseText = this.responseText;
            // get csp blocked parts
            // the style block is the first and starts at 0
            {
                let PosBeg = responseText.indexOf('>', responseText.indexOf('<style')) + 1;
                let PosEnd = responseText.indexOf('</style>', PosBeg);
                document.getElementById('toolbar_dynamic_style').innerHTML = responseText.substr(PosBeg, PosEnd - PosBeg);
                responseText = responseText.substr(PosEnd + 8);
            }
            // the script block starts right after style blocks ended
            {
                let PosBeg = responseText.indexOf('>', responseText.indexOf('<script')) + 1;
                let PosEnd = responseText.indexOf('</script>');
                document.getElementById('toolbar_dynamic_script').innerHTML = responseText.substr(PosBeg, PosEnd - PosBeg);
                responseText = responseText.substr(PosEnd + 9);
            }
            // check for last style block
            {
                let PosBeg = responseText.indexOf('>', responseText.lastIndexOf('<style')) + 1;
                let PosEnd = responseText.indexOf('</style>', PosBeg);
                document.getElementById('toolbar_dynamic_style').innerHTML += responseText.substr(PosBeg, PosEnd - PosBeg);
                responseText = responseText.substr(0, PosBeg + 8);
            }
            toolbar.innerHTML = this.responseText;
            if (typeof OctopyToolbar === 'object') {
                OctopyToolbar.init();
            }
        } else if (this.readyState === 4 && this.status === 404) {
        }
    };

    xhttp.open("GET", url, true);
    xhttp.send();
}

// Track all AJAX requests
let oldXHR;
if (window.ActiveXObject) {
    oldXHR = new ActiveXObject('Microsoft.XMLHTTP');
} else {
    oldXHR = window.XMLHttpRequest;
}

function newXHR() {
    const realXHR = new oldXHR();
    realXHR.addEventListener("readystatechange", function () {
        // Only success responses and URLs that do not contains "toolbar_time" are tracked
        if (realXHR.readyState === 4 && realXHR.status.toString()[0] === '2' && realXHR.responseURL.indexOf('toolbar_time') === -1) {
            const toolbarTime = realXHR.getResponseHeader('Debugbar-Time');
            if (toolbarTime) {
                const h2 = document.querySelector('#octopy-history > h2');
                h2.innerHTML = 'History <small>You have new debug data.</small> <button onclick="loadDoc(' + toolbarTime + ')">Update</button>';
                const badge = document.querySelector('a[data-tab="octopy-history"] > span > .badge');
                badge.className += ' active';
            }
        }
    }, false);
    return realXHR;
}

window.XMLHttpRequest = newXHR;
