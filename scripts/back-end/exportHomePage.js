const objectContainer = document.getElementById('homepageObjectContainer');

async function getAllRooms(exportParam = false) {
  var callback = exportParam ? blobCallback : jsonCallback;
  var data = await fetchGet("escapeRoomController", "getAllRooms", { language: "en", export: exportParam }, callback);

  if (exportParam) {
      downloadFile(data, "all-escape-rooms", "json");
  }
  else {
    createGetObjects(data, 'rooms', 'homepageObjectContainer');
  }
}

getAllRooms();

var clearFilterButton = document.getElementById("clearFilterButton");
var submitFilterButton = document.getElementById("submitFilterButton");

clearFilterButton.addEventListener('click', function() {
  document.getElementById("filterForm").reset();
  objectContainer.innerHTML = "";
  getAllRooms();
});

submitFilterButton.addEventListener('click', async function() {
  constraints = {};
  if (document.getElementById('langTextBox').value != '') {
    constraints["language"] = document.getElementById('langTextBox').value;
  }
  if (document.getElementById('nameTextBox').value != '') {
    constraints["name"] = document.getElementById('nameTextBox').value;
  }
  if (document.getElementById('minDifficulty').value != '') {
    constraints["minDifficulty"] = document.getElementById('minDifficulty').value;
  }
  if (document.getElementById('maxDifficulty').value != '') {
    constraints["maxDifficulty"] = document.getElementById('maxDifficulty').value;
  }
  if (document.getElementById('minTimeLimit').value != '') {
    constraints["minTimeLimit"] = document.getElementById('minTimeLimit').value;
  }
  if (document.getElementById('maxTimeLimit').value != '') {
    constraints["maxTimeLimit"] = document.getElementById('maxTimeLimit').value;
  }
  if (document.getElementById('minPlayers').value != '') {
  constraints["minPlayers"] = document.getElementById('minPlayers').value;
  }
  if (document.getElementById('maxPlayers').value != '') {
  constraints["maxPlayers"] = document.getElementById('maxPlayers').value;
  }

  constraints["export"] = jsonCallback;

  var data = await fetchGet("escapeRoomController", "filterRooms", constraints, jsonCallback);
  objectContainer.innerHTML = "";
  createGetObjects(data, 'rooms', 'homepageObjectContainer');
});