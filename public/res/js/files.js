window.onbeforeunload = function (event) {
    const hashInputElem = document.getElementById("contentHashValue");
    hashInputElem.value = "onbeforeunload";
};

let serverBusy = false;

function displayFiles() {
    const hashInputElem = document.getElementById("contentHashValue");
    const oldHash = (hashInputElem) ? ("" + hashInputElem.value) : "null";
    const filesElem = document.getElementById('myFiles');
    const storageInfoElem = document.getElementById("userDataStorageInfo");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    if (serverBusy === false) {
        $.ajax({
            url: '/display',
            type: "POST",
            data: {
                _token: CSRF_TOKEN,
                contentHash: oldHash
            },
            beforeSend: function () {
                serverBusy = true;
                hashInputElem.value = "before";
            },
            success: function (text) {
                hashInputElem.value = text.hash;
                if (text.update === true) {
                    console.log("new content");
                    filesElem.innerHTML = text.content;
                    storageInfoElem.innerHTML = text.storageInfo;
                } else {
                    console.log(oldHash + " = " + text.hash);
                }
            },
            error: function (text) {
                hashInputElem.value = "err";
                filesElem.innerHTML = "";
            }
        }).always(function () {
            // po zakończeniu połączenia
            // połączenia zakończone błędem lub sukcesem
            console.log("refresh finished: " + Date.now());
            decRefreshDelay();
            serverBusy = false;
        });
    } else {
        console.log("server busy: " + Date.now());
        incRefreshDelay();
    }
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

function isVisible(elem) {
    const docViewTop = $(window).scrollTop();
    const docViewBottom = docViewTop + $(window).height();

    const elemTop = $(elem).offset().top;
    const elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

let dotsOpened = false;
let openedDotsElem = null;
let clickScrollPos = window.pageYOffset;

function dotsClicked(elem) {
    dotsOpened = true;
    openedDotsElem = elem;
    clickScrollPos = window.pageYOffset;
    //alert(elem.innerHTML);
}

window.onscroll = function () {
    if (dotsOpened === true && openedDotsElem) {
        //const currentScrollPos = window.pageYOffset;
        //const absDiff = Math.abs(clickScrollPos - currentScrollPos);
        if (isVisible(openedDotsElem) === false) {
            $("body").trigger("click");
            dotsOpened = false;
            openedDotsElem = null;
        }
    }
}
