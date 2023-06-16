var cancel = document.getElementById("cancelUpdateRiddle");
var submitUpdateRiddle = document.getElementById("submitUpdateButton");

cancel.addEventListener('click', function() {
  window.location = 'dataPageRoom.html';
});

submitUpdateRiddle.addEventListener('click', function() {
  if (confirm('Are you sure you want to apply the changes?')) {
    //yourformelement.submit();
    window.location = 'dataPageRoom.html';
  } else {
      return false;
  }
});