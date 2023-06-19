var importRoomButton = document.getElementById('importRoomButton');
var importRiddleButton = document.getElementById('importRiddleButton');

importRoomButton.addEventListener('click', function() {
  readFile("uploadRoomsFile", importRoomsHelper);
});

importRiddleButton.addEventListener('click', function() {
  readFile("uploadRiddlesFile", importRiddlesHelper);
});

function importRoomsHelper(fileContents) {
  try {
      var jsonContents = JSON.parse(fileContents);
      fetchPost("escapeRoomController", "importFromJson", { jsonContents: jsonContents });
  } catch (error) {
      console.error("Invalid JSON error: " + error);
  }
}

function importRiddlesHelper(fileContents) {
  try {
      var jsonContents = JSON.parse(fileContents);
      fetchPost("riddleController", "importFromJson", { jsonContents: jsonContents });
  } catch (error) {
      console.error("Invalid JSON error: " + error);
  }
}