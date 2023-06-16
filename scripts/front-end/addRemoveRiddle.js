const listButtons = document.querySelectorAll('.addAndDelete');

for (var i = 0; i < listButtons.length; i++) {
	listButtons[i].addEventListener('click', function() {
    const text = this.textContent;
    console.log(text + '\n');
    if (text === 'Add') {
      //TODO
    } else {
      //TODO
    }
	});
}

const cancelButton = document.getElementById('cancelButton');
cancelButton.addEventListener('click', function() {
  window.history.back();
});