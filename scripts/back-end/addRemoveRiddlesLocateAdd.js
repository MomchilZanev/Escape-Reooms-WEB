const SaveButton = document.getElementById("submitButton");
const cancelButton = document.getElementById("cancelButton");

SaveButton.addEventListener('click', function() {
  var tempItems = JSON.parse(sessionStorage.getItem('tempRoom'));
  tempItems.riddles = JSON.parse(sessionStorage.getItem('riddles'));
  sessionStorage.setItem("tempRoom", JSON.stringify(tempItems));
  window.location = 'addNewRoom.html';
});

cancelButton.addEventListener('click', function() {
  sessionStorage.setItem("riddles", sessionStorage.getItem('originRiddles'));
  window.location = 'addNewRoom.html';
});