const addButton = document.getElementById('addButton');
const addRemoveRiddles = document.getElementById('addRemoveRiddles');

var languageTextBox = document.getElementById('langTextBox');
var timeLimitTextBox = document.getElementById('timeLimit');
var minPlayersTextBox = document.getElementById('minPlayers');
var maxPlayersTextBox = document.getElementById('maxPlayers');
var imageTextBox = document.getElementById('imgTextBox');
var difficultyTextBox = document.getElementById('difficulty');
var nameTextBox = document.getElementById('nameTextBox');

var tempItems = JSON.parse(sessionStorage.getItem("tempRoom"));

languageTextBox.value = tempItems.language;
timeLimitTextBox.value = tempItems.timeLimit;
minPlayersTextBox.value = tempItems.minPlayers;
maxPlayersTextBox.value = tempItems.maxPlayers;
nameTextBox.value = tempItems.name;
imageTextBox.value = tempItems.image;
difficultyTextBox.value = tempItems.difficulty;
var roomId = tempItems.id;

addRemoveRiddles.addEventListener('click', function() {
  sessionStorage.setItem("riddles", JSON.stringify(tempItems.riddles));
  setTempValues();
  sessionStorage.setItem("tempRoom", JSON.stringify(tempItems));
  window.location = 'addRemoveRiddlesAdd.html';
});

addButton.addEventListener('click', async function() {
  debugger;
  var roomToAdd = {
    language: document.getElementById('langTextBox').value,
    name: document.getElementById('nameTextBox').value,
    difficulty: document.getElementById('difficulty').value,
    timeLimit: document.getElementById('timeLimit').value,
    minPlayers: document.getElementById('minPlayers').value,
    maxPlayers: document.getElementById('maxPlayers').value,
    image: document.getElementById('imgTextBox').value,
    riddleIds: tempItems.riddles.map(r => r.id)
  };

  await fetchPost("escapeRoomController", "addRoom", { roomJson: roomToAdd });
  window.location = 'homepage.html';
});

function addRiddles() {
  createGetObjects(tempItems.riddles, 'riddles', 'addRoomRiddles');
}

addRiddles();

function setTempValues() {
  tempItems.language = languageTextBox.value;
  tempItems.timeLimit = timeLimitTextBox.value;
  tempItems.minPlayers = minPlayersTextBox.value;
  tempItems.maxPlayers = maxPlayersTextBox.value;
  tempItems.name = nameTextBox.value;
  tempItems.image = imageTextBox.value;
  tempItems.difficulty = difficultyTextBox.value;
  tempItems.id = roomId;
}