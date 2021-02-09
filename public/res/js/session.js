let refreshDelay = 4;
let lastRefresh = 0;

function incRefreshDelay() {
    refreshDelay += 3;
    console.log("refresh delay: " + refreshDelay);
}

function decRefreshDelay() {
    const minDelay = 3;
    refreshDelay -= 1;
    if (refreshDelay < minDelay) {
        refreshDelay = minDelay;
    }
    console.log("refresh delay: " + refreshDelay);
}

function onLoadFunction(timeId, timerId) {
    displayFiles();

    // Update every 1 second
    let x = setInterval(function () {

        const limit = document.getElementById(timeId).value;
        let countDownDate = parseInt(limit);
        let now = Math.round((new Date()).getTime() / 1000);
        let distance = countDownDate - now;

        /* List of files - refresh (not part of session timer) */
        lastRefresh++;
        if (lastRefresh > refreshDelay) {
            displayFiles();
            console.log("last \"displayFiles()\" call: "
                + lastRefresh + " seconds ago");
            lastRefresh = 0;
        }

        // Time calculations
        let minutes = Math.floor(distance / 60);
        let seconds = distance - (minutes * 60);

        if (distance < 0) {
            //finished
            clearInterval(x);
            document.getElementById(timerId).innerHTML = "⚠️⌛️";
            location.reload();
        } else {
            //Display
            document.getElementById(timerId).innerHTML =
                (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
        }
    }, 1000);
}

function prolong(url, btn) {
    btn.style.display = "none";

    const inputUsername = document.getElementById("prolongUsername");
    const inputPswd = document.getElementById("prolongPswd");
    let username = String(inputUsername.value);
    let pswd = String(inputPswd.value);

    if (username.length < 1) {
        username = "?*?*?";
    } else if (pswd.length < 1) {
        pswd = "?*?*?";
    }

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: "POST",
        data: {
            username: username,
            pswd: pswd,
            _token: CSRF_TOKEN
        },
        success: function (data) {
            // data.responseJSON
            btn.style.display = "initial";
            location.reload();
        },
        error: function (data) {
            btn.style.display = "initial";
            location.reload();
        }

    });
}

function logOut(url) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: CSRF_TOKEN
        },
        success: function (data) {
            location.reload();
        },
        error: function (data) {
            location.reload();
        }
    });
}

