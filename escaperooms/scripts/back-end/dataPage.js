function loadDataRoom() {
  objectString = sessionStorage.getItem("objectRoom");
  const object = JSON.parse(objectString);

  var objectTitle = document.getElementsByClassName('dataTitle')[0];
  objectTitle.textContent = object.name;
  var imageTag = document.getElementById('dataImage');
  imageTag.src = object.image == "no-image-available.jpg" ? location.href.split("/escaperooms/")[0] + "/escaperooms/images/" + object.image : object.image;

  createMetadata(object.language, 'Language',);
  createMetadata(object.difficulty, 'Difficulty');
  createMetadata(object.timeLimit, 'TimeLimit');
  createMetadata(object.minPlayers, 'MinPlayers');
  createMetadata(object.maxPlayers, 'MaxPlayers');
  createMetadata(object.id, 'Id');

  createGetObjects(object.riddles, 'riddles', 'dataPageObjectContainer');
}

function loadDataRiddle() {
  var objectString = sessionStorage.getItem("objectRiddle");
  const object = JSON.parse(objectString);
  
  var imageTag = document.getElementById('dataImage');
  imageTag.src = object.image == "no-image-available.jpg" ? location.href.split("/escaperooms/")[0] + "/escaperooms/images/" + object.image : object.image;

  createMetadata(object.language, 'Language');
  createMetadata(object.type, 'Type');
  createMetadata(object.task, 'Problem');
  createMetadata(object.solution, 'Solution');
  createMetadata(object.id, 'Id');
}


function createMetadata(element, keyword) {
  var metadataValue = document.getElementById('metadata' + keyword + 'Value');
  metadataValue.textContent = element;
}