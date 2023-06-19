function createGetObjects(data, type, container) {
  for (let i = 0; i < data.length; i++) {
    createObject(data[i], type, container);
  }
}

function createObject(object, type, container) {
  var objectBox = document.createElement('div');
  objectBox.className = 'objectBox';

  var anchorTag = document.createElement('a');
  anchorTag.id = object.id;
  anchorTag.addEventListener('click', function() {
    if (type === 'rooms') {
       sessionStorage.setItem("objectRoom", JSON.stringify(object));
       window.location = 'dataPageRoom.html';
    } else { 
      sessionStorage.setItem("objectRiddle", JSON.stringify(object));
      window.location = 'dataPageRiddle.html'; 
    }
  });

  var imageTag = document.createElement('img');
  imageTag.src = object.image;

  anchorTag.appendChild(imageTag);

  var paragraphTag = document.createElement('p');
  paragraphTag.className = 'objectTitle';
  if (type == 'rooms') {
    paragraphTag.textContent = object.name;
  } else {
    paragraphTag.textContent = object.type;
  }

  objectBox.appendChild(anchorTag);
  objectBox.appendChild(paragraphTag);

  document.getElementById(container).appendChild(objectBox);
}