var languageTextBox = document.getElementById('langTextBox');
var typeTextBox = document.getElementById('typeSearch');
var taskTextBox = document.getElementById('describeTextBoxProblem');
var solutionTextBox = document.getElementById('describeTextBoxSolution');
var imageTextBox = document.getElementById('imgTextBox');

var riddleItems = JSON.parse(sessionStorage.getItem("objectRiddle"));

languageTextBox.value = riddleItems.language;
typeTextBox.value = riddleItems.type;
taskTextBox.value = riddleItems.task;
solutionTextBox.value = riddleItems.solution;
imageTextBox.value = riddleItems.image;
var riddlesId = riddleItems.id;

var cancel = document.getElementById("cancelUpdateRiddle");
var submitUpdateRiddle = document.getElementById("submitUpdateButton");

cancel.addEventListener('click', function() {
  window.history.back();
});

submitUpdateRiddle.addEventListener('click', async function() {
  if (confirm('Are you sure you want to apply the changes?')) {

    var riddleToUpdate = {
      id: riddlesId,
      type: typeTextBox.value,
      language: languageTextBox.value,
      task: taskTextBox.value,
      solution: solutionTextBox.value,
      image: imageTextBox.value
    };

    console.log(JSON.stringify(riddleToUpdate));
    sessionStorage.setItem("objectRiddle", JSON.stringify(riddleToUpdate));
    await fetchPost("riddleController", "updateRiddle", { riddleJson: riddleToUpdate });
    window.location=document.referrer;
  } else {
      return false;
  }
});