const addButton = document.getElementById('addButton');
const addRemoveRiddles = document.getElementById('addRemoveRiddles');
var addedRiddles;

if (sessionStorage.getItem("originRiddles") == null) {
  addedRiddles = [];
} else {
  addedRiddles = JSON.parse(sessionStorage.getItem("originRiddles"));
}

addRemoveRiddles.addEventListener('click', function() {
  sessionStorage.setItem("riddles", JSON.stringify(addedRiddles));
  sessionStorage.setItem("originRiddles", JSON.stringify(addedRiddles));
  window.location = 'addRemoveRiddles.html';
});

addButton.addEventListener('click', function() {
  console.log(JSON.stringify(addedRiddles));
  var roomToAdd = {
    language: document.getElementById('langTextBox').value,
    name: document.getElementById('nameTextBox').value,
    difficulty: document.getElementById('difficulty').value,
    timeLimit: document.getElementById('timeLimit').value,
    minPlayers: document.getElementById('minPlayers').value,
    maxPlayers: document.getElementById('maxPlayers').value,
    image: document.getElementById('imgTextBox').value,
    riddles: JSON.stringify(addedRiddles)
  };

  fetchPost("escapeRoomController", "addRoom", { roomJson: roomToAdd });

  window.location = 'homepage.html';
});

function addRiddles() {
  createGetObjects(addedRiddles, 'riddles', 'addRoomRiddles');
}

addRiddles();