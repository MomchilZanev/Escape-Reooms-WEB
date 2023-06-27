loadDataRoom();

var updateButton = document.getElementById("updateRoom");
var deleteButton = document.getElementById("deleteRoom");
var exportButton = document.getElementById("exportRoom");
var roomId = document.getElementById('metadataIdValue').textContent;

exportButton.addEventListener('click', async function() {  
  const language = document.getElementById('metadataLanguageValue').textContent;
  var data = await fetchGet("escapeRoomController", "getRoomDetails", { language: language, export: blobCallback, id: roomId}, blobCallback);
  downloadFile(data, "escape-room", "json");
});

updateButton.addEventListener('click', function() {
  sessionStorage.setItem("tempRoom", sessionStorage.getItem('objectRoom'));
  window.location = 'updateRoom.html';
});

deleteButton.addEventListener('click', async function() {
  if (confirm('Are you sure you want to delete this room?')) {
    await fetchPost("escapeRoomController", "deleteRoom", { id: Number(roomId) });
    window.location='homepage.html';
  } else {
      return false;
  }
});