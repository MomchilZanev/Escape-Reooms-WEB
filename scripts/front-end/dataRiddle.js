var updateRiddle = document.getElementById("updateRiddle");
var deleteRiddle = document.getElementById("deleteRiddle");

updateRiddle.addEventListener('click', function() {
  window.location = 'updateRiddle.html';
});

deleteRiddle.addEventListener('click', function() {
  if (confirm('Do you want to delete this page?')) {
    //yourformelement.submit();
    window.location = 'homepage.html';
  } else {
      return false;
  }
});