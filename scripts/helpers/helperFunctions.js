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