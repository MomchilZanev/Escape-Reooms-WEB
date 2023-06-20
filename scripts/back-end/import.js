var importRoomButton = document.getElementById('importRoomButton');
var importRiddleButton = document.getElementById('importRiddleButton');

importRoomButton.addEventListener('click', function () {
  try {
    readFile("uploadRoomsFile", importRoomsHelper);
  } catch (error) {
    console.log(error.message);
  }
});

importRiddleButton.addEventListener('click', function () {
  try {
    readFile("uploadRiddlesFile", importRiddlesHelper);
  } catch (error) {
    console.log(error.message);
  }
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