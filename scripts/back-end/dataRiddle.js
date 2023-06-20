loadDataRiddle();

var updateRiddle = document.getElementById("updateRiddle");
var deleteButton = document.getElementById("deleteRiddle");
var exportButton = document.getElementById("exportRiddle");
var riddleId = document.getElementById('metadataIdValue').textContent;
console.log(riddleId);

exportButton.addEventListener('click', async function() {
  const roomId = document.getElementById('metadataIdValue').textContent;
  const language = document.getElementById('metadataLanguageValue').textContent;
  var data = await fetchGet("riddleController", "getRiddleDetails", { language: language, export: blobCallback, id: roomId}, blobCallback);
  downloadFile(data, "all-escape-rooms", "json");
});

updateRiddle.addEventListener('click', function() {
  window.location = 'updateRiddle.html';
});

deleteButton.addEventListener('click', async function() {
  if (confirm('Are you sure you want to delete this riddle?')) {
    var riddles = JSON.parse(sessionStorage.getItem("riddles"));

    var removeDeletedRiddle = riddles.filter(function(item){
      return riddleId != item.id;         
    });

    sessionStorage.setItem("riddles", JSON.stringify(removeDeletedRiddle));

    await fetchPost("riddleController", "deleteRiddle", { id: Number(riddleId) });
    window.location=document.referrer;
  } else {
      return false;
  }
});