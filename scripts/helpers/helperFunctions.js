function downloadFile(fileBlob, fileName, fileExtension) {
    var tempElement = document.createElement('a');
    tempElement.href = URL.createObjectURL(fileBlob);
    tempElement.download = fileName + "." + fileExtension;
    tempElement.click();
    tempElement.remove();
}

function readFile(fileInputId, callback) {
    var file = document.getElementById(fileInputId).files[0];

    var reader = new FileReader();
    reader.onload = function (event) {
        var fileContents = event.target.result;
        callback(fileContents);
    };

    reader.readAsText(file);
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setDate(date.getDate() + days);
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; ++i) {
        var cookie = cookies[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
        if (cookie.indexOf(nameEQ) == 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}