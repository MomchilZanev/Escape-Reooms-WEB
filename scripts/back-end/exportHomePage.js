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

objectContainer.innerHTML = "";
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

  var askingExport = document.querySelector('input[name="export"]:checked').value;

  constraints["export"] = askingExport == 'Yes' ? blobCallback : jsonCallback;
  console.log(constraints["export"]);

  var data = await fetchGet("escapeRoomController", "filterRooms", constraints, constraints["export"]);
  if (askingExport == "Yes") {
    downloadFile(data, "filtered-escape-rooms", "json");
  } else {
    objectContainer.innerHTML = "";
    createGetObjects(data, 'rooms', 'homepageObjectContainer');
  }
});

var ExportHomepageRooms = document.getElementById("ExportHomepageRooms");
ExportHomepageRooms.addEventListener('click', function() {
  getAllRooms(true);
});

sessionStorage.setItem('tempRoom', JSON.stringify(setTempRoomValues()));
console.log(sessionStorage.getItem('tempRoom'));

function setTempRoomValues() {
  var tempItems = {};
  tempItems.language = '';
  tempItems.timeLimit = '';
  tempItems.minPlayers = '';
  tempItems.maxPlayers = '';
  tempItems.name = '';
  tempItems.image = '';
  tempItems.difficulty = '';
  tempItems.id = '';
  tempItems.riddles = [];
  return tempItems;
}