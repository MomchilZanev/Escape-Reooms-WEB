loadDataRiddle();

var updateRiddle = document.getElementById("updateRiddle");
var deleteButton = document.getElementById("deleteRiddle");
var exportButton = document.getElementById("exportRiddle");
var riddleId = document.getElementById('metadataIdValue').textContent;
console.log(riddleId);

exportButton.addEventListener('click', async function () {
  const roomId = document.getElementById('metadataIdValue').textContent;
  const language = document.getElementById('metadataLanguageValue').textContent;
  var data = await fetchGet("riddleController", "getRiddleDetails", { language: language, export: blobCallback, id: roomId }, blobCallback);
  downloadFile(data, "riddle", "json");
});

updateRiddle.addEventListener('click', function () {
  window.location = 'updateRiddle.html';
});

deleteButton.addEventListener('click', async function () {
  if (confirm('Are you sure you want to delete this riddle?')) {

    var room = JSON.parse(sessionStorage.getItem("objectRoom"));
    room.riddles = room.riddles.filter(function (item) {
      return riddleId != item.id;
    });
    sessionStorage.setItem("objectRoom", JSON.stringify(room));

    await fetchPost("riddleController", "deleteRiddle", { id: Number(riddleId) });
    window.location = document.referrer;
  } else {
    return false;
  }
});