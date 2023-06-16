var updateButton = document.getElementById("updateRoom");
var deleteButton = document.getElementById("deleteRoom");

updateButton.addEventListener('click', function() {
  window.location = 'updateRoom.html';
});

deleteButton.addEventListener('click', function() {
  if (confirm('Do you want to delete this page?')) {
    //yourformelement.submit();
    window.location = 'homepage.html';
  } else {
      return false;
  }
});