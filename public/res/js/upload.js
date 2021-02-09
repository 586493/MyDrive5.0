function getSizeString(size) {
    if (size < 3 * 1024) {
        return "" + size + " B";
    }
    if (size < 3 * 1024 * 1024) {
        return "" + Math.ceil(size / 1024.0) + " KB";
    }
    if (size < 3 * 1024 * 1024 * 1024) {
        return "" + Math.ceil(size / (1024.0 * 1024.0)) + " MB";
    } else {
        alert("Zbyt duży plik!");
        return "⚠️";
    }
}

function fileSelected(limit) {
    const file = document.getElementById('fileToUpload').files[0];
    document.getElementById("fileName").value = file.name;

    document.getElementById("submitFile").disabled = (file.size > limit);
    document.getElementById("submitFile").style.cursor = (file.size > limit) ? "not-allowed" : "pointer";
    document.getElementById("submitFile").style.opacity = (file.size > limit) ? "0.10" : "1.0";

    document.getElementById("fileSize").value = getSizeString(file.size);

    document.getElementById("uploadSuccess").style.display = "none";
    document.getElementById("uploadError").style.display = "none";

    document.getElementById("progressBar").style.width = "0%";
}

function uploadFile(url) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    const file = document.getElementById("fileToUpload").files[0];
    const formData = new FormData();
    formData.append("fileToUpload", file);
    formData.append("_token", CSRF_TOKEN);
    const ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (this.readyState === 4) {

            const rText = (String(this.responseText));
            document.getElementById("uploadSuccess").style.display = "none";
            document.getElementById("uploadError").style.display = "none";
            if (this.status === 200) {
                displayFiles();
                document.getElementById("uploadSuccess").style.display = "block";
                document.getElementById("uploadSuccess").innerHTML = rText;
            } else {
                document.getElementById("uploadError").style.display = "block";
                document.getElementById("uploadError").innerHTML = rText;
            }

            document.getElementById("fileToUpload").value = "";
            document.getElementById("fileToUpload").disabled = false;
            document.getElementById("fileToUpload").style.cursor = "pointer";

            document.getElementById("fileName").value = "";
            document.getElementById("fileSize").value = "";

            document.getElementById("chooseFileToUpload").style.opacity = "1.0";
            document.getElementById("chooseFileToUpload").style.cursor = "pointer";
            document.getElementById("chooseFileToUpload2").style.cursor = "pointer";
            document.getElementById("chooseFileToUpload3").style.cursor = "pointer";
        }
    };
    ajax.upload.addEventListener("progress", progressHandler, false);

    //to prevent from multiple uploads at the same time
    document.getElementById("submitFile").disabled = true;
    document.getElementById("submitFile").style.opacity = "0.10";
    document.getElementById("submitFile").style.cursor = "not-allowed";

    document.getElementById("progressBar").style.width = "0%";

    document.getElementById("fileToUpload").disabled = true;
    document.getElementById("fileToUpload").style.cursor = "not-allowed";

    document.getElementById("chooseFileToUpload").style.opacity = "0.10";
    document.getElementById("chooseFileToUpload").style.cursor = "not-allowed";
    document.getElementById("chooseFileToUpload2").style.cursor = "not-allowed";
    document.getElementById("chooseFileToUpload3").style.cursor = "not-allowed";

    ajax.open("POST", url, true);
    ajax.send(formData);
}

function progressHandler(event) {
    const width = ((1.0 * event.loaded) / (1.0 * event.total)) * 100.0;
    document.getElementById("progressBar").style.width = (width + '%');
}

String.prototype.replaceAt = function (index, replacement) {
    return this.substr(0, index) + replacement + this.substr(index + replacement.length);
};

function removeIllegalChars(newFileNameInput) {
    var str = newFileNameInput.value;
    if (str !== null && str.length > 0) {
        for (var s = 0; s < str.length; s++) {
            if (str.charAt(s) === "&" ||
                str.charAt(s) === "<" ||
                str.charAt(s) === ">" ||
                str.charAt(s) === ":" ||
                str.charAt(s) === "\"" ||
                str.charAt(s) === "/" ||
                str.charAt(s) === "\\" ||
                str.charAt(s) === "|" ||
                str.charAt(s) === "?" ||
                str.charAt(s) === "*" ||
                str.charAt(s) === "'" ||
                str.charAt(s) === ";" ||
                str.charAt(s) === "." ||
                str.charAt(s) === "%" ||
                str.charAt(s) === "{" ||
                str.charAt(s) === "}") {
                str = str.replaceAt(s, '_');
                //alert("Illegal character!");
            }
        }
        newFileNameInput.value = str;
    }
}


