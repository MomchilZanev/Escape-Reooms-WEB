exportDataRoom();

var updateButton = document.getElementById("updateRoom");
var deleteButton = document.getElementById("deleteRoom");

updateButton.addEventListener('click', function() {
  window.location = 'updateRoom.html';
});

deleteButton.addEventListener('click', function() {
  if (confirm('Do you want to delete this room?')) {
    //yourformelement.submit();
    window.history.back();
  } else {
      return false;
  }
});