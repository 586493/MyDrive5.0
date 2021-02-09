function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

const infoElem = document.getElementById("fileActionID");
const okElem = document.getElementById("fileActionOkID");
const errElem = document.getElementById("fileActionErrID");

function showInfo(ok, err) {
    infoElem.style.display = "none";
    okElem.style.display = "none";
    errElem.style.display = "none";
    if (ok) {
        infoElem.style.display = "initial";
        okElem.style.display = "";
    } else if (err) {
        infoElem.style.display = "initial";
        errElem.style.display = "";
    }
    setTimeout(function () {
        infoElem.style.display = "none";
        okElem.style.display = "none";
        errElem.style.display = "none";
    }, 3900);
}

function okInfo(text) {
    okElem.innerText = text;
    showInfo(true, false);
}

function errInfo(text) {
    errElem.innerText = text;
    showInfo(false, true);
}

function downloadFile(path, name, okInfoTxt, errInfoTxt, url) {
    const xhttp = new XMLHttpRequest();
    const formData = new FormData();
    formData.append("path", path);
    formData.append("_token", getCsrfToken());
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
            if (xhttp.status === 200) {
                const a = document.createElement('a');
                a.href = window.URL.createObjectURL(xhttp.response);
                a.download = name;
                a.style.display = "none";
                document.body.appendChild(a);
                a.click();
                okInfo(okInfoTxt);
            } else {
                errInfo(errInfoTxt);
            }
        }
    };
    xhttp.open("POST", url, true);
    xhttp.responseType = 'blob';
    xhttp.send(formData);
}

function renameFile(url, errInfoTxt) {
    const path = document.getElementById("renamePathInput").value;
    const newName = document.getElementById("renameNewNameInput").value
        + document.getElementById("renameExtInput").value;
    if (path && newName && path.length >= 1 && newName.length >= 1) {
        $("#myModal").modal('hide');
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: getCsrfToken(),
                newName: newName,
                path: path
            },
            success: function (response) {
                displayFiles();
                okInfo(response.info);
            },
            error: function (response) {
                displayFiles();
                errInfo(errInfoTxt);
            }
        });
    }
}

function deleteFile(url, errInfoTxt) {
    const path = document.getElementById("pathToDelete").value;
    if (path && path.length >= 1) {
        $("#myModal").modal('hide');
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: getCsrfToken(),
                path: path
            },
            success: function (response) {
                displayFiles();
                okInfo(response.info);
            },
            error: function (response) {
                displayFiles();
                errInfo(errInfoTxt);
            }
        });
    }
}

function openDeleteModal(path, name) {
    document.getElementById("deleteFileLabel").value = name;
    document.getElementById("pathToDelete").value = path;
    showModal(4)
}

function openShareModal(path, name) {
    document.getElementById("shareFileLabel").value = name;
    document.getElementById("shareFileUsername").value = "";
    document.getElementById("pathToShare").value = path;
    showModal(5)
}

function shareFile(url, errInfoTxt) {
    const path = document.getElementById("pathToShare").value;
    const username = document.getElementById("shareFileUsername").value;
    if (path && path.length >= 1 && username && username.length >= 1) {
        $("#myModal").modal('hide');
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: getCsrfToken(),
                username: username,
                path: path
            },
            success: function (response) {
                displayFiles();
                okInfo(response.info);
            },
            error: function (response) {
                displayFiles();
                errInfo(errInfoTxt);
            }
        });
    }
}

function newNameChangedListener(url, input) {
    let typingTimer; //timer identifier
    let doneTypingInterval = 1500;  //ms
    input.addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        if (input.value) {
            typingTimer = setTimeout(() => {
                const outputElem = document.getElementById("validatedNewName");
                const progress = document.getElementById("validationProgress");
                progress.style.display = "";
                let name = document.getElementById("renameNewNameInput").value;
                if (name) {
                    name = name + document.getElementById("renameExtInput").value;
                    const path = document.getElementById("renamePathInput").value;
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: getCsrfToken(),
                            name: name,
                            path: path
                        },
                        success: function (response) {
                            outputElem.value = response.text;
                            progress.style.display = "none";
                        },
                        error: function (response) {
                            outputElem.value = "";
                            progress.style.display = "none";
                        }
                    });
                } else {
                    outputElem.value = "";
                    progress.style.display = "none";
                }
            }, doneTypingInterval);
        }
    });
}

function openRename(path, fullName, name, extension) {
    document.getElementById("renamePathInput").value = path;
    document.getElementById("renameOldNameInput").value = fullName;
    document.getElementById("renameNewNameInput").placeholder = name;
    document.getElementById("renameNewNameInput").value = "";
    document.getElementById("renameExtInput").value = extension;
    document.getElementById("validatedNewName").value = "";
    showModal(3);
}
