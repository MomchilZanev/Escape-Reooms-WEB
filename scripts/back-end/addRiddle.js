var addButton = document.getElementById('addButton');

addButton.addEventListener('click', async function() {
  var riddleToAdd = {
    language: document.getElementById('langTextBox').value,
    type: document.getElementById('typeSearch').value,
    task: document.getElementById('describeTextBoxProblem').value,
    solution: document.getElementById('describeTextBoxSolution').value,
    image: document.getElementById('imgTextBox').value
  };

  await fetchPost("riddleController", "addRiddle", { riddleJson: riddleToAdd });

  window.location = 'homepage.html';
});