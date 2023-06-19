function exportDataRoom() {
  objectString = sessionStorage.getItem("objectRoom");
  const object = JSON.parse(objectString);

  var objectTitle = document.getElementsByClassName('dataTitle')[0];
  console.log(objectTitle);
  objectTitle.textContent = object.name;
  var imageTag = document.getElementById('dataImage');
  imageTag.src = object.image;

  createMetadata(object.language, 'Language',);
  createMetadata(object.difficulty, 'Difficulty');
  createMetadata(object.timeLimit, 'TimeLimit');
  createMetadata(object.minPlayers, 'MinPlayers');
  createMetadata(object.maxPlayers, 'MaxPlayers');

  createGetObjects(object.riddles, 'riddles', 'dataPageObjectContainer');
}

function exportDataRiddle() {
  var objectString = sessionStorage.getItem("objectRiddle");
  const object = JSON.parse(objectString);
  
  var imageTag = document.getElementById('dataImage');
  imageTag.src = object.image;

  createMetadata(object.language, 'Language');
  createMetadata(object.type, 'Type');
  createMetadata(object.task, 'Problem');
  createMetadata(object.solution, 'Solution');
}


function createMetadata(element, keyword) {
  var metadataValue = document.getElementById('metadata' + keyword + 'Value');
  metadataValue.textContent = element;
}