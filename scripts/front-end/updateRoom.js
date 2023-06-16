var cancel = document.getElementById("cancelUpdateRoom");
var submitUpdateRiddle = document.getElementById("submitUpdateButton");

cancel.addEventListener('click', function() {
  window.history.back();
});

submitUpdateRiddle.addEventListener('click', function() {
  if (confirm('Are you sure you want to apply the changes?')) {
    //yourformelement.submit();
    window.location = 'dataPageRoom.html';
  } else {
      return false;
  }
});