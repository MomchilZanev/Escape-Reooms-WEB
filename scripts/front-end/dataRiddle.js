var updateRiddle = document.getElementById("updateRiddle");
var deleteRiddle = document.getElementById("deleteRiddle");

updateRiddle.addEventListener('click', function() {
  window.location = 'updateRiddle.html';
});

deleteRiddle.addEventListener('click', function() {
  if (confirm('Do you want to delete this riddle?')) {
    //yourformelement.submit();
    window.history.back();
  } else {
      return false;
  }
});