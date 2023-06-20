const SaveButton = document.getElementById("submitButton");
const cancelButton = document.getElementById("cancelButton");

SaveButton.addEventListener('click', function() {
  var tempItems = JSON.parse(sessionStorage.getItem('tempRoom'));
  tempItems.riddles = JSON.parse(sessionStorage.getItem('riddles'));
  sessionStorage.setItem("tempRoom", JSON.stringify(tempItems));
  window.location = 'updateRoom.html';
});

cancelButton.addEventListener('click', function() {
  sessionStorage.setItem("riddles", JSON.parse(sessionStorage.getItem('tempRoom').riddles));
  window.location = 'updateRoom.html';
});